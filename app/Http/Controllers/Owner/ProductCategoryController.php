<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function index(): View
    {
        $businessId = auth()->user()->business_id;
        $categories = ProductCategory::where('business_id', $businessId)
            ->withCount('products')
            ->latest('id')
            ->paginate(15);

        return view('owner.product-categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $businessId = auth()->user()->business_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama kategori produk wajib diisi.',
            'name.max' => 'Nama kategori tidak boleh lebih dari 255 karakter.',
            'description.max' => 'Keterangan kategori maksimal 500 karakter.',
        ]);

        ProductCategory::create([
            'business_id' => $businessId,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('owner.product-categories.index')
            ->with('success', "Kategori produk \"{$request->name}\" berhasil ditambahkan.");
    }

    public function update(Request $request, ProductCategory $productCategory): RedirectResponse
    {
        $this->authorizeCategory($productCategory);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama kategori produk wajib diisi.',
            'name.max' => 'Nama kategori tidak boleh lebih dari 255 karakter.',
            'description.max' => 'Keterangan kategori maksimal 500 karakter.',
        ]);

        $productCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->route('owner.product-categories.index')
            ->with('success', "Kategori produk \"{$productCategory->name}\" berhasil diperbarui.");
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        $this->authorizeCategory($productCategory);
        $count = $productCategory->products()->count();

        if ($count > 0) {
            return back()->with('error', "Kategori \"{$productCategory->name}\" tidak dapat dihapus karena masih digunakan oleh {$count} produk. Silakan ubah kategori produk-produk tersebut terlebih dahulu.");
        }

        $categoryName = $productCategory->name;
        $productCategory->delete();

        return redirect()->route('owner.product-categories.index')
            ->with('success', "Kategori produk \"{$categoryName}\" berhasil dihapus.");
    }

    private function authorizeCategory(ProductCategory $productCategory): void
    {
        if ($productCategory->business_id !== auth()->user()->business_id) {
            abort(403, 'Anda tidak memiliki akses ke kategori ini.');
        }
    }
}
