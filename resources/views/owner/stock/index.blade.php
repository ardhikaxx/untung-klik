@extends('layouts.app')

@section('title', 'Manajemen & Mutasi Stok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Manajemen & Mutasi Stok</h4>
        <p class="text-muted mb-0">Pantau peringatan stok menipis dan audit trail riwayat keluar-masuk barang</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-boxes me-1"></i>Katalog Produk
        </a>
        <a href="{{ route('owner.stock.adjust') }}" class="btn btn-warning">
            <i class="fas fa-sliders-h me-1"></i>Penyesuaian Stok
        </a>
    </div>
</div>

<!-- Alert Peringatan Stok Menipis & Habis -->
@if($outOfStockProducts->isNotEmpty() || $lowStockProducts->isNotEmpty())
    <div class="row g-3 mb-4">
        @if($outOfStockProducts->isNotEmpty())
            <div class="col-lg-6">
                <div class="card border-danger border shadow-sm">
                    <div class="card-header bg-danger-subtle text-danger fw-bold d-flex justify-content-between align-items-center py-2">
                        <span><i class="fas fa-times-circle me-1"></i>Stok Habis ({{ $outOfStockProducts->count() }} Produk)</span>
                        <span class="badge bg-danger text-white">Perlu Restock Segera</span>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush small">
                            @foreach($outOfStockProducts->take(5) as $p)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold text-dark">{{ $p->name }}</span>
                                        <span class="text-muted ms-1">({{ $p->category ? $p->category->name : 'Umum' }})</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-danger text-white">0 {{ $p->unit }}</span>
                                        <a href="{{ route('owner.stock.adjust', ['product_id' => $p->id]) }}" class="btn btn-xs btn-outline-danger py-0 px-2" style="font-size: 0.75rem;">+ Isi Stok</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if($lowStockProducts->isNotEmpty())
            <div class="col-lg-6">
                <div class="card border-warning border shadow-sm">
                    <div class="card-header bg-warning-subtle text-warning-emphasis fw-bold d-flex justify-content-between align-items-center py-2">
                        <span><i class="fas fa-exclamation-triangle me-1"></i>Stok Menipis ({{ $lowStockProducts->count() }} Produk)</span>
                        <span class="badge bg-warning text-dark">&le; Batas Minimum</span>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush small">
                            @foreach($lowStockProducts->take(5) as $p)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold text-dark">{{ $p->name }}</span>
                                        <span class="text-muted ms-1">(Min: {{ $p->min_stock }} {{ $p->unit }})</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-warning text-dark fw-bold">{{ $p->stock }} {{ $p->unit }}</span>
                                        <a href="{{ route('owner.stock.adjust', ['product_id' => $p->id]) }}" class="btn btn-xs btn-outline-warning py-0 px-2" style="font-size: 0.75rem;">+ Tambah</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif

<!-- Filter Mutasi -->
<div class="card border shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('owner.stock.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="product_id" class="form-label fw-semibold small">Produk</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Semua Produk</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                                {{ $prod->name }} (Sisa: {{ $prod->stock }} {{ $prod->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label fw-semibold small">Aktivitas / Tipe Mutasi</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Semua Aktivitas</option>
                        <option value="sale" {{ request('type') === 'sale' ? 'selected' : '' }}>Penjualan</option>
                        <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Penambahan Stok</option>
                        <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Pengurangan Stok</option>
                        <option value="initial" {{ request('type') === 'initial' ? 'selected' : '' }}>Stok Awal</option>
                        <option value="damaged" {{ request('type') === 'damaged' ? 'selected' : '' }}>Barang Rusak</option>
                        <option value="lost" {{ request('type') === 'lost' ? 'selected' : '' }}>Barang Hilang</option>
                        <option value="correction" {{ request('type') === 'correction' ? 'selected' : '' }}>Koreksi Stok</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label fw-semibold small">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label fw-semibold small">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('owner.stock.index') }}" class="btn btn-outline-secondary" title="Reset">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Histori Mutasi Stok -->
<div class="card border shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">
            <i class="fas fa-history text-muted me-2"></i>Histori Pergerakan Stok
        </h6>
        <span class="text-muted small">Total {{ $movements->total() }} catatan pergerakan</span>
    </div>
    <div class="card-body p-0">
        @if($movements->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-cubes fa-3x text-muted opacity-50"></i>
                </div>
                <h6 class="text-muted fw-bold">Belum Ada Histori Pergerakan Stok</h6>
                <p class="text-muted small mb-3">Semua mutasi stok (penjualan, penambahan, koreksi, barang rusak) akan tercatat otomatis di sini.</p>
                <a href="{{ route('owner.stock.adjust') }}" class="btn btn-warning">
                    <i class="fas fa-sliders-h me-1"></i>Lakukan Penyesuaian Stok
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">No</th>
                            <th>Waktu</th>
                            <th>Produk</th>
                            <th>Aktivitas</th>
                            <th class="text-center">Perubahan</th>
                            <th class="text-center">Sebelum &rarr; Sesudah</th>
                            <th>Keterangan / Alasan</th>
                            <th class="pe-3">Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $index => $move)
                            <tr>
                                <td class="ps-3 text-muted">{{ $movements->firstItem() + $index }}</td>
                                <td class="small text-muted" style="white-space: nowrap;">
                                    {{ $move->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $move->product ? $move->product->name : 'Produk Dihapus' }}</div>
                                    @if($move->product && $move->product->sku)
                                        <div class="text-muted small">SKU: {{ $move->product->sku }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $move->type_badge_class }} px-2 py-1">
                                        {{ $move->type_label }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold fs-6 {{ $move->quantity > 0 ? 'text-success' : ($move->quantity < 0 ? 'text-danger' : 'text-muted') }}">
                                    {{ $move->quantity > 0 ? '+' : '' }}{{ number_format($move->quantity) }}
                                </td>
                                <td class="text-center small">
                                    <span class="text-muted">{{ number_format($move->stock_before) }}</span>
                                    <span class="mx-1">&rarr;</span>
                                    <span class="fw-bold text-dark">{{ number_format($move->stock_after) }}</span>
                                </td>
                                <td class="small">
                                    <div class="text-dark">{{ $move->notes ?: '-' }}</div>
                                    @if($move->reference_id)
                                        <div class="text-muted">Ref: Transaksi #{{ $move->reference_id }}</div>
                                    @endif
                                </td>
                                <td class="pe-3 small text-muted">
                                    <i class="fas fa-user-circle me-1"></i>{{ $move->user ? $move->user->name : 'Sistem' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($movements->hasPages())
                <div class="p-3 border-top">
                    {{ $movements->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
