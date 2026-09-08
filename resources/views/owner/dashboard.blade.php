@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Dashboard Usaha</h4>
        <p class="text-muted mb-0">Ringkasan keuangan, penjualan produk, dan peringatan ketersediaan stok</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('owner.dashboard', ['period' => 'today']) }}"
           class="btn btn-sm {{ $period === 'today' ? 'btn-success' : 'btn-outline-secondary' }}">
            Hari Ini
        </a>
        <a href="{{ route('owner.dashboard', ['period' => 'week']) }}"
           class="btn btn-sm {{ $period === 'week' ? 'btn-success' : 'btn-outline-secondary' }}">
            Minggu Ini
        </a>
        <a href="{{ route('owner.dashboard', ['period' => 'month']) }}"
           class="btn btn-sm {{ $period === 'month' ? 'btn-success' : 'btn-outline-secondary' }}">
            Bulan Ini
        </a>
        <a href="{{ route('owner.dashboard', ['period' => 'year']) }}"
           class="btn btn-sm {{ $period === 'year' ? 'btn-success' : 'btn-outline-secondary' }}">
            Tahun Ini
        </a>
        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#customDateRange">
            <i class="fas fa-calendar-alt me-1"></i>Custom
        </button>
        <a href="{{ route('owner.sales.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-cash-register me-1"></i>+ Catat Penjualan
        </a>
    </div>
</div>

<div class="collapse mb-4" id="customDateRange">
    <div class="card border shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('owner.dashboard') }}" class="row g-3 align-items-end">
                <input type="hidden" name="period" value="custom">
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-semibold small">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-semibold small">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SECTION 1: KEUANGAN UTAMA -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold text-dark mb-0">
        <i class="fas fa-wallet text-success me-2"></i>Kondisi Keuangan Usaha
    </h6>
    <span class="text-muted small">Ringkasan arus kas, modal & laba periode ini</span>
</div>
<div class="row g-3 mb-4">
    <!-- Baris 1: 3 Metrik Utama Kas & Laba (Formula: Masuk - Keluar = Laba) -->
    <!-- Total Uang Masuk -->
    <div class="col-12 col-md-4">
        <div class="card modern-stat-card accent-success h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stat-label-text">Total Uang Masuk</span>
                        <div class="stat-icon-pod pod-green">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                    </div>
                    <h4 class="stat-value-text text-success">
                        {{ format_rupiah($totalIncome) }}
                    </h4>
                </div>
                <div class="stat-card-footer">
                    <span><i class="fas fa-circle-check text-success me-1 opacity-75"></i>Kas masuk & penjualan</span>
                    <a href="{{ route('owner.transactions.index') }}" class="text-success">Detail &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Uang Keluar -->
    <div class="col-12 col-md-4">
        <div class="card modern-stat-card accent-danger h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stat-label-text">Total Uang Keluar</span>
                        <div class="stat-icon-pod pod-red">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                    <h4 class="stat-value-text text-danger">
                        {{ format_rupiah($totalExpense) }}
                    </h4>
                </div>
                <div class="stat-card-footer">
                    <span><i class="fas fa-circle-minus text-danger me-1 opacity-75"></i>Pengeluaran kas tercatat</span>
                    <a href="{{ route('owner.transactions.index') }}" class="text-danger">Detail &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Laba Bersih Usaha -->
    <div class="col-12 col-md-4">
        <div class="card modern-stat-card {{ $netProfit >= 0 ? 'accent-success' : 'accent-danger' }} h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stat-label-text">Laba Bersih Usaha</span>
                        <div class="stat-icon-pod {{ $netProfit >= 0 ? 'pod-green' : 'pod-red' }}">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 flex-wrap mb-1">
                        <h4 class="stat-value-text {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ format_rupiah($netProfit) }}
                        </h4>
                        @if($netProfit >= 0)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                                <i class="fas fa-arrow-trend-up me-1"></i>Surplus
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                                <i class="fas fa-arrow-trend-down me-1"></i>Defisit
                            </span>
                        @endif
                    </div>
                </div>
                <div class="stat-card-footer">
                    <span><i class="fas fa-calculator me-1 opacity-75"></i>Masuk - Keluar - Beban</span>
                    <a href="{{ route('owner.reports.index') }}" class="text-dark">Laporan &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 2: 2 Metrik Struktur Modal & Beban Operasional -->
    <!-- Modal Usaha -->
    <div class="col-12 col-md-6">
        <div class="card modern-stat-card accent-primary h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stat-label-text">Modal Usaha (Capital)</span>
                        <div class="stat-icon-pod pod-blue">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                    <h4 class="stat-value-text text-primary">
                        {{ format_rupiah($totalCapital) }}
                    </h4>
                </div>
                <div class="stat-card-footer">
                    <span><i class="fas fa-shield-alt text-primary me-1 opacity-75"></i>Akumulasi modal yang disetor ke usaha</span>
                    <a href="{{ route('owner.capital.index') }}" class="text-primary">Kelola Modal &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Beban Operasional -->
    <div class="col-12 col-md-6">
        <div class="card modern-stat-card accent-warning h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stat-label-text">Beban Operasional Toko</span>
                        <div class="stat-icon-pod pod-amber">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                    <h4 class="stat-value-text text-warning">
                        {{ format_rupiah($totalOperational) }}
                    </h4>
                </div>
                <div class="stat-card-footer">
                    <span><i class="fas fa-building text-warning me-1 opacity-75"></i>Biaya listrik, sewa, gaji, & operasional rutin</span>
                    <a href="{{ route('owner.expenses.index') }}" class="text-warning">Kelola Beban &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2: RINGKASAN PRODUK, STOK & PENJUALAN -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold text-dark mb-0">
        <i class="fas fa-boxes-stacked text-primary me-2"></i>Produk, Penjualan & Stok Toko
    </h6>
    <span class="text-muted small">Status inventaris dan pergerakan kasir</span>
