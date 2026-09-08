@extends('layouts.app')

@section('title', 'Dashboard Usaha')

@section('content')
<!-- Page Header & Period Filters -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Dashboard Usaha</h2>
        <p class="uk-page-subtitle">Ringkasan kondisi keuangan, mutasi kas, dan ketersediaan stok produk Anda</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <div class="btn-group p-1 bg-white border rounded-pill shadow-xs" role="group">
            <a href="{{ route('owner.dashboard', ['period' => 'today']) }}"
               class="btn btn-sm rounded-pill px-3 fw-semibold {{ $period === 'today' ? 'btn-uk-primary' : 'text-muted border-0 bg-transparent' }}">
                Hari Ini
            </a>
            <a href="{{ route('owner.dashboard', ['period' => 'week']) }}"
               class="btn btn-sm rounded-pill px-3 fw-semibold {{ $period === 'week' ? 'btn-uk-primary' : 'text-muted border-0 bg-transparent' }}">
                Minggu Ini
            </a>
            <a href="{{ route('owner.dashboard', ['period' => 'month']) }}"
               class="btn btn-sm rounded-pill px-3 fw-semibold {{ $period === 'month' ? 'btn-uk-primary' : 'text-muted border-0 bg-transparent' }}">
                Bulan Ini
            </a>
            <a href="{{ route('owner.dashboard', ['period' => 'year']) }}"
               class="btn btn-sm rounded-pill px-3 fw-semibold {{ $period === 'year' ? 'btn-uk-primary' : 'text-muted border-0 bg-transparent' }}">
                Tahun Ini
            </a>
        </div>
        <button class="btn btn-sm btn-uk-outline rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#customDateRange">
            <i class="fas fa-calendar-alt me-1.5"></i>Custom
        </button>
        <a href="{{ route('owner.sales.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 shadow-xs">
            <i class="fas fa-cash-register me-1.5"></i>Catat Penjualan
        </a>
    </div>
</div>

<!-- Custom Date Filter Dropdown Collapse -->
<div class="collapse mb-4 {{ $period === 'custom' ? 'show' : '' }}" id="customDateRange">
    <div class="uk-card p-3 p-md-4">
        <form method="GET" action="{{ route('owner.dashboard') }}" class="row g-3 align-items-end">
            <input type="hidden" name="period" value="custom">
            <div class="col-12 col-md-4">
                <label for="start_date" class="form-label fw-semibold small text-dark mb-1">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}" required>
            </div>
            <div class="col-12 col-md-4">
                <label for="end_date" class="form-label fw-semibold small text-dark mb-1">Tanggal Akhir</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}" required>
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-uk-primary w-100">
                    <i class="fas fa-filter me-1.5"></i>Terapkan Filter
                </button>
                <a href="{{ route('owner.dashboard') }}" class="btn btn-uk-secondary">
                    <i class="fas fa-undo me-1.5"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- SECTION 1: METRIK ARUS KAS & LABA BERSIH (3 Cards Grid) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
        <span class="badge rounded-circle p-1" style="background-color: var(--uk-primary);"></span>
        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Arus Kas & Laba Bersih</h6>
    </div>
    <span class="text-muted small">Formula: Total Masuk - Total Keluar - Beban Toko</span>
</div>

