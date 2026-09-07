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
                            <i class="fas fa-cash-register" style="color: #16a34a; font-size: 1.25rem;"></i>
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
                        <p class="text-muted mb-1 small">Transaksi Hari Ini</p>
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
                            <i class="fas fa-history" style="color: #d97706; font-size: 1.25rem;"></i>
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
    <div class="col-12 d-flex flex-wrap gap-2">
        <a href="{{ route('karyawan.sales.create') }}" class="btn btn-primary btn-lg px-4 shadow-sm">
            <i class="fas fa-cart-plus me-2"></i>Catat Penjualan Produk
        </a>
        <a href="{{ route('karyawan.transactions.create') }}" class="btn btn-outline-secondary btn-lg px-4">
            <i class="fas fa-plus me-2"></i>Catat Kas Masuk Manual
        </a>
    </div>
</div>

@if($lowStockProducts->isNotEmpty())
<div class="alert alert-warning border-0 shadow-sm mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="fas fa-exclamation-triangle text-warning me-2 fs-5"></i>
        <span class="fw-semibold">Perhatian: Stok Produk Menipis / Habis</span>
    </div>
    <p class="small text-muted mb-2">Segera beritahu pemilik usaha jika stok barang berikut ini perlu diisi ulang:</p>
    <div class="d-flex flex-wrap gap-2">
        @foreach($lowStockProducts as $low)
            <span class="badge {{ $low->stock <= 0 ? 'bg-danger' : 'bg-warning text-dark' }} py-2 px-3">
                {{ $low->name }} (Sisa: {{ $low->stock }} {{ $low->unit }})
            </span>
        @endforeach
    </div>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
        <h6 class="mb-0 fw-semibold">Transaksi Terakhir Saya</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('karyawan.sales.index') }}" class="btn btn-sm btn-outline-primary">Riwayat Penjualan</a>
            <a href="{{ route('karyawan.transactions.index') }}" class="btn btn-sm btn-outline-secondary">Buku Kas</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="font-size: 0.8125rem;">Tanggal</th>
                        <th style="font-size: 0.8125rem;">Tipe & Item</th>
                        <th style="font-size: 0.8125rem;">Keterangan</th>
                        <th class="text-end pe-3" style="font-size: 0.8125rem;">Jumlah</th>
                        <th class="text-center" style="font-size: 0.8125rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                    <tr>
                        <td class="ps-3">
                            <span class="fw-semibold">{{ $transaction->transaction_date->format('d/m/Y') }}</span>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $transaction->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            @if($transaction->is_sale)
                                <span class="badge bg-success mb-1"><i class="fas fa-shopping-bag me-1"></i>Penjualan Produk</span>
                                @if($transaction->items->isNotEmpty())
                                <div class="small text-muted">
                                    {{ $transaction->items->pluck('product_name')->take(2)->join(', ') }}
                                    @if($transaction->items->count() > 2)
                                        <span class="badge bg-light text-secondary">+{{ $transaction->items->count() - 2 }} item</span>
                                    @endif
                                </div>
                                @endif
                            @else
                                <span class="badge bg-info text-dark mb-1">{{ $transaction->category->name ?? 'Kas Masuk' }}</span>
                                <div class="small text-muted">{{ $transaction->source ?? '-' }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="small text-truncate" style="max-width: 250px;">
                                {{ $transaction->description ?: '-' }}
                            </div>
                        </td>
                        <td class="text-end pe-3 fw-bold" style="color: #16a34a;">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($transaction->is_sale)
                                <a href="{{ route('karyawan.sales.show', $transaction) }}" class="btn btn-sm btn-outline-primary" title="Lihat Nota">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
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
