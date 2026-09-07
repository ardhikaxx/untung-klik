<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Karyawan\DashboardController as KaryawanDashboardController;
use App\Http\Controllers\Karyawan\ReportController as KaryawanReportController;
use App\Http\Controllers\Karyawan\TransactionController as KaryawanTransactionController;
use App\Http\Controllers\Owner\CapitalController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\ChartController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\OperationalExpenseController;
use App\Http\Controllers\Owner\ReportController;
use App\Http\Controllers\Owner\TransactionController;
use App\Http\Controllers\Owner\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Forgot PIN routes
Route::get('/lupa-pin', [AuthController::class, 'showForgotPin'])->name('forgot-pin')->middleware('guest');
Route::post('/lupa-pin', [AuthController::class, 'forgotPin'])->name('forgot-pin.post')->middleware('guest');
Route::get('/reset-pin/{token}', [AuthController::class, 'showResetPin'])->name('reset-pin')->middleware('guest');
Route::post('/reset-pin', [AuthController::class, 'resetPin'])->name('reset-pin.post')->middleware('guest');

// Owner/Admin routes
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner,admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions (uang masuk & keluar)
    Route::resource('transactions', TransactionController::class)->except(['show']);
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Capital
    Route::resource('capital', CapitalController::class)->except(['show']);

    // Operational Expenses
    Route::resource('expenses', OperationalExpenseController::class)->except(['show']);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/custom', [ReportController::class, 'custom'])->name('reports.custom');

    // Charts
    Route::get('/charts', [ChartController::class, 'index'])->name('charts.index');
    Route::get('/charts/data', [ChartController::class, 'data'])->name('charts.data');

    // User Management
    Route::resource('users', UserController::class)->except(['show']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/pin', [ProfileController::class, 'changePin'])->name('profile.pin');

    // Export
    Route::get('/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
});

// Karyawan routes
Route::prefix('karyawan')->name('karyawan.')->middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/dashboard', [KaryawanDashboardController::class, 'index'])->name('dashboard');

    // Transactions (uang masuk only)
    Route::get('/transactions', [KaryawanTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [KaryawanTransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [KaryawanTransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [KaryawanTransactionController::class, 'show'])->name('transactions.show');

    // Reports
    Route::get('/reports', [KaryawanReportController::class, 'index'])->name('reports.index');
    Route::get('/export/pdf', [KaryawanReportController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/excel', [KaryawanReportController::class, 'exportExcel'])->name('export.excel');

    // Profile
    Route::get('/profile', [ProfileController::class, 'karyawanProfile'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'karyawanUpdate'])->name('profile.update');
    Route::put('/profile/pin', [ProfileController::class, 'karyawanChangePin'])->name('profile.pin');
});
