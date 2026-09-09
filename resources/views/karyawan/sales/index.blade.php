@extends('layouts.app')

@section('title', 'Riwayat Penjualan Kasir')

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Riwayat Penjualan Kasir</h2>
        <p class="uk-page-subtitle">Daftar transaksi kasir penjualan produk toko yang telah Anda layani.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('karyawan.sales.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 shadow-xs fw-bold">
            <i class="fas fa-plus-circle me-1.5"></i>Buka Kasir Baru
        </a>
    </div>
</div>

<!-- Stat Ringkas -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Omset Saya
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(14, 71, 73, 0.1); color: var(--uk-primary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-cash-register"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-success" style="font-size: 1.35rem; letter-spacing: -0.02em;">
                    {{ format_rupiah($filteredSalesTotal) }}
                </h4>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <span class="text-muted">Hari ini: {{ format_rupiah($todaySalesTotal) }}</span>
                <span class="text-primary fw-semibold">Omset</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Transaksi Saya
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(0, 38, 38, 0.08); color: var(--uk-dark); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.35rem; letter-spacing: -0.02em;">
                    {{ number_format($filteredSalesCount) }} <span class="fs-6 fw-normal text-muted">Nota</span>
                </h4>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <span class="text-muted">Hari ini: {{ $todaySalesCount }} Transaksi</span>
                <span class="text-dark fw-semibold">Shift</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Item Terjual
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(149, 198, 35, 0.2); color: var(--uk-dark); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.35rem; letter-spacing: -0.02em;">
                    {{ number_format($filteredItemsCount) }} <span class="fs-6 fw-normal text-muted">Pcs</span>
                </h4>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <span class="text-muted">Volume produk laku</span>
                <span class="text-warning fw-semibold">Barang</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Rata-rata / Nota
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(14, 71, 73, 0.1); color: var(--uk-primary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.35rem; letter-spacing: -0.02em;">
                    {{ format_rupiah($averageOrderValue) }}
                </h4>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <span class="text-muted">Nilai rata-rata nota</span>
                <span class="text-success fw-semibold">Rerata</span>
            </div>
        </div>
    </div>
</div>

<!-- Filter Periode & Pencarian -->
<div class="uk-card p-3 p-md-4 mb-4">
    <!-- Quick Period Shortcuts -->
    <div class="d-flex gap-1.5 flex-wrap mb-3 pb-3 border-bottom">
        <a href="{{ route('karyawan.sales.index') }}"
           class="btn btn-sm rounded-pill px-3 fw-semibold {{ empty($period) && !request('start_date') ? 'btn-uk-primary' : 'btn-uk-ghost' }}">
            Semua
        </a>
        <a href="{{ route('karyawan.sales.index', ['period' => 'today']) }}"
           class="btn btn-sm rounded-pill px-3 fw-semibold {{ $period === 'today' ? 'btn-uk-primary' : 'btn-uk-ghost' }}">
            Hari Ini
        </a>
        <a href="{{ route('karyawan.sales.index', ['period' => 'yesterday']) }}"
           class="btn btn-sm rounded-pill px-3 fw-semibold {{ $period === 'yesterday' ? 'btn-uk-primary' : 'btn-uk-ghost' }}">
            Kemarin
        </a>
        <a href="{{ route('karyawan.sales.index', ['period' => '7days']) }}"
           class="btn btn-sm rounded-pill px-3 fw-semibold {{ $period === '7days' ? 'btn-uk-primary' : 'btn-uk-ghost' }}">
            7 Hari Terakhir
        </a>
        <a href="{{ route('karyawan.sales.index', ['period' => 'this_month']) }}"
           class="btn btn-sm rounded-pill px-3 fw-semibold {{ $period === 'this_month' ? 'btn-uk-primary' : 'btn-uk-ghost' }}">
            Bulan Ini
        </a>
    </div>

    <form method="GET" action="{{ route('karyawan.sales.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label fw-semibold small text-dark mb-1">Cari Transaksi</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" id="search" name="search"
                           value="{{ request('search') }}" placeholder="No faktur, pelanggan...">
                </div>
            </div>
            <div class="col-md-2 col-6">
                <label for="payment_method" class="form-label fw-semibold small text-dark mb-1">Metode Bayar</label>
                <select class="form-select form-select-sm" id="payment_method" name="payment_method">
                    <option value="">Semua Metode</option>
                    <option value="Tunai" {{ request('payment_method') === 'Tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="Transfer" {{ request('payment_method') === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="QRIS" {{ request('payment_method') === 'QRIS' ? 'selected' : '' }}>QRIS</option>
                    <option value="Lainnya" {{ request('payment_method') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="col-md-2 col-6">
                <label for="start_date" class="form-label fw-semibold small text-dark mb-1">Dari Tanggal</label>
                <input type="date" class="form-control form-control-sm" id="start_date" name="start_date"
                       value="{{ request('start_date') }}">
            </div>
            <div class="col-md-2 col-6">
                <label for="end_date" class="form-label fw-semibold small text-dark mb-1">Sampai Tanggal</label>
                <input type="date" class="form-control form-control-sm" id="end_date" name="end_date"
                       value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2 col-12 d-flex gap-1">
                <button type="submit" class="btn btn-uk-primary btn-sm w-100" title="Terapkan Filter">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('karyawan.sales.index') }}" class="btn btn-uk-secondary btn-sm" title="Reset">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Tabel Riwayat Penjualan -->
<div class="uk-card p-0 overflow-hidden">
    @if($sales->isEmpty())
        <div class="uk-empty-state py-5">
            <div class="uk-empty-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="uk-empty-title">Belum Ada Riwayat Penjualan</div>
            <p class="uk-empty-desc mb-3">Mulai catat transaksi penjualan barang untuk pelanggan toko Anda.</p>
            <a href="{{ route('karyawan.sales.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5">
                <i class="fas fa-cash-register me-1"></i>Buka Kasir Sekarang
            </a>
        </div>
    @else
        <div class="table-responsive mb-0">
            <table class="table uk-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>No. Faktur & Waktu</th>
                        <th>Pelanggan</th>
                        <th>Item Terjual</th>
                        <th class="text-end">Total Pembayaran</th>
                        <th class="text-center">Metode</th>
                        <th class="text-center pe-4" style="width: 110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $index => $sale)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $sales->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $sale->formatted_invoice_number }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">
                                    {{ $sale->transaction_date->format('d/m/Y') }} {{ $sale->created_at ? $sale->created_at->format('H:i') : '' }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark small">{{ $sale->customer_name ?: 'Pelanggan Umum' }}</div>
                                @if($sale->customer_phone)
                                    <div class="text-muted small" style="font-size: 0.72rem;">
                                        <i class="fab fa-whatsapp text-success me-1"></i>{{ $sale->customer_phone }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($sale->items->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($sale->items->take(2) as $item)
                                            <span class="badge bg-light text-dark border px-2 py-0.5 rounded" style="font-size: 0.7rem;">
                                                {{ $item->product_name }} &times; {{ $item->quantity }}
                                            </span>
                                        @endforeach
                                        @if($sale->items->count() > 2)
                                            <span class="badge bg-light text-secondary border px-1.5 py-0.5 rounded" style="font-size: 0.68rem;">+{{ $sale->items->count() - 2 }} lainnya</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small">{{ $sale->description ?: '-' }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="fw-bold text-success" style="font-size: 0.9rem;">{{ format_rupiah($sale->amount) }}</div>
                                @if($sale->discount > 0)
                                    <div class="text-muted text-decoration-line-through" style="font-size: 0.7rem;">
                                        {{ format_rupiah($sale->subtotal) }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $sale->payment_method === 'Tunai' ? 'bg-success' : 'bg-primary' }} rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                    {{ $sale->payment_method ?: 'Tunai' }}
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('karyawan.sales.show', $sale) }}" class="btn btn-xs btn-uk-outline rounded-pill px-2.5" title="Lihat & Cetak Nota">
                                    <i class="fas fa-print me-1"></i>Nota
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($sales->hasPages())
            <div class="p-3 px-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan {{ $sales->firstItem() }} - {{ $sales->lastItem() }} dari {{ $sales->total() }} penjualan
                </small>
                {{ $sales->withQueryString()->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
