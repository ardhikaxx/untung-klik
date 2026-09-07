<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::now()->toDateString();

        $todaySales = Transaction::where('business_id', $user->business_id)
            ->where('user_id', $user->id)
            ->where('type', 'masuk')
            ->whereDate('transaction_date', $today)
            ->sum('amount');

        $todayCount = Transaction::where('business_id', $user->business_id)
            ->where('user_id', $user->id)
            ->whereDate('transaction_date', $today)
            ->count();

        $totalMyTransactions = Transaction::where('business_id', $user->business_id)
            ->where('user_id', $user->id)
            ->count();

        $recentTransactions = Transaction::where('business_id', $user->business_id)
            ->where('user_id', $user->id)
            ->with('category')
            ->latest('transaction_date')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('karyawan.dashboard', compact(
            'todaySales', 'todayCount', 'totalMyTransactions', 'recentTransactions'
        ));
    }
}
