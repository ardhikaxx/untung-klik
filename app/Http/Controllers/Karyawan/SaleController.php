<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
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

        $period = $request->get('period', '');
        $today = now()->toDateString();

        if ($period === 'today') {
            $query->whereDate('transaction_date', $today);
        } elseif ($period === 'yesterday') {
            $query->whereDate('transaction_date', now()->subDay()->toDateString());
        } elseif ($period === '7days') {
            $query->whereDate('transaction_date', '>=', now()->subDays(6)->toDateString());
        } elseif ($period === 'this_month') {
            $query->whereMonth('transaction_date', now()->month)->whereYear('transaction_date', now()->year);
        } else {
            if ($request->filled('start_date')) {
                $query->whereDate('transaction_date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('transaction_date', '<=', $request->end_date);
            }
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhereHas('items', function ($itemQuery) use ($search) {
                        $itemQuery->where('product_name', 'like', "%{$search}%");
                    });
            });
        }

        $filteredSalesTotal = (clone $query)->sum('amount');
        $filteredSalesCount = (clone $query)->count();

        $saleIds = (clone $query)->pluck('id');
        $filteredItemsCount = TransactionItem::whereIn('transaction_id', $saleIds)->sum('quantity');
        $averageOrderValue = $filteredSalesCount > 0 ? round($filteredSalesTotal / $filteredSalesCount) : 0;

        $sales = $query->latest('transaction_date')->latest('id')->paginate(15);

        $todaySalesTotal = Transaction::where('business_id', $businessId)
            ->where('is_sale', true)
            ->where('user_id', $user->id)
            ->whereDate('transaction_date', $today)
            ->sum('amount');

        $todaySalesCount = Transaction::where('business_id', $businessId)
            ->where('is_sale', true)
            ->where('user_id', $user->id)
            ->whereDate('transaction_date', $today)
            ->count();

        return view('karyawan.sales.index', compact(
            'sales',
            'todaySalesTotal',
            'todaySalesCount',
            'filteredSalesTotal',
            'filteredSalesCount',
            'filteredItemsCount',
            'averageOrderValue',
            'period'
        ));
    }

    public function create(): View
    {
        $businessId = auth()->user()->business_id;
        $products = Product::where('business_id', $businessId)
            ->where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        $categories = ProductCategory::where('business_id', $businessId)
            ->where('is_active', true)
            ->get();

        return view('karyawan.sales.create', compact('products', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        $request->validate([
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'discount' => 'nullable|numeric|min:0',
            'cash_received' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'items.required' => 'Keranjang penjualan masih kosong. Pilih minimal satu produk.',
            'items.min' => 'Pilih minimal satu produk untuk transaksi penjualan.',
            'items.*.product_id.required' => 'Pilihan produk tidak valid.',
            'items.*.product_id.exists' => 'Produk yang dipilih tidak ditemukan dalam katalog.',
            'items.*.quantity.required' => 'Jumlah barang wajib diisi.',
            'items.*.quantity.integer' => 'Jumlah barang harus berupa bilangan bulat.',
            'items.*.quantity.min' => 'Jumlah produk minimal 1 unit.',
            'discount.numeric' => 'Potongan diskon harus berupa angka.',
            'discount.min' => 'Potongan diskon tidak boleh bernilai negatif.',
            'cash_received.numeric' => 'Nominal uang tunai diterima harus berupa angka.',
            'cash_received.min' => 'Nominal uang tunai diterima tidak boleh negatif.',
        ]);

        $createdTransaction = null;

        try {
            DB::transaction(function () use ($request, $businessId, $user, &$createdTransaction) {
                $subtotalAmount = 0;
                $validatedItems = [];

                foreach ($request->items as $item) {
                    $product = Product::where('id', $item['product_id'])
                        ->where('business_id', $businessId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $qty = (int) $item['quantity'];

                    if (! $product->is_active) {
                        throw new \Exception("Produk \"{$product->name}\" saat ini sedang dinonaktifkan sehingga tidak dapat dijual.");
                    }

                    if ($product->stock < $qty) {
                        throw new \Exception("Stok produk \"{$product->name}\" tidak mencukupi. Sisa stok tersedia: {$product->stock} {$product->unit}, permintaan: {$qty} {$product->unit}.");
                    }

                    $unitPrice = (float) $product->selling_price;
                    $purchasePrice = (float) ($product->purchase_price ?: 0);
                    $subtotal = $qty * $unitPrice;
                    $subtotalAmount += $subtotal;

                    $validatedItems[] = [
                        'product' => $product,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'purchase_price' => $purchasePrice,
                        'subtotal' => $subtotal,
                    ];
                }

                $discount = (float) $request->get('discount', 0);
                $finalAmount = max(0, $subtotalAmount - $discount);

                $cashReceived = $request->filled('cash_received') ? (float) $request->cash_received : null;
                $cashChange = null;
                if ($cashReceived !== null) {
                    if (in_array(strtolower($request->payment_method), ['cash', 'tunai']) && $cashReceived < $finalAmount) {
                        $shortage = $finalAmount - $cashReceived;
                        throw new \Exception('Uang tunai yang diterima (Rp '.number_format($cashReceived, 0, ',', '.').') kurang dari total tagihan (Rp '.number_format($finalAmount, 0, ',', '.').'). Kekurangan: Rp '.number_format($shortage, 0, ',', '.').'.');
                    }
                    $cashChange = max(0, $cashReceived - $finalAmount);
                }

                $todayCount = Transaction::where('business_id', $businessId)
                    ->where('is_sale', true)
                    ->whereDate('created_at', today())
                    ->count() + 1;
                $invoiceNumber = 'PJ-'.date('Ymd').'-'.str_pad($todayCount, 4, '0', STR_PAD_LEFT);

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
                    'invoice_number' => $invoiceNumber,
                    'customer_name' => $request->customer_name ?: 'Pelanggan Umum',
                    'customer_phone' => $request->customer_phone,
                    'amount' => $finalAmount,
                    'discount' => $discount,
                    'transaction_date' => $request->transaction_date,
                    'source' => $request->customer_name ?: 'Penjualan Toko',
                    'description' => $desc,
                    'payment_method' => $request->payment_method,
                    'cash_received' => $cashReceived,
                    'cash_change' => $cashChange,
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
                        'purchase_price' => $entry['purchase_price'],
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
                        'notes' => "Penjualan #{$transaction->invoice_number}",
                        'reference_id' => $transaction->id,
                    ]);
                }

                $createdTransaction = $transaction;
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage())->with('warning', $e->getMessage());
        }

        return redirect()->route('karyawan.sales.show', $createdTransaction)
            ->with('success', "Transaksi penjualan #{$createdTransaction->invoice_number} berhasil dicatat dan stok barang telah diperbarui! Struk nota siap dicetak.");
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
