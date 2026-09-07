<?php

namespace App\Http\Controllers\Karyawan;

use App\Exports\KaryawanReportExport;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $date = $request->get('date', Carbon::now()->toDateString());

        $transactions = Transaction::where('business_id', $user->business_id)
            ->where('user_id', $user->id)
            ->whereDate('transaction_date', $date)
            ->with(['category', 'items.product'])
            ->latest()
            ->get();

        $totalAmount = $transactions->sum('amount');
        $totalCount = $transactions->count();

        $salesTransactions = $transactions->where('is_sale', true);
        $posSalesTotal = $salesTransactions->sum('amount');
        $posSalesCount = $salesTransactions->count();

        $manualTransactions = $transactions->where('is_sale', false);
        $manualCashTotal = $manualTransactions->sum('amount');
        $manualCashCount = $manualTransactions->count();

        $saleIds = $salesTransactions->pluck('id');
        $totalItemsSold = TransactionItem::whereIn('transaction_id', $saleIds)->sum('quantity');

        // Payment method breakdown for cashier reconciliation
        $paymentBreakdown = $transactions->groupBy('payment_method')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount'),
            ];
        });

        $cashTotal = $paymentBreakdown->get('Tunai')['total'] ?? ($paymentBreakdown->get('tunai')['total'] ?? 0);

        return view('karyawan.reports.index', compact(
            'transactions',
            'totalAmount',
            'totalCount',
            'posSalesTotal',
            'posSalesCount',
            'manualCashTotal',
            'manualCashCount',
            'totalItemsSold',
            'paymentBreakdown',
            'cashTotal',
            'date'
        ));
    }

    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $date = $request->get('date', Carbon::now()->toDateString());

        $transactions = Transaction::where('business_id', $user->business_id)
            ->where('user_id', $user->id)
            ->whereDate('transaction_date', $date)
            ->with(['category', 'items.product'])
            ->latest()
            ->get();

        $totalAmount = $transactions->sum('amount');
        $business = $user->business;

        $pdf = \PDF::loadView('karyawan.reports.pdf', compact('transactions', 'totalAmount', 'date', 'business', 'user'));

        return $pdf->download("laporan-penjualan-{$date}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $date = $request->get('date', Carbon::now()->toDateString());

        $transactions = Transaction::where('business_id', $user->business_id)
            ->where('user_id', $user->id)
            ->whereDate('transaction_date', $date)
            ->with(['category', 'items.product'])
            ->latest()
            ->get();

        $totalAmount = $transactions->sum('amount');
        $business = $user->business;

        return Excel::download(
            new KaryawanReportExport($transactions, $totalAmount, $date, $business, $user),
            "laporan-penjualan-{$date}.xlsx"
        );
    }
}
