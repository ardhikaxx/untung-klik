<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\OperationalExpense;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;

class OperationalExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = OperationalExpense::where('business_id', $user->business_id)->with('user', 'category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('expense_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('expense_date', '<=', $request->end_date);
        }

        $expenses = $query->latest('expense_date')->latest('id')->paginate(15);
        $categories = TransactionCategory::where('business_id', $user->business_id)
            ->where('type', 'operasional')
            ->where('is_active', true)
            ->get();
        $totalExpenses = OperationalExpense::where('business_id', $user->business_id)->sum('amount');

        return view('owner.expenses.index', compact('expenses', 'categories', 'totalExpenses'));
    }

    public function create()
    {
        $user = auth()->user();
        $categories = TransactionCategory::where('business_id', $user->business_id)
            ->where('type', 'operasional')
            ->where('is_active', true)
            ->get();

        return view('owner.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'description' => 'nullable|string|max:1000',
        ], [
            'amount.required' => 'Nominal biaya operasional wajib diisi.',
            'amount.numeric' => 'Nominal biaya operasional harus berupa angka.',
            'amount.min' => 'Nominal biaya operasional minimal Rp 1.',
            'expense_date.required' => 'Tanggal pengeluaran biaya operasional wajib diisi.',
            'expense_date.date' => 'Format tanggal pengeluaran tidak valid.',
            'category_id.exists' => 'Kategori operasional yang dipilih tidak valid.',
        ]);

        $cleanedAmount = clean_number($request->amount);

        OperationalExpense::create([
            'business_id' => $user->business_id,
            'user_id' => $user->id,
            'amount' => $cleanedAmount,
            'expense_date' => $request->expense_date,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);

        return redirect()->route('owner.expenses.index')
            ->with('success', 'Biaya operasional sebesar Rp '.number_format($cleanedAmount, 0, ',', '.').' berhasil dicatat ke pembukuan.');
    }

    public function edit(OperationalExpense $expense)
    {
        if ($expense->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $user = auth()->user();
        $categories = TransactionCategory::where('business_id', $user->business_id)
            ->where('type', 'operasional')
            ->where('is_active', true)
            ->get();

        return view('owner.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, OperationalExpense $expense)
    {
        if ($expense->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'description' => 'nullable|string|max:1000',
        ], [
            'amount.required' => 'Nominal biaya operasional wajib diisi.',
            'amount.numeric' => 'Nominal biaya operasional harus berupa angka.',
            'amount.min' => 'Nominal biaya operasional minimal Rp 1.',
            'expense_date.required' => 'Tanggal pengeluaran biaya operasional wajib diisi.',
            'expense_date.date' => 'Format tanggal pengeluaran tidak valid.',
            'category_id.exists' => 'Kategori operasional yang dipilih tidak valid.',
        ]);

        $cleanedAmount = clean_number($request->amount);

        $expense->update([
            'amount' => $cleanedAmount,
            'expense_date' => $request->expense_date,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);

        return redirect()->route('owner.expenses.index')
            ->with('success', 'Data biaya operasional berhasil diperbarui.');
    }

    public function destroy(OperationalExpense $expense)
    {
        if ($expense->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $expense->delete();

        return redirect()->route('owner.expenses.index')
            ->with('success', 'Catatan biaya operasional berhasil dihapus dari pembukuan.');
    }
}
