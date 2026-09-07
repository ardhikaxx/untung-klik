@extends('layouts.app')

@section('title', 'Penjualan Harian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Riwayat Penjualan Harian</h4>
        <p class="text-muted mb-0">Catatan transaksi penjualan produk terhubung langsung dengan pemotongan stok & kas masuk</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-boxes me-1"></i>Katalog Produk
        </a>
        <a href="{{ route('owner.sales.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i>Catat Penjualan Baru
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-6">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-success-subtle text-success rounded-3">
                    <i class="fas fa-cash-register fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Omzet Penjualan Hari Ini</div>
                    <div class="fs-4 fw-bold text-success">{{ format_rupiah($todaySalesTotal) }}</div>
                    <div class="small text-muted">{{ $todaySalesCount }} transaksi berhasil hari ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-primary-subtle text-primary rounded-3">
                    <i class="fas fa-file-invoice-dollar fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Total Transaksi Penjualan</div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($sales->total()) }}</div>
                    <div class="small text-muted">Seluruh riwayat transaksi produk</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card border shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('owner.sales.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label fw-semibold small">Cari Transaksi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Keterangan, produk, metode...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label fw-semibold small">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label fw-semibold small">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('owner.sales.index') }}" class="btn btn-outline-secondary" title="Reset">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Penjualan -->
<div class="card border shadow-sm">
    <div class="card-body p-0">
        @if($sales->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-shopping-bag fa-3x text-muted opacity-50"></i>
                </div>
                <h6 class="text-muted fw-bold">Belum Ada Transaksi Penjualan</h6>
                <p class="text-muted small mb-3">Catat penjualan produk Anda untuk langsung memotong stok dan membukukan pemasukan.</p>
                <a href="{{ route('owner.sales.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>Catat Penjualan Sekarang
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">No</th>
                            <th>Tanggal</th>
                            <th>Item Terjual</th>
                            <th class="text-end">Total Penjualan</th>
                            <th class="text-center">Pembayaran</th>
                            <th>Kasir / Petugas</th>
                            <th class="text-center pe-3" style="width: 140px;">Aksi</th>
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
                                <td class="small text-muted">
                                    <i class="fas fa-user-circle me-1"></i>{{ $sale->user ? $sale->user->name : '-' }}
                                </td>
                                <td class="text-center pe-3">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('owner.sales.show', $sale) }}" class="btn btn-sm btn-outline-info" title="Lihat Struk / Rincian">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('owner.sales.destroy', $sale) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete(event, 'Membatalkan penjualan ini akan menghapus kas masuk dan mengembalikan stok produk yang terjual!')"
                                                    title="Hapus Penjualan">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
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