</div>
<div class="row g-3 mb-4">
    <!-- Penjualan Hari Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card modern-stat-card accent-purple h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stat-label-text">Penjualan Hari Ini</span>
                        <div class="stat-icon-pod pod-purple">
                            <i class="fas fa-cash-register"></i>
                        </div>
                    </div>
                    <h4 class="stat-value-text text-dark">
                        {{ format_rupiah($todaySalesTotal) }}
                    </h4>
                </div>
                <div class="stat-card-footer">
                    <span><i class="fas fa-receipt text-purple me-1 opacity-75"></i>{{ $todaySalesCount }} transaksi</span>
                    <a href="{{ route('owner.sales.index') }}" class="text-primary">Kasir &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Produk Aktif -->
    <div class="col-sm-6 col-lg-3">
        <div class="card modern-stat-card accent-primary h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stat-label-text">Total Produk</span>
                        <div class="stat-icon-pod pod-blue">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                    <h4 class="stat-value-text text-dark">
                        {{ number_format($totalProducts) }} <span class="fs-6 fw-normal text-muted">Item</span>
                    </h4>
                </div>
                <div class="stat-card-footer">
                    <span><i class="fas fa-check-circle text-success me-1 opacity-75"></i>Katalog produk aktif</span>
                    <a href="{{ route('owner.products.index') }}" class="text-primary">Katalog &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok Menipis -->
    <div class="col-sm-6 col-lg-3">
        <div class="card modern-stat-card accent-warning h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stat-label-text">Stok Menipis</span>
                        <div class="stat-icon-pod pod-amber">
                            <i class="fas fa-triangle-exclamation"></i>
                        </div>
                    </div>
                    <h4 class="stat-value-text text-warning">
                        {{ number_format($lowStockCount) }} <span class="fs-6 fw-normal text-muted">Produk</span>
                    </h4>
                </div>
                <div class="stat-card-footer">
                    <span><i class="fas fa-info-circle text-warning me-1 opacity-75"></i>&le; batas minimum stok</span>
                    <a href="{{ route('owner.stock.index') }}" class="text-warning">Cek Stok &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok Habis -->
    <div class="col-sm-6 col-lg-3">
        <div class="card modern-stat-card accent-danger h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stat-label-text">Stok Habis</span>
                        <div class="stat-icon-pod pod-red">
                            <i class="fas fa-circle-xmark"></i>
                        </div>
                    </div>
                    <h4 class="stat-value-text text-danger">
                        {{ number_format($outOfStockCount) }} <span class="fs-6 fw-normal text-muted">Produk</span>
                    </h4>
                </div>
                <div class="stat-card-footer">
                    <span><i class="fas fa-bell text-danger me-1 opacity-75"></i>Perlu restock segera</span>
                    <a href="{{ route('owner.stock.adjust') }}" class="text-danger">+ Isi Stok &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 3: PERHATIAN STOK & TRANSAKSI TERBARU -->
