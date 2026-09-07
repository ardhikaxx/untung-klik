@extends('layouts.app')

@section('title', 'Laporan Penjualan & Produk')

@section('content')
<!-- Header & Navigasi Tab Laporan -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Laporan Penjualan & Produk</h4>
        <p class="text-muted mb-0">{{ $periodLabel }}</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('owner.sales.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus me-1"></i>Catat Penjualan
        </a>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.print()">
            <i class="fas fa-print me-1"></i>Cetak Laporan
        </button>
    </div>
</div>

<!-- Navigasi Tab Laporan: Kas vs Penjualan -->
<ul class="nav nav-pills mb-4 border-bottom pb-2">
    <li class="nav-item">
        <a class="nav-link text-secondary fw-semibold py-2 px-3" href="{{ route('owner.reports.index', ['period' => $period]) }}">
            <i class="fas fa-book me-1"></i>Buku Kas & Laba Bersih
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active bg-success fw-semibold py-2 px-3" href="{{ route('owner.reports.sales', ['period' => $period]) }}">
            <i class="fas fa-shopping-bag me-1"></i>Penjualan & Produk Terlaris
        </a>
    </li>
</ul>

<!-- Filter Periode & Kasir -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('owner.reports.sales') }}" id="periodForm">
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <div class="d-flex gap-1 flex-wrap">
                    <a href="{{ route('owner.reports.sales', ['period' => 'today']) }}"
                       class="btn btn-sm {{ $period === 'today' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('owner.reports.sales', ['period' => 'yesterday']) }}"
                       class="btn btn-sm {{ $period === 'yesterday' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Kemarin
                    </a>
                    <a href="{{ route('owner.reports.sales', ['period' => 'week']) }}"
                       class="btn btn-sm {{ $period === 'week' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Minggu Ini
                    </a>
                    <a href="{{ route('owner.reports.sales', ['period' => 'month']) }}"
                       class="btn btn-sm {{ $period === 'month' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Bulan Ini
                    </a>
                    <a href="{{ route('owner.reports.sales', ['period' => 'year']) }}"
                       class="btn btn-sm {{ $period === 'year' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Tahun Ini
                    </a>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md-3 col-6">
                    <label for="user_id" class="form-label small fw-semibold">Kasir / Petugas</label>
                    <select class="form-select form-select-sm" id="user_id" name="user_id">
                        <option value="">Semua Kasir</option>
                        @foreach($cashiers as $c)
                            <option value="{{ $c->id }}" {{ $cashierId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label for="payment_method" class="form-label small fw-semibold">Metode Pembayaran</label>
                    <select class="form-select form-select-sm" id="payment_method" name="payment_method">
                        <option value="">Semua Metode</option>
                        <option value="Tunai" {{ $paymentMethod === 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="Transfer" {{ $paymentMethod === 'Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="QRIS" {{ $paymentMethod === 'QRIS' ? 'selected' : '' }}>QRIS</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <label for="start_date" class="form-label small fw-semibold">Dari Tanggal</label>
                    <input type="date" class="form-control form-control-sm" id="start_date" name="start_date"
                           value="{{ $startDate }}">
                </div>
                <div class="col-md-2 col-6">
                    <label for="end_date" class="form-label small fw-semibold">Sampai Tanggal</label>
                    <input type="date" class="form-control form-control-sm" id="end_date" name="end_date"
                           value="{{ $endDate }}">
                </div>
                <div class="col-md-2 col-12 d-flex align-items-end gap-1">
                    <input type="hidden" name="period" value="custom">
                    <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('owner.reports.sales') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Kartu Ringkasan Metrik Utama Penjualan -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-cash-register text-success fs-5"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Total Omset Penjualan</h6>
                <h5 class="fw-bold text-success mb-0">{{ format_rupiah($totalSales) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
                <div class="rounded-circle bg-secondary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-tags text-secondary fs-5"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Total HPP (Modal Barang)</h6>
                <h5 class="fw-bold text-dark mb-0">{{ format_rupiah($totalHpp) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-coins text-primary fs-5"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Laba Kotor (Gross)</h6>
                <h5 class="fw-bold text-primary mb-0">{{ format_rupiah($grossProfit) }}</h5>
                <span class="badge bg-primary-subtle text-primary mt-1" style="font-size: 0.65rem;">Margin: {{ $profitMargin }}%</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
                <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-receipt text-warning fs-5"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Jumlah Transaksi</h6>
                <h5 class="fw-bold text-dark mb-0">{{ number_format($totalTransactions) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
                <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-boxes text-info fs-5"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Kuantitas Barang Terjual</h6>
                <h5 class="fw-bold text-info mb-0">{{ number_format($totalItemsSold) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
                <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-chart-line text-danger fs-5"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Rata-rata/Nota (AOV)</h6>
                <h5 class="fw-bold text-danger mb-0">{{ format_rupiah($averageOrderValue) }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Tabel Top 10 Produk Terlaris -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-crown text-warning me-2"></i>10 Produk Terlaris (Bestseller)
                </h6>
                <span class="text-muted small">Periode ini</span>
            </div>
            <div class="card-body p-0">
                @if($bestsellers->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-box-open fa-2x mb-2 opacity-50"></i>
                        <p class="mb-0 small">Belum ada data barang terjual pada periode ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th class="ps-3" style="width: 40px;">#</th>
                                    <th>Nama Produk</th>
                                    <th class="text-center">Terjual</th>
                                    <th class="text-end">Total Omset</th>
                                    <th class="text-end pe-3">Estimasi Laba</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bestsellers as $idx => $item)
                                    @php
                                        $profit = $item->total_revenue - $item->total_cost;
                                    @endphp
                                    <tr>
                                        <td class="ps-3 fw-bold text-muted">
                                            @if($idx === 0)
                                                <i class="fas fa-medal text-warning"></i>
                                            @elseif($idx === 1)
                                                <i class="fas fa-medal text-secondary"></i>
                                            @elseif($idx === 2)
                                                <i class="fas fa-medal" style="color: #cd7f32;"></i>
                                            @else
                                                {{ $idx + 1 }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $item->product_name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">
                                                {{ $item->total_qty }} unit
                                            </span>
                                        </td>
                                        <td class="text-end fw-semibold text-dark">
                                            {{ format_rupiah($item->total_revenue) }}
                                        </td>
                                        <td class="text-end pe-3 fw-bold text-success">
                                            {{ format_rupiah($profit) }}
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

    <!-- Ringkasan Metode Bayar & Kasir -->
    <div class="col-lg-4">
        <!-- Metode Pembayaran -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-wallet text-success me-2"></i>Metode Pembayaran
                </h6>
            </div>
            <div class="card-body p-3">
                @if($paymentBreakdown->isEmpty())
                    <p class="text-muted small text-center my-3">Belum ada transaksi</p>
                @else
                    @foreach($paymentBreakdown as $pay)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="fw-semibold text-dark d-block">{{ $pay->payment_method ?: 'Tunai' }}</span>
                                <small class="text-muted">{{ $pay->count }} transaksi</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success d-block">{{ format_rupiah($pay->total) }}</span>
                                <small class="text-muted">{{ $totalSales > 0 ? round(($pay->total / $totalSales) * 100) : 0 }}%</small>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Kinerja Kasir -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-users text-primary me-2"></i>Penjualan per Kasir
                </h6>
            </div>
            <div class="card-body p-3">
                @if($cashierBreakdown->isEmpty())
                    <p class="text-muted small text-center my-3">Belum ada transaksi</p>
                @else
                    @foreach($cashierBreakdown as $cb)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="fw-semibold text-dark d-block">{{ $cb->user ? $cb->user->name : '-' }}</span>
                                <small class="text-muted">{{ $cb->count }} penjualan dicatat</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-primary d-block">{{ format_rupiah($cb->total) }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Daftar Transaksi Penjualan Lengkap -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="fas fa-list-alt text-secondary me-2"></i>Riwayat Transaksi Penjualan Periode Ini
        </h6>
        <span class="text-muted small">Total {{ $transactions->total() }} Transaksi</span>
    </div>
    <div class="card-body p-0">
        @if($transactions->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                Tidak ada data penjualan untuk periode ini
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th class="ps-3" style="width: 40px;">No</th>
                            <th>No. Faktur / Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Item Barang</th>
                            <th class="text-end">Total Bayar</th>
                            <th class="text-center">Metode</th>
                            <th>Kasir</th>
                            <th class="text-center pe-3" style="width: 80px;">Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $idx => $t)
                            <tr>
                                <td class="ps-3 text-muted">{{ $transactions->firstItem() + $idx }}</td>
                                <td class="small" style="white-space: nowrap;">
                                    <div class="fw-bold text-dark">{{ $t->formatted_invoice_number }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $t->transaction_date->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark small">{{ $t->customer_name ?: 'Pelanggan Umum' }}</span>
                                </td>
                                <td>
                                    @if($t->items->isNotEmpty())
                                        <div class="small text-truncate" style="max-width: 250px;" title="{{ $t->items->pluck('product_name')->join(', ') }}">
                                            {{ $t->items->pluck('product_name')->join(', ') }}
                                        </div>
                                    @else
                                        <span class="text-muted small">{{ $t->description }}</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-success fs-6" style="white-space: nowrap;">
                                    {{ format_rupiah($t->amount) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-dark border">
                                        {{ $t->payment_method ?: 'Tunai' }}
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    {{ $t->user ? $t->user->name : '-' }}
                                </td>
                                <td class="text-center pe-3">
                                    <a href="{{ route('owner.sales.show', $t) }}" class="btn btn-sm btn-outline-primary" title="Lihat Nota">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">
                        Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                    </small>
                    {{ $transactions->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
