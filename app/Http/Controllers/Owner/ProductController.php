<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $businessId = auth()->user()->business_id;

        $query = Product::where('business_id', $businessId)->with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'out') {
                $query->where('stock', '<=', 0);
            } elseif ($request->stock_status === 'low') {
                $query->where('stock', '>', 0)->whereColumn('stock', '<=', 'min_stock');
            } elseif ($request->stock_status === 'safe') {
                $query->whereColumn('stock', '>', 'min_stock');
            }
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->latest('id')->paginate(15);
        $categories = ProductCategory::where('business_id', $businessId)->where('is_active', true)->get();

        $totalProducts = Product::where('business_id', $businessId)->count();
        $lowStockCount = Product::where('business_id', $businessId)
            ->where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'min_stock')
            ->count();
        $outOfStockCount = Product::where('business_id', $businessId)
            ->where('stock', '<=', 0)
            ->count();

        return view('owner.products.index', compact(
            'products',
            'categories',
            'totalProducts',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    public function create(): View
    {
        $businessId = auth()->user()->business_id;
        $categories = ProductCategory::where('business_id', $businessId)->where('is_active', true)->get();

        return view('owner.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:product_categories,id',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.exists' => 'Kategori produk yang dipilih tidak ditemukan.',
            'selling_price.required' => 'Harga jual produk wajib diisi.',
            'selling_price.numeric' => 'Harga jual harus berupa nominal angka.',
            'selling_price.min' => 'Harga jual tidak boleh kurang dari 0.',
            'purchase_price.numeric' => 'Harga beli (HPP) harus berupa nominal angka.',
            'purchase_price.min' => 'Harga beli (HPP) tidak boleh kurang dari 0.',
            'unit.required' => 'Satuan produk (misal: Unit, Pcs, Set) wajib diisi.',
            'stock.required' => 'Stok awal produk wajib diisi.',
            'stock.integer' => 'Stok awal harus berupa angka bulat.',
            'stock.min' => 'Stok awal tidak boleh bernilai negatif.',
            'min_stock.required' => 'Batas minimum stok peringatan wajib diisi.',
            'min_stock.integer' => 'Batas minimum stok harus berupa angka bulat.',
            'min_stock.min' => 'Batas minimum stok tidak boleh negatif.',
            'image.image' => 'Berkas yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar yang didukung hanya: JPEG, PNG, JPG, atau WEBP.',
            'image.max' => 'Ukuran berkas gambar maksimal adalah 2 MB.',
        ]);

        if ($request->filled('category_id')) {
            $cat = ProductCategory::where('id', $request->category_id)->where('business_id', $businessId)->first();
            if (! $cat) {
                return back()->withInput()->with('error', 'Kategori produk yang dipilih tidak valid atau bukan milik usaha Anda.');
            }
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        DB::transaction(function () use ($request, $businessId, $user, $imagePath) {
            $initialStock = (int) $request->stock;

            $product = Product::create([
                'business_id' => $businessId,
                'category_id' => $request->category_id,
                'name' => $request->name,
                'sku' => $request->sku,
                'selling_price' => clean_number($request->selling_price),
                'purchase_price' => $request->filled('purchase_price') ? clean_number($request->purchase_price) : null,
                'unit' => strtolower($request->unit),
                'stock' => $initialStock,
                'min_stock' => (int) $request->min_stock,
                'description' => $request->description,
                'image' => $imagePath,
                'is_active' => true,
            ]);

            if ($initialStock > 0) {
                StockMovement::create([
                    'business_id' => $businessId,
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'type' => 'initial',
                    'quantity' => $initialStock,
                    'stock_before' => 0,
                    'stock_after' => $initialStock,
                    'notes' => 'Stok awal saat penambahan produk baru',
                ]);
            }
        });

        return redirect()->route('owner.products.index')
            ->with('success', "Produk \"{$request->name}\" berhasil ditambahkan ke katalog toko.");
    }

    public function show(Product $product): View
    {
        $this->authorizeProduct($product);
        $product->load(['category', 'stockMovements.user']);

        return view('owner.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $this->authorizeProduct($product);
        $businessId = auth()->user()->business_id;
        $categories = ProductCategory::where('business_id', $businessId)->where('is_active', true)->get();

        return view('owner.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);
        $businessId = auth()->user()->business_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:product_categories,id',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.exists' => 'Kategori produk yang dipilih tidak ditemukan.',
            'selling_price.required' => 'Harga jual produk wajib diisi.',
            'selling_price.numeric' => 'Harga jual harus berupa nominal angka.',
            'selling_price.min' => 'Harga jual tidak boleh kurang dari 0.',
            'purchase_price.numeric' => 'Harga beli (HPP) harus berupa nominal angka.',
            'purchase_price.min' => 'Harga beli (HPP) tidak boleh kurang dari 0.',
            'unit.required' => 'Satuan produk (misal: Unit, Pcs, Set) wajib diisi.',
            'min_stock.required' => 'Batas minimum stok peringatan wajib diisi.',
            'min_stock.integer' => 'Batas minimum stok harus berupa angka bulat.',
            'min_stock.min' => 'Batas minimum stok tidak boleh negatif.',
            'image.image' => 'Berkas yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar yang didukung hanya: JPEG, PNG, JPG, atau WEBP.',
            'image.max' => 'Ukuran berkas gambar maksimal adalah 2 MB.',
            'is_active.required' => 'Status keaktifan produk wajib ditentukan.',
        ]);

        if ($request->filled('category_id')) {
            $cat = ProductCategory::where('id', $request->category_id)->where('business_id', $businessId)->first();
            if (! $cat) {
                return back()->withInput()->with('error', 'Kategori produk yang dipilih tidak valid atau bukan milik usaha Anda.');
            }
        }

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'sku' => $request->sku,
            'selling_price' => clean_number($request->selling_price),
            'purchase_price' => $request->filled('purchase_price') ? clean_number($request->purchase_price) : null,
            'unit' => strtolower($request->unit),
            'min_stock' => (int) $request->min_stock,
            'description' => $request->description,
            'image' => $imagePath,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('owner.products.index')
            ->with('success', "Data produk \"{$product->name}\" berhasil diperbarui.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);
        $productName = $product->name;

        if ($product->transactionItems()->exists()) {
            $product->update(['is_active' => false]);

            return redirect()->route('owner.products.index')
                ->with('warning', "Produk \"{$productName}\" memiliki riwayat transaksi penjualan sehingga tidak dapat dihapus permanen. Status produk berhasil dinonaktifkan.");
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('owner.products.index')
            ->with('success', "Produk \"{$productName}\" berhasil dihapus dari inventaris.");
    }

    public function toggleStatus(Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);
        $product->update(['is_active' => ! $product->is_active]);

        $statusText = $product->is_active ? 'diaktifkan kembali dan dapat dijual di kasir' : 'dinonaktifkan dari transaksi kasir';

        return redirect()->route('owner.products.index')
            ->with('success', "Produk \"{$product->name}\" berhasil {$statusText}.");
    }

    private function authorizeProduct(Product $product): void
    {
        if ($product->business_id !== auth()->user()->business_id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }
    }
}
