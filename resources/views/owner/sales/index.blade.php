@extends('layouts.app')

@section('title', 'Riwayat & Manajemen Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Riwayat & Manajemen Penjualan</h4>
        <p class="text-muted mb-0">Kelola dan pantau seluruh transaksi penjualan kasir, cetak nota, dan pantau omset usaha.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('owner.reports.sales') }}" class="btn btn-outline-primary">
            <i class="fas fa-chart-line me-1"></i>Analisis & Laporan Penjualan
        </a>
        <a href="{{ route('owner.sales.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i>Catat Penjualan Baru
        </a>
    </div>
</div>

<!-- Stat Cards Sesuai Filter Aktif -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="p-2 rounded-3 bg-success bg-opacity-10 text-success me-2">
                        <i class="fas fa-cash-register fs-5"></i>
                    </div>
                    <span class="text-muted small fw-semibold">Total Omset</span>
                </div>
                <h4 class="fw-bold text-success mb-1">{{ format_rupiah($filteredSalesTotal) }}</h4>
                <div class="small text-muted">Hari Ini: {{ format_rupiah($todaySalesTotal) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="p-2 rounded-3 bg-primary bg-opacity-10 text-primary me-2">
                        <i class="fas fa-receipt fs-5"></i>
                    </div>
                    <span class="text-muted small fw-semibold">Jumlah Transaksi</span>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ number_format($filteredSalesCount) }}</h4>
                <div class="small text-muted">Hari Ini: {{ $todaySalesCount }} transaksi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="p-2 rounded-3 bg-warning bg-opacity-10 text-warning me-2">
                        <i class="fas fa-box-open fs-5"></i>
                    </div>
                    <span class="text-muted small fw-semibold">Item Terjual</span>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ number_format($filteredItemsCount) }}</h4>
                <div class="small text-muted">Rata-rata: {{ format_rupiah($averageOrderValue) }}/trx</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="p-2 rounded-3 bg-info bg-opacity-10 text-info me-2">
                        <i class="fas fa-coins fs-5"></i>
                    </div>
                    <span class="text-muted small fw-semibold">Estimasi Laba Kotor</span>
                </div>
                <h4 class="fw-bold text-info mb-1">{{ format_rupiah($filteredProfit) }}</h4>
                <div class="small text-muted">
                    Margin: {{ $filteredSalesTotal > 0 ? round(($filteredProfit / $filteredSalesTotal) * 100, 1) : 0 }}%
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Periode & Pencarian -->
<div class="card border shadow-sm mb-4">
    <div class="card-body">
        <!-- Quick Period Shortcuts -->
        <div class="d-flex gap-1 flex-wrap mb-3 pb-3 border-bottom">
            <a href="{{ route('owner.sales.index') }}"
               class="btn btn-sm {{ empty($period) && !request('start_date') ? 'btn-success' : 'btn-outline-secondary' }}">
                Semua
            </a>
            <a href="{{ route('owner.sales.index', ['period' => 'today']) }}"
               class="btn btn-sm {{ $period === 'today' ? 'btn-success' : 'btn-outline-secondary' }}">
                Hari Ini
            </a>
            <a href="{{ route('owner.sales.index', ['period' => 'yesterday']) }}"
               class="btn btn-sm {{ $period === 'yesterday' ? 'btn-success' : 'btn-outline-secondary' }}">
                Kemarin
            </a>
            <a href="{{ route('owner.sales.index', ['period' => '7days']) }}"
               class="btn btn-sm {{ $period === '7days' ? 'btn-success' : 'btn-outline-secondary' }}">
                7 Hari Terakhir
            </a>
            <a href="{{ route('owner.sales.index', ['period' => 'this_month']) }}"
               class="btn btn-sm {{ $period === 'this_month' ? 'btn-success' : 'btn-outline-secondary' }}">
                Bulan Ini
            </a>
        </div>

        <form method="GET" action="{{ route('owner.sales.index') }}">
            <div class="row g-2">
                <div class="col-md-3">
                    <label for="search" class="form-label fw-semibold small">Cari Transaksi</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="No faktur, pelanggan, produk...">
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <label for="payment_method" class="form-label fw-semibold small">Metode Bayar</label>
                    <select class="form-select form-select-sm" id="payment_method" name="payment_method">
                        <option value="">Semua Metode</option>
                        <option value="Tunai" {{ request('payment_method') === 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="Transfer" {{ request('payment_method') === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="QRIS" {{ request('payment_method') === 'QRIS' ? 'selected' : '' }}>QRIS</option>
                        <option value="Lainnya" {{ request('payment_method') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <label for="user_id" class="form-label fw-semibold small">Kasir / Petugas</label>
                    <select class="form-select form-select-sm" id="user_id" name="user_id">
                        <option value="">Semua Kasir</option>
                        @foreach($cashiers as $c)
                            <option value="{{ $c->id }}" {{ request('user_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ ucfirst($c->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <label for="start_date" class="form-label fw-semibold small">Dari Tanggal</label>
                    <input type="date" class="form-control form-control-sm" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2 col-6">
                    <label for="end_date" class="form-label fw-semibold small">Sampai Tanggal</label>
                    <input type="date" class="form-control form-control-sm" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-1 col-12 d-flex align-items-end gap-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100" title="Terapkan Filter">
                        <i class="fas fa-filter"></i>
                    </button>
                    <a href="{{ route('owner.sales.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset">
                        <i class="fas fa-undo"></i>
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
                <i class="fas fa-shopping-bag fa-3x text-muted opacity-50 mb-3"></i>
                <h6 class="text-muted fw-bold">Belum Ada Transaksi Penjualan</h6>
                <p class="text-muted small mb-3">Tidak ada data transaksi yang cocok dengan filter yang dipilih.</p>
                <a href="{{ route('owner.sales.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i>Catat Penjualan Sekarang
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">No</th>
                            <th>No. Faktur / Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Item Produk Terjual</th>
                            <th class="text-end">Total Bayar</th>
                            <th class="text-center">Pembayaran</th>
                            <th>Kasir</th>
                            <th class="text-center pe-3" style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $index => $sale)
                            <tr>
                                <td class="ps-3 text-muted">{{ $sales->firstItem() + $index }}</td>
                                <td class="small" style="white-space: nowrap;">
                                    <div class="fw-bold text-dark">{{ $sale->formatted_invoice_number }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $sale->transaction_date->format('d/m/Y') }} {{ $sale->created_at ? $sale->created_at->format('H:i') : '' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark small">{{ $sale->customer_name ?: 'Pelanggan Umum' }}</div>
                                    @if($sale->customer_phone)
                                        <div class="text-muted small" style="font-size: 0.75rem;">
                                            <i class="fab fa-whatsapp text-success me-1"></i>{{ $sale->customer_phone }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($sale->items->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($sale->items->take(3) as $item)
                                                <span class="badge bg-light text-dark border">
                                                    {{ $item->product_name }} &times; {{ $item->quantity }}
                                                </span>
                                            @endforeach
                                            @if($sale->items->count() > 3)
                                                <span class="badge bg-light text-secondary border">+{{ $sale->items->count() - 3 }} item</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">{{ $sale->description }}</span>
                                    @endif
                                </td>
                                <td class="text-end" style="white-space: nowrap;">
                                    <div class="fw-bold text-success fs-6">{{ format_rupiah($sale->amount) }}</div>
                                    @if($sale->discount > 0)
                                        <div class="text-muted text-decoration-line-through" style="font-size: 0.7rem;">
                                            {{ format_rupiah($sale->subtotal) }}
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger" style="font-size: 0.65rem;">
                                            Diskon {{ format_rupiah($sale->discount) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center" style="white-space: nowrap;">
                                    <span class="badge {{ $sale->payment_method === 'Tunai' ? 'bg-success' : 'bg-primary' }} mb-1">
                                        {{ $sale->payment_method ?: 'Tunai' }}
                                    </span>
                                    @if($sale->cash_change > 0)
                                        <div class="text-muted" style="font-size: 0.7rem;">Kembali: {{ format_rupiah($sale->cash_change) }}</div>
                                    @endif
                                </td>
                                <td class="small text-muted" style="white-space: nowrap;">
                                    <i class="fas fa-user-circle me-1"></i>{{ $sale->user ? $sale->user->name : '-' }}
                                </td>
                                <td class="text-center pe-3">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('owner.sales.show', $sale) }}" class="btn btn-sm btn-outline-primary" title="Lihat & Cetak Nota">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                        <form action="{{ route('owner.sales.destroy', $sale) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete(event, 'Membatalkan penjualan ini akan mengembalikan stok produk yang telah terjual!')"
                                                    title="Batalkan / Hapus">
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
                <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">
                        Menampilkan {{ $sales->firstItem() }} - {{ $sales->lastItem() }} dari {{ $sales->total() }} penjualan
                    </small>
                    {{ $sales->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
