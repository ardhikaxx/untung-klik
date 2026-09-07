<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Transaction::where('business_id', $user->business_id)
            ->with('user', 'category');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('source', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(15);
        $categories = TransactionCategory::where('business_id', $user->business_id)
            ->where('is_active', true)->get();

        return view('owner.transactions.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        $user = auth()->user();
        $categories = TransactionCategory::where('business_id', $user->business_id)
            ->where('is_active', true)
            ->whereIn('type', ['masuk', 'keluar'])
            ->get();

        return view('owner.transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'type' => 'required|in:masuk,keluar',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'payment_method' => 'nullable|string|max:100',
        ]);

        Transaction::create([
            'business_id' => $user->business_id,
            'user_id' => $user->id,
            'type' => $request->type,
            'amount' => clean_number($request->amount),
            'transaction_date' => $request->transaction_date,
            'category_id' => $request->category_id,
            'source' => $request->source,
            'description' => $request->description,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->route('owner.transactions.index')
            ->with('success', 'Transaksi berhasil dicatat.');
    }

    public function show(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
        $transaction->load('user', 'category');

        return view('owner.transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
        $user = auth()->user();
        $categories = TransactionCategory::where('business_id', $user->business_id)
            ->where('is_active', true)
            ->whereIn('type', ['masuk', 'keluar'])
            ->get();

        return view('owner.transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
        $request->validate([
            'type' => 'required|in:masuk,keluar',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'payment_method' => 'nullable|string|max:100',
        ]);

        $transaction->update([
            'type' => $request->type,
            'amount' => clean_number($request->amount),
            'transaction_date' => $request->transaction_date,
            'category_id' => $request->category_id,
            'source' => $request->source,
            'description' => $request->description,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->route('owner.transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
        $transaction->delete();

        return redirect()->route('owner.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    private function authorizeTransaction(Transaction $transaction): void
    {
        if ($transaction->business_id !== auth()->user()->business_id) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }
    }
}
