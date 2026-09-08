@extends('layouts.app')

@section('title', 'Manajemen & Mutasi Stok')

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Manajemen & Mutasi Stok</h2>
        <p class="uk-page-subtitle">Pantau peringatan stok toko dan audit trail seluruh riwayat keluar-masuk barang.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('owner.products.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
            <i class="fas fa-boxes-stacked me-1.5"></i>Katalog Produk
        </a>
        <a href="{{ route('owner.stock.adjust') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 shadow-xs fw-bold">
            <i class="fas fa-sliders-h me-1.5"></i>+ Penyesuaian Stok
        </a>
    </div>
</div>

<!-- Alert Peringatan Stok Menipis & Habis -->
@if($outOfStockProducts->isNotEmpty() || $lowStockProducts->isNotEmpty())
    <div class="row g-3 mb-4">
        @if($outOfStockProducts->isNotEmpty())
            <div class="col-12 col-lg-6">
                <div class="uk-card border-danger-subtle p-0 overflow-hidden" style="background-color: rgba(43, 44, 40, 0.04); border-left: 4px solid var(--uk-dark-secondary) !important;">
                    <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-danger" style="font-size: 0.88rem;">
                            <i class="fas fa-circle-xmark me-1.5"></i>Stok Habis ({{ $outOfStockProducts->count() }} Produk)
                        </span>
                        <span class="badge bg-danger text-white rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">Perlu Restock Segera</span>
                    </div>
                    <div class="p-0">
                        <ul class="list-group list-group-flush border-0">
                            @foreach($outOfStockProducts->take(5) as $p)
                                <li class="list-group-item px-4 py-2.5 d-flex justify-content-between align-items-center bg-transparent border-bottom-subtle">
                                    <div>
                                        <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $p->name }}</span>
                                        <span class="text-muted small ms-1">({{ $p->category ? $p->category->name : 'Umum' }})</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-danger text-white rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">0 {{ $p->unit }}</span>
                                        <a href="{{ route('owner.stock.adjust', ['product_id' => $p->id]) }}" class="btn btn-xs btn-uk-danger rounded-pill px-2.5">
                                            + Isi Stok
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if($lowStockProducts->isNotEmpty())
            <div class="col-12 col-lg-6">
                <div class="uk-card border-warning-subtle p-0 overflow-hidden" style="background-color: rgba(125, 226, 209, 0.12); border-left: 4px solid var(--uk-accent) !important;">
                    <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-warning-emphasis" style="font-size: 0.88rem;">
                            <i class="fas fa-triangle-exclamation text-warning me-1.5"></i>Stok Menipis ({{ $lowStockProducts->count() }} Produk)
                        </span>
                        <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">&le; Batas Minimum</span>
                    </div>
                    <div class="p-0">
                        <ul class="list-group list-group-flush border-0">
                            @foreach($lowStockProducts->take(5) as $p)
                                <li class="list-group-item px-4 py-2.5 d-flex justify-content-between align-items-center bg-transparent border-bottom-subtle">
                                    <div>
                                        <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $p->name }}</span>
                                        <span class="text-muted small ms-1">(Min: {{ $p->min_stock }} {{ $p->unit }})</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 fw-bold" style="font-size: 0.68rem;">{{ $p->stock }} {{ $p->unit }}</span>
                                        <a href="{{ route('owner.stock.adjust', ['product_id' => $p->id]) }}" class="btn btn-xs btn-uk-secondary rounded-pill px-2.5">
                                            + Tambah
                                        </a>
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
<div class="uk-card p-3 p-md-4 mb-4">
    <form method="GET" action="{{ route('owner.stock.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="product_id" class="form-label fw-semibold small text-dark mb-1">Produk</label>
                <select class="form-select form-select-sm" id="product_id" name="product_id">
                    <option value="">Semua Produk</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                            {{ $prod->name }} (Sisa: {{ $prod->stock }} {{ $prod->unit }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="type" class="form-label fw-semibold small text-dark mb-1">Aktivitas Mutasi</label>
                <select class="form-select form-select-sm" id="type" name="type">
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
                <a href="{{ route('owner.stock.index') }}" class="btn btn-uk-secondary btn-sm" title="Reset">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Tabel Histori Mutasi Stok -->
<div class="uk-card p-0 overflow-hidden">
    <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-history text-primary"></i>
            <h6 class="fw-bold mb-0 text-dark">Histori Pergerakan Stok</h6>
        </div>
        <span class="text-muted small">Total {{ $movements->total() }} mutasi tercatat</span>
    </div>
    <div class="p-0">
        @if($movements->isEmpty())
            <div class="uk-empty-state py-5">
                <div class="uk-empty-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="uk-empty-title">Belum Ada Histori Pergerakan Stok</div>
                <p class="uk-empty-desc mb-3">Semua pergerakan inventaris (penjualan, penambahan, koreksi) akan otomatis teraudit di sini.</p>
                <a href="{{ route('owner.stock.adjust') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5">
                    <i class="fas fa-sliders-h me-1"></i>Lakukan Penyesuaian Stok
                </a>
            </div>
        @else
            <div class="table-responsive mb-0">
                <table class="table uk-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Waktu</th>
                            <th>Produk</th>
                            <th>Aktivitas</th>
                            <th class="text-center">Perubahan</th>
                            <th class="text-center">Sebelum &rarr; Sesudah</th>
                            <th>Keterangan / Alasan</th>
                            <th class="pe-4">Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $index => $move)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $movements->firstItem() + $index }}</td>
                                <td class="small text-muted" style="white-space: nowrap;">
                                    {{ $move->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $move->product ? $move->product->name : 'Produk Dihapus' }}</div>
                                    @if($move->product && $move->product->sku)
                                        <div class="text-muted small" style="font-size: 0.72rem;">SKU: {{ $move->product->sku }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $move->type_badge_class }} px-2 py-0.5 rounded-pill shadow-xs" style="font-size: 0.7rem; font-weight: 600;">
                                        <i class="{{ $move->type_icon }} me-1"></i>{{ $move->type_label }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold {{ $move->quantity > 0 ? 'text-success' : ($move->quantity < 0 ? 'text-danger' : 'text-muted') }}" style="font-size: 0.9rem;">
                                    {{ $move->quantity > 0 ? '+' : '' }}{{ number_format($move->quantity) }}
                                </td>
                                <td class="text-center small">
                                    <span class="text-muted">{{ number_format($move->stock_before) }}</span>
                                    <span class="mx-1 text-muted">&rarr;</span>
                                    <span class="fw-bold text-dark">{{ number_format($move->stock_after) }}</span>
                                </td>
                                <td class="small">
                                    <div class="text-dark">{{ $move->notes ?: '-' }}</div>
                                    @if($move->reference_id)
                                        <div class="text-muted" style="font-size: 0.72rem;">Ref: Nota #{{ $move->reference_id }}</div>
                                    @endif
                                </td>
                                <td class="pe-4 small text-muted">
                                    <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.7rem;">
                                        {{ $move->user ? $move->user->name : 'Sistem' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($movements->hasPages())
                <div class="p-3 px-4 border-top">
                    {{ $movements->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
