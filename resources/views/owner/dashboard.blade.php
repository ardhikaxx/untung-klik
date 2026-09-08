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
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-semibold">Total Uang Masuk</span>
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; flex-shrink: 0;">
                            <i class="fas fa-arrow-down text-success"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1 text-success text-nowrap" style="font-size: clamp(1.2rem, 1.4vw, 1.45rem); letter-spacing: -0.02em;">
                        {{ format_rupiah($totalIncome) }}
                    </h4>
                </div>
                <div class="text-muted small pt-2 border-top mt-2 d-flex align-items-center justify-content-between" style="font-size: 0.78rem;">
                    <span><i class="fas fa-info-circle me-1 opacity-75"></i>Kas masuk & penjualan toko</span>
                    <a href="{{ route('owner.transactions.index') }}" class="text-success text-decoration-none fw-semibold">Detail &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Uang Keluar -->
    <div class="col-12 col-md-4">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-semibold">Total Uang Keluar</span>
                        <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; flex-shrink: 0;">
                            <i class="fas fa-arrow-up text-danger"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1 text-danger text-nowrap" style="font-size: clamp(1.2rem, 1.4vw, 1.45rem); letter-spacing: -0.02em;">
                        {{ format_rupiah($totalExpense) }}
                    </h4>
                </div>
                <div class="text-muted small pt-2 border-top mt-2 d-flex align-items-center justify-content-between" style="font-size: 0.78rem;">
                    <span><i class="fas fa-info-circle me-1 opacity-75"></i>Pengeluaran kas tercatat</span>
                    <a href="{{ route('owner.transactions.index') }}" class="text-danger text-decoration-none fw-semibold">Detail &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Laba Bersih Usaha -->
    <div class="col-12 col-md-4">
        <div class="card border shadow-sm h-100 border-start border-4 {{ $netProfit >= 0 ? 'border-success' : 'border-danger' }}">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-semibold">Laba Bersih Usaha</span>
                        <div class="rounded-circle {{ $netProfit >= 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; flex-shrink: 0;">
                            <i class="fas fa-chart-line {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 flex-wrap mb-1">
                        <h4 class="fw-bold mb-0 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} text-nowrap" style="font-size: clamp(1.2rem, 1.4vw, 1.45rem); letter-spacing: -0.02em;">
                            {{ format_rupiah($netProfit) }}
                        </h4>
                        @if($netProfit >= 0)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.7rem;">
                                <i class="fas fa-arrow-trend-up me-1"></i>Surplus
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="font-size: 0.7rem;">
                                <i class="fas fa-arrow-trend-down me-1"></i>Defisit
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-muted small pt-2 border-top mt-2 d-flex align-items-center justify-content-between" style="font-size: 0.78rem;">
                    <span><i class="fas fa-calculator me-1 opacity-75"></i>Masuk - Keluar - Beban</span>
                    <a href="{{ route('owner.reports.index') }}" class="text-dark text-decoration-none fw-semibold">Laporan &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 2: 2 Metrik Struktur Modal & Beban Operasional -->
    <!-- Modal Usaha -->
    <div class="col-12 col-md-6">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-semibold">Modal Usaha (Capital)</span>
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; flex-shrink: 0;">
                            <i class="fas fa-coins text-primary"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1 text-primary text-nowrap" style="font-size: clamp(1.2rem, 1.4vw, 1.45rem); letter-spacing: -0.02em;">
                        {{ format_rupiah($totalCapital) }}
                    </h4>
                </div>
                <div class="text-muted small pt-2 border-top mt-2 d-flex align-items-center justify-content-between" style="font-size: 0.78rem;">
                    <span><i class="fas fa-shield-alt me-1 opacity-75"></i>Total akumulasi modal yang disetor ke usaha</span>
                    <a href="{{ route('owner.capital.index') }}" class="text-primary text-decoration-none fw-semibold">Kelola Modal &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Beban Operasional -->
    <div class="col-12 col-md-6">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-semibold">Beban Operasional Toko</span>
                        <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; flex-shrink: 0;">
                            <i class="fas fa-receipt text-warning"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1 text-warning text-nowrap" style="font-size: clamp(1.2rem, 1.4vw, 1.45rem); letter-spacing: -0.02em;">
                        {{ format_rupiah($totalOperational) }}
                    </h4>
                </div>
                <div class="text-muted small pt-2 border-top mt-2 d-flex align-items-center justify-content-between" style="font-size: 0.78rem;">
                    <span><i class="fas fa-building me-1 opacity-75"></i>Biaya listrik, sewa, gaji, & operasional rutin</span>
                    <a href="{{ route('owner.expenses.index') }}" class="text-warning text-decoration-none fw-semibold">Kelola Beban &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2: RINGKASAN PRODUK, STOK & PENJUALAN -->
<h6 class="fw-bold text-dark mb-3">
    <i class="fas fa-boxes text-primary me-2"></i>Produk, Penjualan & Stok Toko
</h6>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-success-subtle text-success rounded-3">
                    <i class="fas fa-cash-register fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Penjualan Hari Ini</div>
                    <div class="fs-5 fw-bold text-success">{{ format_rupiah($todaySalesTotal) }}</div>
                    <div class="small text-muted">{{ $todaySalesCount }} transaksi hari ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-primary-subtle text-primary rounded-3">
                    <i class="fas fa-box fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Total Produk</div>
                    <div class="fs-5 fw-bold text-dark">{{ number_format($totalProducts) }}</div>
                    <div class="small text-muted">Katalog aktif</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-warning-subtle text-warning rounded-3">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Stok Menipis</div>
                    <div class="fs-5 fw-bold text-warning">{{ number_format($lowStockCount) }}</div>
                    <div class="small text-muted">&le; batas minimum</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-danger-subtle text-danger rounded-3">
                    <i class="fas fa-times-circle fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Stok Habis</div>
                    <div class="fs-5 fw-bold text-danger">{{ number_format($outOfStockCount) }}</div>
                    <div class="small text-muted">Segera belanja restock</div>
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
