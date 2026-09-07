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
        ]);

        TransactionCategory::create([
            'business_id' => $user->business_id,
            'name' => $request->name,
            'type' => $request->type,
            'is_active' => true,
        ]);

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
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
        ]);

        $category->update($request->only(['name', 'type', 'is_active']));

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(TransactionCategory $category)
    {
        if ($category->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $category->update(['is_active' => false]);

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori berhasil dinonaktifkan.');
    }
}
