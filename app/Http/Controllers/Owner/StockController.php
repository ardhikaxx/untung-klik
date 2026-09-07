<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $businessId = auth()->user()->business_id;

        $query = StockMovement::where('business_id', $businessId)
            ->with(['product', 'user']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $movements = $query->latest('id')->paginate(20);

        $products = Product::where('business_id', $businessId)->where('is_active', true)->orderBy('name')->get();

        $lowStockProducts = Product::where('business_id', $businessId)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->get();

        $outOfStockProducts = Product::where('business_id', $businessId)
            ->where('is_active', true)
            ->where('stock', '<=', 0)
            ->orderBy('name')
            ->get();

        return view('owner.stock.index', compact(
            'movements',
            'products',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }

    public function adjust(): View
    {
        $businessId = auth()->user()->business_id;
        $products = Product::where('business_id', $businessId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('owner.stock.adjust', compact('products'));
    }

    public function storeAdjustment(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,damaged,lost,correction,adjustment',
            'amount' => 'required|integer|min:0',
            'notes' => 'required|string|max:500',
        ]);

        $product = Product::where('id', $request->product_id)
            ->where('business_id', $businessId)
            ->firstOrFail();

        $before = $product->stock;
        $amount = (int) $request->amount;
        $type = $request->type;
        $diff = 0;
        $after = 0;

        if ($type === 'in') {
            $diff = $amount;
            $after = $before + $diff;
        } elseif (in_array($type, ['out', 'damaged', 'lost', 'adjustment'])) {
            if ($amount > $before) {
                return back()->withInput()->with('error', "Jumlah pengurangan ({$amount}) melebihi stok yang ada ({$before} {$product->unit}).");
            }
            $diff = -$amount;
            $after = $before + $diff;
        } elseif ($type === 'correction') {
            $after = $amount;
            $diff = $after - $before;
        }

        DB::transaction(function () use ($businessId, $user, $product, $type, $diff, $before, $after, $request) {
            $product->update(['stock' => $after]);

            StockMovement::create([
                'business_id' => $businessId,
                'product_id' => $product->id,
                'user_id' => $user->id,
                'type' => $type,
                'quantity' => $diff,
                'stock_before' => $before,
                'stock_after' => $after,
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('owner.stock.index')
            ->with('success', "Stok {$product->name} berhasil diperbarui (Stok saat ini: {$after} {$product->unit}).");
    }
}
