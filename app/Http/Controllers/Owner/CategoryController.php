<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $categories = TransactionCategory::where('business_id', $user->business_id)
            ->latest()
            ->paginate(15);

        return view('owner.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('owner.categories.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:masuk,keluar,operasional',
        ], [
            'name.required' => 'Nama kategori transaksi wajib diisi.',
            'name.max' => 'Nama kategori transaksi maksimal 255 karakter.',
            'type.required' => 'Jenis transaksi wajib dipilih.',
            'type.in' => 'Pilihan jenis transaksi harus berupa kas masuk, kas keluar, atau biaya operasional.',
        ]);

        TransactionCategory::create([
            'business_id' => $user->business_id,
            'name' => $request->name,
            'type' => $request->type,
            'is_active' => true,
        ]);

        return redirect()->route('owner.categories.index')
            ->with('success', "Kategori transaksi \"{$request->name}\" berhasil ditambahkan.");
    }

    public function edit(TransactionCategory $category)
    {
        if ($category->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        return view('owner.categories.edit', compact('category'));
    }

    public function update(Request $request, TransactionCategory $category)
    {
        if ($category->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:masuk,keluar,operasional',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Nama kategori transaksi wajib diisi.',
            'name.max' => 'Nama kategori transaksi maksimal 255 karakter.',
            'type.required' => 'Jenis transaksi wajib dipilih.',
            'type.in' => 'Pilihan jenis transaksi harus berupa kas masuk, kas keluar, atau biaya operasional.',
            'is_active.required' => 'Status keaktifan kategori wajib ditentukan.',
        ]);

        $category->update($request->only(['name', 'type', 'is_active']));

        return redirect()->route('owner.categories.index')
            ->with('success', "Kategori transaksi \"{$category->name}\" berhasil diperbarui.");
    }

    public function destroy(TransactionCategory $category)
    {
        if ($category->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $categoryName = $category->name;
        $category->update(['is_active' => false]);

        return redirect()->route('owner.categories.index')
            ->with('success', "Kategori transaksi \"{$categoryName}\" berhasil dinonaktifkan.");
    }
}