<div class="row g-3 mb-4">
    <!-- 1. Laba Bersih Usaha (HERO DARK CARD #131515) -->
    <div class="col-12 col-md-4">
        <div class="uk-stat-card-dark h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.06em; color: rgba(255, 250, 251, 0.7);">
                        Laba Bersih Usaha
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(125, 226, 209, 0.2); color: var(--uk-accent); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mb-1 flex-wrap">
                    <h3 class="fw-bold mb-0" style="font-size: clamp(1.35rem, 1.8vw, 1.65rem); letter-spacing: -0.03em; color: #FFFAFB;">
                        {{ format_rupiah($netProfit) }}
                    </h3>
                </div>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid rgba(255, 250, 251, 0.12); font-size: 0.78rem;">
                @if($netProfit >= 0)
                    <span class="badge px-2 py-1 rounded-pill" style="background-color: rgba(125, 226, 209, 0.2); color: var(--uk-accent); font-weight: 600;">
                        <i class="fas fa-arrow-trend-up me-1"></i>Surplus Positif
                    </span>
                @else
                    <span class="badge px-2 py-1 rounded-pill" style="background-color: rgba(43, 44, 40, 0.45); color: #FFFAFB; font-weight: 600; border: 1px solid rgba(255, 250, 251, 0.2);">
                        <i class="fas fa-arrow-trend-down me-1"></i>Defisit Arus Kas
                    </span>
                @endif
                <a href="{{ route('owner.reports.index') }}" style="color: var(--uk-accent); font-weight: 600;">
                    Laporan Lengkap <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Total Uang Masuk (PRIMARY SOLID CARD #339989) -->
    <div class="col-12 col-md-4">
        <div class="uk-stat-card-primary h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.06em; color: rgba(255, 250, 251, 0.85);">
                        Total Uang Masuk
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(255, 250, 251, 0.2); color: #FFFAFB; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-white" style="font-size: clamp(1.35rem, 1.8vw, 1.65rem); letter-spacing: -0.03em;">
                    {{ format_rupiah($totalIncome) }}
                </h3>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid rgba(255, 250, 251, 0.2); font-size: 0.78rem;">
                <span class="text-white opacity-90"><i class="fas fa-check-circle me-1"></i>Kasir & Kas Masuk</span>
                <a href="{{ route('owner.transactions.index') }}" class="text-white fw-bold">
                    Detail Mutasi <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Total Uang Keluar (CLEAN LIGHT CARD) -->
    <div class="col-12 col-md-4">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Total Uang Keluar
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(43, 44, 40, 0.1); color: var(--uk-dark-secondary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="font-size: clamp(1.35rem, 1.8vw, 1.65rem); letter-spacing: -0.03em; color: var(--uk-danger);">
                    {{ format_rupiah($totalExpense) }}
                </h3>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <span class="text-muted"><i class="fas fa-circle-minus text-danger me-1"></i>Pengeluaran kas tercatat</span>
                <a href="{{ route('owner.transactions.index') }}" class="text-danger fw-semibold">
                    Detail Kas <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2: OPERASIONAL, PENJUALAN & INVENTARIS (4 Cards Grid) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
        <span class="badge rounded-circle p-1" style="background-color: var(--uk-accent);"></span>
        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Operasional, Modal & Inventaris</h6>
    </div>
    <span class="text-muted small">Aktivitas penjualan kasir dan ketersediaan stok toko</span>
</div>

<div class="row g-3 mb-4">
    <!-- 1. Penjualan Hari Ini (ACCENT SOLID CARD #7DE2D1) -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="uk-stat-card-accent h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.06em; color: var(--uk-dark);">
                        Penjualan Hari Ini
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(19, 21, 21, 0.1); color: var(--uk-dark); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-cash-register"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0" style="font-size: 1.35rem; color: var(--uk-dark); letter-spacing: -0.02em;">
                    {{ format_rupiah($todaySalesTotal) }}
                </h4>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid rgba(19, 21, 21, 0.12); font-size: 0.78rem;">
                <span class="fw-semibold" style="color: var(--uk-dark);">
                    <i class="fas fa-receipt me-1 opacity-75"></i>{{ $todaySalesCount }} Transaksi
                </span>
                <a href="{{ route('owner.sales.index') }}" class="fw-bold" style="color: var(--uk-dark);">
                    Kasir <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Modal Usaha / Capital -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Modal Usaha
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(43, 44, 40, 0.08); color: var(--uk-dark-secondary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.35rem; letter-spacing: -0.02em;">
                    {{ format_rupiah($totalCapital) }}
                </h4>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <span class="text-muted"><i class="fas fa-shield-alt text-primary me-1"></i>Total modal disetor</span>
                <a href="{{ route('owner.capital.index') }}" class="text-primary fw-semibold">
                    Kelola <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Beban Operasional -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Beban Operasional
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(125, 226, 209, 0.25); color: var(--uk-dark); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.35rem; letter-spacing: -0.02em;">
                    {{ format_rupiah($totalOperational) }}
                </h4>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <span class="text-muted"><i class="fas fa-store me-1"></i>Listrik, sewa & rutin</span>
                <a href="{{ route('owner.expenses.index') }}" class="text-warning fw-semibold">
                    Rincian <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Status Stok Inventaris -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Total Produk Aktif
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(51, 153, 137, 0.1); color: var(--uk-primary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mb-1">
                    <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.35rem; letter-spacing: -0.02em;">
                        {{ number_format($totalProducts) }}
                    </h4>
                    <span class="text-muted small">Item terdaftar</span>
                </div>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <div class="d-flex gap-1">
                    @if($lowStockCount > 0)
                        <span class="badge bg-warning text-dark px-1.5 py-0.5 rounded" style="font-size: 0.68rem;">{{ $lowStockCount }} Menipis</span>
                    @endif
                    @if($outOfStockCount > 0)
                        <span class="badge bg-danger text-white px-1.5 py-0.5 rounded" style="font-size: 0.68rem;">{{ $outOfStockCount }} Habis</span>
                    @endif
                    @if($lowStockCount == 0 && $outOfStockCount == 0)
                        <span class="text-success small"><i class="fas fa-check-circle me-1"></i>Stok Prima</span>
                    @endif
                </div>
                <a href="{{ route('owner.stock.index') }}" class="text-primary fw-semibold">
                    Stok <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 3: PRODUK PERHATIAN & MUTASI TRANSAKSI TERBARU -->
<div class="row g-4">
    <!-- Kolom Kiri: Produk Perlu Perhatian (Stok Menipis/Habis) -->
    <div class="col-12 col-lg-5">
        <div class="uk-card h-100 d-flex flex-column p-0 overflow-hidden">
            <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-bell text-warning"></i>
                    <h6 class="fw-bold text-dark mb-0">Peringatan Stok Toko</h6>
                </div>
                <a href="{{ route('owner.stock.index') }}" class="btn btn-xs btn-uk-outline rounded-pill px-2.5">
                    <i class="fas fa-boxes-stacked me-1"></i>Semua Stok
                </a>
            </div>

            <div class="p-0 flex-grow-1">
                @if($attentionProducts->isEmpty())
                    <div class="uk-empty-state py-5">
                        <div class="uk-empty-icon" style="background-color: rgba(51, 153, 137, 0.1); color: var(--uk-primary);">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div class="uk-empty-title">Semua Stok Aman</div>
                        <p class="uk-empty-desc mb-0">Tidak ada produk yang berada di bawah batas minimum stok saat ini.</p>
                    </div>
                @else
                    <ul class="list-group list-group-flush border-0">
                        @foreach($attentionProducts as $prod)
                            <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center border-bottom-subtle">
                                <div class="me-2">
                                    <div class="fw-bold text-dark mb-0.5" style="font-size: 0.88rem;">{{ $prod->name }}</div>
                                    <div class="text-muted" style="font-size: 0.78rem;">
                                        Kategori: <span class="text-dark">{{ $prod->category->name ?? 'Umum' }}</span> &bull; Min: {{ $prod->min_stock }} {{ $prod->unit }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    @if($prod->isOutOfStock())
                                        <span class="badge bg-danger text-white mb-1 d-inline-block px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                                            Habis
                                        </span>
                                    @elseif($prod->isLowStock())
                                        <span class="badge bg-warning text-dark mb-1 d-inline-block px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                                            Menipis
                                        </span>
                                    @endif
                                    <div class="fw-bold {{ $prod->stock <= 0 ? 'text-danger' : 'text-warning' }}" style="font-size: 0.95rem;">
                                        {{ number_format($prod->stock) }} <span class="fw-normal small text-muted">{{ $prod->unit }}</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="p-3 px-4 bg-white border-top text-center mt-auto">
                <a href="{{ route('owner.stock.adjust') }}" class="btn btn-sm btn-uk-secondary w-100 rounded-pill">
                    <i class="fas fa-sliders-h me-1.5"></i>Lakukan Penyesuaian / Restock
                </a>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Transaksi Keuangan Terbaru -->
    <div class="col-12 col-lg-7">
        <div class="uk-card h-100 d-flex flex-column p-0 overflow-hidden">
            <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-clock-rotate-left text-primary"></i>
                    <h6 class="fw-bold text-dark mb-0">Mutasi Kas Terbaru</h6>
                </div>
                <a href="{{ route('owner.transactions.index') }}" class="btn btn-xs btn-uk-outline rounded-pill px-2.5">
                    <i class="fas fa-list me-1"></i>Lihat Semua
                </a>
            </div>

            <div class="p-0 flex-grow-1">
                @if($recentTransactions->isEmpty())
                    <div class="uk-empty-state py-5">
                        <div class="uk-empty-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="uk-empty-title">Belum Ada Transaksi</div>
                        <p class="uk-empty-desc mb-3">Mulai catat transaksi penjualan atau uang masuk usaha Anda.</p>
                        <a href="{{ route('owner.sales.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3">
                            <i class="fas fa-plus me-1"></i>Catat Penjualan
                        </a>
                    </div>
                @else
                    <div class="table-responsive mb-0">
                        <table class="table uk-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Tipe & Kategori</th>
                                    <th>Keterangan / Sumber</th>
                                    <th class="text-end">Nominal</th>
                                    <th class="pe-4 text-center">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-semibold text-dark" style="font-size: 0.82rem;">
                                                {{ $transaction->transaction_date->format('d/m/Y') }}
                                            </div>
                                            <div class="text-muted" style="font-size: 0.7rem;">
                                                {{ $transaction->created_at ? $transaction->created_at->format('H:i') : '' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($transaction->type === 'masuk')
                                                <span class="uk-badge-success mb-1 d-inline-flex align-items-center gap-1">
                                                    <i class="fas fa-arrow-down" style="font-size: 0.65rem;"></i>
                                                    {{ $transaction->is_sale ? 'Penjualan' : 'Masuk' }}
                                                </span>
                                            @else
                                                <span class="uk-badge-danger mb-1 d-inline-flex align-items-center gap-1">
                                                    <i class="fas fa-arrow-up" style="font-size: 0.65rem;"></i>
                                                    Keluar
                                                </span>
                                            @endif
                                            <div class="text-muted" style="font-size: 0.72rem;">
                                                {{ $transaction->category->name ?? 'Kas Umum' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark text-truncate" style="max-width: 200px; font-size: 0.82rem;">
                                                {{ $transaction->source ?: ($transaction->description ?: '-') }}
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold {{ $transaction->type === 'masuk' ? 'text-success' : 'text-danger' }}" style="font-size: 0.88rem;">
                                            {{ $transaction->type === 'masuk' ? '+' : '-' }} {{ format_rupiah($transaction->amount) }}
                                        </td>
                                        <td class="pe-4 text-center">
                                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.7rem;" title="{{ $transaction->user ? $transaction->user->name : '-' }}">
                                                {{ $transaction->user ? Str::limit($transaction->user->name, 10) : '-' }}
                                            </span>
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
