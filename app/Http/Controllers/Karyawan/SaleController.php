<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\TransactionItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        $query = Transaction::where('business_id', $businessId)
            ->where('is_sale', true)
            ->where('user_id', $user->id)
            ->with(['items.product']);

        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        $sales = $query->latest('transaction_date')->latest('id')->paginate(15);

        $todaySalesTotal = Transaction::where('business_id', $businessId)
            ->where('is_sale', true)
            ->where('user_id', $user->id)
            ->whereDate('transaction_date', now()->toDateString())
            ->sum('amount');

        $todaySalesCount = Transaction::where('business_id', $businessId)
            ->where('is_sale', true)
            ->where('user_id', $user->id)
            ->whereDate('transaction_date', now()->toDateString())
            ->count();

        return view('karyawan.sales.index', compact('sales', 'todaySalesTotal', 'todaySalesCount'));
    }

    public function create(): View
    {
        $businessId = auth()->user()->business_id;
        $products = Product::where('business_id', $businessId)
            ->where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('karyawan.sales.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        $request->validate([
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'items.required' => 'Pilih minimal satu produk untuk dicatat.',
            'items.min' => 'Pilih minimal satu produk untuk dicatat.',
            'items.*.quantity.min' => 'Jumlah produk minimal 1.',
        ]);

        try {
            DB::transaction(function () use ($request, $businessId, $user) {
                $totalAmount = 0;
                $validatedItems = [];

                foreach ($request->items as $item) {
                    $product = Product::where('id', $item['product_id'])
                        ->where('business_id', $businessId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $qty = (int) $item['quantity'];

                    if (! $product->is_active) {
                        throw new \Exception("Produk {$product->name} sedang dinonaktifkan dan tidak dapat dijual.");
                    }

                    if ($product->stock < $qty) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi (Tersedia: {$product->stock} {$product->unit}, Permintaan: {$qty} {$product->unit}).");
                    }

                    $unitPrice = (float) $product->selling_price;
                    $subtotal = $qty * $unitPrice;
                    $totalAmount += $subtotal;

                    $validatedItems[] = [
                        'product' => $product,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ];
                }

                $category = TransactionCategory::firstOrCreate(
                    [
                        'business_id' => $businessId,
                        'name' => 'Penjualan Produk',
                        'type' => 'masuk',
                    ],
                    [
                        'is_active' => true,
                    ]
                );

                $desc = $request->description;
                if (! $desc) {
                    $itemNames = collect($validatedItems)->pluck('product.name')->take(2)->implode(', ');
                    $count = count($validatedItems);
                    $desc = "Penjualan {$count} produk ({$itemNames}".($count > 2 ? ', dll' : '').')';
                }

                $transaction = Transaction::create([
                    'business_id' => $businessId,
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'type' => 'masuk',
                    'is_sale' => true,
                    'amount' => $totalAmount,
                    'transaction_date' => $request->transaction_date,
                    'source' => 'Penjualan Produk',
                    'description' => $desc,
                    'payment_method' => $request->payment_method,
                ]);

                foreach ($validatedItems as $entry) {
                    $product = $entry['product'];
                    $qty = $entry['quantity'];
                    $before = $product->stock;
                    $after = $before - $qty;

                    TransactionItem::create([
                        'business_id' => $businessId,
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $qty,
                        'unit_price' => $entry['unit_price'],
                        'subtotal' => $entry['subtotal'],
                    ]);

                    $product->update(['stock' => $after]);

                    StockMovement::create([
                        'business_id' => $businessId,
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'type' => 'sale',
                        'quantity' => -$qty,
                        'stock_before' => $before,
                        'stock_after' => $after,
                        'notes' => "Penjualan #{$transaction->id}",
                        'reference_id' => $transaction->id,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('warning', $e->getMessage());
        }

        return redirect()->route('karyawan.sales.index')
            ->with('success', 'Penjualan berhasil dicatat dan stok produk otomatis terpotong.');
    }

    public function show(Transaction $sale): View
    {
        $this->authorizeSale($sale);
        $sale->load(['items.product', 'user', 'category']);

        return view('karyawan.sales.show', compact('sale'));
    }

    private function authorizeSale(Transaction $sale): void
    {
        $user = auth()->user();
        if ($sale->business_id !== $user->business_id || ! $sale->is_sale || $sale->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke data penjualan ini.');
        }
    }
}
