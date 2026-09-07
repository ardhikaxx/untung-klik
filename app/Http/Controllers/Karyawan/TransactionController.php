<?php

namespace App\Http\Controllers\Karyawan;

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
            ->where('user_id', $user->id)
            ->where('is_sale', false)
            ->with('category');

        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(15);

        return view('karyawan.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $user = auth()->user();
        $categories = TransactionCategory::where('business_id', $user->business_id)
            ->where('type', 'masuk')
            ->where('is_active', true)
            ->get();

        return view('karyawan.transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'payment_method' => 'nullable|string|max:100',
        ], [
            'amount.required' => 'Nominal kas masuk wajib diisi.',
            'amount.numeric' => 'Nominal kas masuk harus berupa angka.',
            'amount.min' => 'Nominal kas masuk minimal Rp 1.',
            'transaction_date.required' => 'Tanggal kas masuk wajib diisi.',
            'transaction_date.date' => 'Format tanggal tidak valid.',
            'category_id.exists' => 'Kategori kas yang dipilih tidak valid atau tidak ditemukan.',
        ]);

        $cleanedAmount = clean_number($request->amount);

        Transaction::create([
            'business_id' => $user->business_id,
            'user_id' => $user->id,
            'type' => 'masuk',
            'is_sale' => false,
            'amount' => $cleanedAmount,
            'transaction_date' => $request->transaction_date,
            'category_id' => $request->category_id,
            'source' => $request->source,
            'description' => $request->description,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->route('karyawan.transactions.index')
            ->with('success', 'Kas masuk berhasil dicatat.');
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id() || $transaction->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $transaction->load('category');

        return view('karyawan.transactions.show', compact('transaction'));
    }
}
