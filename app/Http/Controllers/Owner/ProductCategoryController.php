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
        ]);

        ProductCategory::create([
            'business_id' => $businessId,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('owner.product-categories.index')
            ->with('success', 'Kategori produk berhasil ditambahkan.');
    }

    public function update(Request $request, ProductCategory $productCategory): RedirectResponse
    {
        $this->authorizeCategory($productCategory);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $productCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->route('owner.product-categories.index')
            ->with('success', 'Kategori produk berhasil diperbarui.');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        $this->authorizeCategory($productCategory);

        if ($productCategory->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.');
        }

        $productCategory->delete();

        return redirect()->route('owner.product-categories.index')
            ->with('success', 'Kategori produk berhasil dihapus.');
    }

    private function authorizeCategory(ProductCategory $productCategory): void
    {
        if ($productCategory->business_id !== auth()->user()->business_id) {
            abort(403, 'Anda tidak memiliki akses ke kategori ini.');
        }
    }
}
