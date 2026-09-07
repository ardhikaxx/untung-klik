@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #dcfce7;">
                            <i class="fas fa-arrow-down" style="color: #16a34a; font-size: 1.25rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1 small">Penjualan Hari Ini</p>
                        <h4 class="mb-0 fw-bold">Rp {{ number_format($todaySales, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #dbeafe;">
                            <i class="fas fa-receipt" style="color: #2563eb; font-size: 1.25rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1 small">Jumlah Transaksi Hari Ini</p>
                        <h4 class="mb-0 fw-bold">{{ $todayCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #fef3c7;">
                            <i class="fas fa-chart-line" style="color: #d97706; font-size: 1.25rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1 small">Total Transaksi Saya</p>
                        <h4 class="mb-0 fw-bold">{{ $totalMyTransactions }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <a href="{{ route('karyawan.transactions.create') }}" class="btn btn-success btn-lg px-4">
            <i class="fas fa-plus me-2"></i>Catat Penjualan Baru
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-semibold">Transaksi Terakhir</h6>
        <a href="{{ route('karyawan.transactions.index') }}" class="text-decoration-none small">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="font-size: 0.8125rem;">Tanggal</th>
                        <th style="font-size: 0.8125rem;">Kategori</th>
                        <th style="font-size: 0.8125rem;">Sumber</th>
                        <th class="text-end pe-3" style="font-size: 0.8125rem;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                    <tr>
                        <td class="ps-3">{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge" style="background-color: #dcfce7; color: #16a34a;">{{ $transaction->category->name ?? '-' }}</span>
                        </td>
                        <td>{{ $transaction->source ?? '-' }}</td>
                        <td class="text-end pe-3 fw-semibold" style="color: #16a34a;">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size: 2rem; color: #d1d5db;"></i>
                            Belum ada transaksi hari ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