<div class="row g-4">
    <!-- Produk yang Perlu Diperhatikan -->
    <div class="col-lg-5">
        <div class="card border shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-bell text-warning me-2"></i>Produk Perlu Perhatian
                </h6>
                <a href="{{ route('owner.stock.index') }}" class="btn btn-sm btn-outline-warning">
                    Semua Stok
                </a>
            </div>
            <div class="card-body p-0">
                @if($attentionProducts->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success opacity-50 mb-2"></i>
                        <h6 class="text-muted fw-bold">Semua Stok Aman</h6>
                        <p class="text-muted small mb-0">Tidak ada produk yang stoknya menipis atau habis.</p>
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($attentionProducts as $prod)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <div class="fw-bold text-dark">{{ $prod->name }}</div>
                                    <div class="text-muted small">
                                        Harga: {{ format_rupiah($prod->selling_price) }} &bull; Min: {{ $prod->min_stock }} {{ $prod->unit }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    @if($prod->isOutOfStock())
                                        <span class="badge bg-danger text-white mb-1 d-inline-block">Stok Habis</span>
                                    @elseif($prod->isLowStock())
                                        <span class="badge bg-warning text-dark mb-1 d-inline-block">Stok Menipis</span>
                                    @else
                                        <span class="badge bg-success text-white mb-1 d-inline-block">Stok Aman</span>
                                    @endif
                                    <div class="fw-bold fs-6 {{ $prod->stock <= 0 ? 'text-danger' : 'text-warning' }}">
                                        {{ number_format($prod->stock) }} {{ $prod->unit }}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="p-3 bg-light border-top text-center">
                        <a href="{{ route('owner.stock.adjust') }}" class="btn btn-sm btn-warning w-100">
                            <i class="fas fa-sliders-h me-1"></i>Lakukan Penyesuaian / Restock
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="col-lg-7">
        <div class="card border shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-list text-muted me-2"></i>Transaksi Keuangan Terbaru
                </h6>
                <a href="{{ route('owner.transactions.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentTransactions->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted opacity-50 mb-3"></i>
                        <h6 class="text-muted fw-bold">Belum Ada Transaksi</h6>
                        <p class="text-muted small mb-3">Mulai catat transaksi penjualan atau uang masuk usaha Anda.</p>
                        <a href="{{ route('owner.sales.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>Catat Penjualan
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th class="ps-3">Tanggal</th>
                                    <th>Tipe</th>
                                    <th>Keterangan / Sumber</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="pe-3">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td class="ps-3 small text-muted">
                                            {{ $transaction->transaction_date->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            @if($transaction->type === 'masuk')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                    {{ $transaction->is_sale ? 'Penjualan' : 'Masuk' }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Keluar</span>
                                            @endif
                                        </td>
                                        <td class="small">
                                            <div class="fw-semibold text-dark">{{ $transaction->source ?: ($transaction->description ?: '-') }}</div>
                                        </td>
                                        <td class="text-end fw-bold {{ $transaction->type === 'masuk' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->type === 'masuk' ? '+' : '-' }} {{ format_rupiah($transaction->amount) }}
                                        </td>
                                        <td class="pe-3 text-muted small">
                                            {{ $transaction->user ? $transaction->user->name : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
