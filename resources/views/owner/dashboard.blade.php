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
<h6 class="fw-bold text-dark mb-3">
    <i class="fas fa-wallet text-success me-2"></i>Kondisi Keuangan Usaha
</h6>
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Total Uang Masuk</p>
                        <h4 class="fw-bold mb-0 text-success">{{ format_rupiah($totalIncome) }}</h4>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-arrow-down text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Total Uang Keluar</p>
                        <h4 class="fw-bold mb-0 text-danger">{{ format_rupiah($totalExpense) }}</h4>
                    </div>
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="fas fa-arrow-up text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Modal Usaha</p>
                        <h4 class="fw-bold mb-0 text-primary">{{ format_rupiah($totalCapital) }}</h4>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-coins text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Beban Operasional</p>
                        <h4 class="fw-bold mb-0 text-warning">{{ format_rupiah($totalOperational) }}</h4>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-receipt text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Laba Bersih</p>
                        <h4 class="fw-bold mb-0 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ format_rupiah($netProfit) }}
                        </h4>
                    </div>
                    <div class="rounded-circle {{ $netProfit >= 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 p-3">
                        <i class="fas fa-chart-line {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}"></i>
                    </div>
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
