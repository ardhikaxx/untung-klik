@extends('layouts.app')

@section('title', 'Penjualan Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Riwayat Penjualan Saya</h4>
        <p class="text-muted mb-0">Daftar transaksi penjualan yang telah Anda catat hari ini dan sebelumnya</p>
    </div>
    <a href="{{ route('karyawan.sales.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>Catat Penjualan Baru
    </a>
</div>

<!-- Stat Ringkas -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-success-subtle text-success rounded-3">
                    <i class="fas fa-cash-register fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Total Penjualan Saya Hari Ini</div>
                    <div class="fs-4 fw-bold text-success">{{ format_rupiah($todaySalesTotal) }}</div>
                    <div class="small text-muted">{{ $todaySalesCount }} transaksi berhasil dicatat hari ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-primary-subtle text-primary rounded-3">
                    <i class="fas fa-receipt fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Total Seluruh Transaksi</div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($sales->total()) }}</div>
                    <div class="small text-muted">Total transaksi yang pernah Anda catat</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card border shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('karyawan.sales.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-semibold small">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-semibold small">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('karyawan.sales.index') }}" class="btn btn-outline-secondary" title="Reset">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Riwayat Penjualan -->
<div class="card border shadow-sm">
    <div class="card-body p-0">
        @if($sales->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-shopping-bag fa-3x text-muted opacity-50"></i>
                </div>
                <h6 class="text-muted fw-bold">Belum Ada Riwayat Penjualan</h6>
                <p class="text-muted small mb-3">Mulai catat transaksi penjualan barang untuk pelanggan toko.</p>
                <a href="{{ route('karyawan.sales.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>Catat Penjualan Pertama
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">No</th>
                            <th>Tanggal & No. Nota</th>
                            <th>Item Terjual</th>
                            <th class="text-end">Total Pembayaran</th>
                            <th class="text-center">Metode</th>
                            <th class="text-center pe-3" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $index => $sale)
                            <tr>
                                <td class="ps-3 text-muted">{{ $sales->firstItem() + $index }}</td>
                                <td class="small" style="white-space: nowrap;">
                                    <div class="fw-semibold text-dark">{{ $sale->transaction_date->format('d/m/Y') }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">#TRX-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                <td>
                                    @if($sale->items->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($sale->items as $item)
                                                <span class="badge bg-light text-dark border">
                                                    {{ $item->product_name }} &times; {{ $item->quantity }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted small">{{ $sale->description }}</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-success fs-6">
                                    {{ format_rupiah($sale->amount) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-dark border">
                                        {{ $sale->payment_method ?: 'Tunai' }}
                                    </span>
                                </td>
                                <td class="text-center pe-3">
                                    <a href="{{ route('karyawan.sales.show', $sale) }}" class="btn btn-sm btn-outline-info" title="Lihat Struk">
                                        <i class="fas fa-eye me-1"></i>Nota
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($sales->hasPages())
                <div class="p-3 border-top">
                    {{ $sales->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
