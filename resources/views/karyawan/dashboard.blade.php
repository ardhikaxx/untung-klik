@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<!-- Page Header & Action CTA -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Dashboard Kasir</h2>
        <p class="uk-page-subtitle">Pusat operasional kasir harian, pencatatan penjualan, dan ringkasan shift Anda</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('karyawan.transactions.create') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
            <i class="fas fa-arrow-down me-1.5"></i>Catat Kas Masuk
        </a>
        <a href="{{ route('karyawan.sales.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-4 shadow-xs fw-bold">
            <i class="fas fa-cash-register me-1.5"></i>Buka Kasir Penjualan
        </a>
    </div>
</div>

<!-- Stat Cards: Karyawan Shift Summary (3 Grid Columns) -->
<div class="row g-3 mb-4">
    <!-- 1. Penjualan Hari Ini (ACCENT SOLID #7DE2D1) -->
    <div class="col-12 col-md-4">
        <div class="uk-stat-card-accent h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.06em; color: var(--uk-dark);">
                        Penjualan Hari Ini
                    </span>
                    <div style="width: 34px; height: 34px; border-radius: 8px; background-color: rgba(19, 21, 21, 0.1); color: var(--uk-dark); display: flex; align-items: center; justify-content: center; font-size: 0.95rem;">
                        <i class="fas fa-cash-register"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="font-size: clamp(1.35rem, 1.8vw, 1.65rem); letter-spacing: -0.03em; color: var(--uk-dark);">
                    {{ format_rupiah($todaySales) }}
                </h3>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid rgba(19, 21, 21, 0.12); font-size: 0.78rem;">
                <span class="fw-semibold" style="color: var(--uk-dark);"><i class="fas fa-calendar-day me-1 opacity-75"></i>Shift Aktif Hari Ini</span>
                <a href="{{ route('karyawan.sales.create') }}" class="fw-bold" style="color: var(--uk-dark);">
                    Buka Kasir &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Transaksi Hari Ini (CLEAN LIGHT CARD) -->
    <div class="col-12 col-md-4">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Transaksi Hari Ini
                    </span>
                    <div style="width: 34px; height: 34px; border-radius: 8px; background-color: rgba(51, 153, 137, 0.1); color: var(--uk-primary); display: flex; align-items: center; justify-content: center; font-size: 0.95rem;">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mb-0">
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: clamp(1.35rem, 1.8vw, 1.65rem); letter-spacing: -0.03em;">
                        {{ number_format($todayCount) }}
                    </h3>
                    <span class="text-muted small">Nota dicetak</span>
                </div>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <span class="text-muted"><i class="fas fa-clock me-1 text-primary"></i>Struk kasir hari ini</span>
                <a href="{{ route('karyawan.sales.index') }}" class="text-primary fw-semibold">
                    Daftar Nota &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Total Transaksi Saya (DARK HERO CARD #131515) -->
    <div class="col-12 col-md-4">
        <div class="uk-stat-card-dark h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.06em; color: rgba(255, 250, 251, 0.7);">
                        Total Transaksi Saya
                    </span>
                    <div style="width: 34px; height: 34px; border-radius: 8px; background-color: rgba(125, 226, 209, 0.2); color: var(--uk-accent); display: flex; align-items: center; justify-content: center; font-size: 0.95rem;">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mb-0">
                    <h3 class="fw-bold mb-0 text-white" style="font-size: clamp(1.35rem, 1.8vw, 1.65rem); letter-spacing: -0.03em;">
                        {{ number_format($totalMyTransactions) }}
                    </h3>
                    <span class="small" style="color: var(--uk-accent);">Aktivitas</span>
                </div>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid rgba(255, 250, 251, 0.12); font-size: 0.78rem;">
                <span class="text-white-50"><i class="fas fa-shield-alt me-1"></i>Akumulasi catatan Anda</span>
                <a href="{{ route('karyawan.reports.index') }}" style="color: var(--uk-accent); font-weight: 600;">
                    Riwayat Shift &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Warning Peringatan Stok Toko Menipis / Habis -->
@if($lowStockProducts->isNotEmpty())
<div class="uk-card p-3 p-md-4 mb-4 border-warning-subtle" style="background-color: rgba(125, 226, 209, 0.12); border-left: 4px solid var(--uk-accent) !important;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-triangle-exclamation text-warning fs-5"></i>
            <h6 class="fw-bold text-dark mb-0">Perhatian: Stok Produk Menipis / Habis</h6>
        </div>
        <span class="text-muted small">Informasikan kepada pemilik toko untuk restock</span>
    </div>
    <p class="small text-muted mb-3">Produk berikut telah mencapai batas minimum atau stok telah kosong:</p>
    <div class="d-flex flex-wrap gap-2">
        @foreach($lowStockProducts as $low)
            <span class="badge {{ $low->stock <= 0 ? 'bg-danger text-white' : 'bg-warning text-dark' }} py-1.5 px-3 rounded-pill" style="font-size: 0.75rem;">
                <i class="fas {{ $low->stock <= 0 ? 'fa-circle-xmark' : 'fa-bell' }} me-1"></i>
                {{ $low->name }} (Sisa: {{ $low->stock }} {{ $low->unit }})
            </span>
        @endforeach
    </div>
</div>
@endif

<!-- Riwayat Transaksi Shift Terakhir -->
<div class="uk-card p-0 overflow-hidden">
    <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-clock-rotate-left text-primary"></i>
            <h6 class="fw-bold text-dark mb-0">Transaksi Terakhir Saya</h6>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('karyawan.sales.index') }}" class="btn btn-xs btn-uk-outline rounded-pill px-3">
                <i class="fas fa-receipt me-1"></i>Riwayat Kasir
            </a>
            <a href="{{ route('karyawan.transactions.index') }}" class="btn btn-xs btn-uk-secondary rounded-pill px-3">
                <i class="fas fa-arrow-down me-1"></i>Kas Masuk
            </a>
        </div>
    </div>

    <div class="p-0">
        @if($recentTransactions->isEmpty())
            <div class="uk-empty-state py-5">
                <div class="uk-empty-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <div class="uk-empty-title">Belum Ada Transaksi Shift Ini</div>
                <p class="uk-empty-desc mb-3">Buka mesin kasir untuk mulai memproses transaksi penjualan pelanggan.</p>
                <a href="{{ route('karyawan.sales.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3">
                    <i class="fas fa-plus me-1"></i>Buka Kasir Sekarang
                </a>
            </div>
        @else
            <div class="table-responsive mb-0">
                <table class="table uk-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Waktu</th>
                            <th>Tipe & Produk</th>
                            <th>Keterangan</th>
                            <th class="text-end">Nominal</th>
                            <th class="pe-4 text-center">Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $transaction)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold text-dark" style="font-size: 0.82rem;">
                                    {{ $transaction->transaction_date->format('d/m/Y') }}
                                </div>
                                <div class="text-muted" style="font-size: 0.72rem;">
                                    {{ $transaction->created_at ? $transaction->created_at->format('H:i') : '' }}
                                </div>
                            </td>
                            <td>
                                @if($transaction->is_sale)
                                    <span class="uk-badge-success mb-1 d-inline-flex align-items-center gap-1">
                                        <i class="fas fa-shopping-bag" style="font-size: 0.65rem;"></i>
                                        Penjualan Kasir
                                    </span>
                                    @if($transaction->items->isNotEmpty())
                                    <div class="small text-muted" style="font-size: 0.75rem;">
                                        {{ $transaction->items->pluck('product_name')->take(2)->join(', ') }}
                                        @if($transaction->items->count() > 2)
                                            <span class="badge bg-light text-secondary border">+{{ $transaction->items->count() - 2 }} lainnya</span>
                                        @endif
                                    </div>
                                    @endif
                                @else
                                    <span class="uk-badge-info mb-1 d-inline-flex align-items-center gap-1">
                                        <i class="fas fa-arrow-down" style="font-size: 0.65rem;"></i>
                                        {{ $transaction->category->name ?? 'Kas Masuk' }}
                                    </span>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $transaction->source ?? '-' }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="text-muted text-truncate" style="max-width: 220px; font-size: 0.8rem;">
                                    {{ $transaction->description ?: '-' }}
                                </div>
                            </td>
                            <td class="text-end fw-bold text-success" style="font-size: 0.88rem;">
                                + {{ format_rupiah($transaction->amount) }}
                            </td>
                            <td class="pe-4 text-center">
                                @if($transaction->is_sale)
                                    <a href="{{ route('karyawan.sales.show', $transaction) }}" class="btn btn-xs btn-uk-outline rounded-pill px-2.5" title="Lihat & Cetak Nota">
                                        <i class="fas fa-print me-1"></i>Nota
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
