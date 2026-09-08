@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Katalog Produk & Stok</h2>
        <p class="uk-page-subtitle">Kelola daftar produk toko, harga jual, margin, dan ketersediaan stok fisik barang.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('owner.stock.adjust') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
            <i class="fas fa-sliders-h me-1.5"></i>Penyesuaian Stok
        </a>
        <a href="{{ route('owner.product-categories.index') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3">
            <i class="fas fa-layer-group me-1.5"></i>Kategori
        </a>
        <a href="{{ route('owner.products.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 shadow-xs fw-bold">
            <i class="fas fa-plus me-1.5"></i>Tambah Produk
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Total Produk
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(51, 153, 137, 0.1); color: var(--uk-primary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.35rem; letter-spacing: -0.02em;">
                    {{ number_format($totalProducts) }} <span class="fs-6 fw-normal text-muted">Item</span>
                </h4>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <span class="text-muted">Item terdaftar dalam katalog</span>
                <span class="text-primary fw-semibold">Katalog</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Stok Menipis
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(125, 226, 209, 0.25); color: var(--uk-dark); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-warning" style="font-size: 1.35rem; letter-spacing: -0.02em;">
                    {{ number_format($lowStockCount) }} <span class="fs-6 fw-normal text-muted">Produk</span>
                </h4>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <span class="text-muted">&le; batas minimum stok</span>
                <span class="text-warning fw-semibold">Peringatan</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="uk-stat-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">
                        Stok Habis
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(43, 44, 40, 0.1); color: var(--uk-dark-secondary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-circle-xmark"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-danger" style="font-size: 1.35rem; letter-spacing: -0.02em;">
                    {{ number_format($outOfStockCount) }} <span class="fs-6 fw-normal text-muted">Produk</span>
                </h4>
            </div>
            <div class="pt-2 mt-2 d-flex align-items-center justify-content-between" style="border-top: 1px solid var(--uk-border); font-size: 0.78rem;">
                <span class="text-muted">Perlu segera restock barang</span>
                <span class="text-danger fw-semibold">Habis</span>
            </div>
        </div>
    </div>
</div>

<!-- Filter Box -->
<div class="uk-card p-3 p-md-4 mb-4">
    <form method="GET" action="{{ route('owner.products.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label fw-semibold small text-dark mb-1">Cari Produk</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Nama produk atau SKU...">
                </div>
            </div>
            <div class="col-md-3 col-6">
                <label for="category_id" class="form-label fw-semibold small text-dark mb-1">Kategori</label>
                <select class="form-select form-select-sm" id="category_id" name="category_id">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-6">
                <label for="stock_status" class="form-label fw-semibold small text-dark mb-1">Kondisi Stok</label>
                <select class="form-select form-select-sm" id="stock_status" name="stock_status">
                    <option value="">Semua Stok</option>
                    <option value="safe" {{ request('stock_status') === 'safe' ? 'selected' : '' }}>Stok Aman</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Stok Menipis</option>
                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Stok Habis</option>
                </select>
            </div>
            <div class="col-md-2 col-6">
                <label for="status" class="form-label fw-semibold small text-dark mb-1">Status</label>
                <select class="form-select form-select-sm" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2 col-6 d-flex gap-1">
                <button type="submit" class="btn btn-uk-primary btn-sm w-100" title="Terapkan Filter">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('owner.products.index') }}" class="btn btn-uk-secondary btn-sm" title="Reset Filter">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Product Table -->
<div class="uk-card p-0 overflow-hidden">
    @if($products->isEmpty())
        <div class="uk-empty-state py-5">
            <div class="uk-empty-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="uk-empty-title">Belum Ada Produk Ditemukan</div>
            <p class="uk-empty-desc mb-3">Mulai tambahkan katalog produk dagangan untuk memantau stok dan mencatat penjualan kasir.</p>
            <a href="{{ route('owner.products.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5">
                <i class="fas fa-plus me-1"></i>Tambah Produk Pertama
            </a>
        </div>
    @else
        <div class="table-responsive mb-0">
            <table class="table uk-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Nama Produk & SKU</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-center">Kondisi</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $products->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted border" style="width: 38px; height: 38px; flex-shrink: 0; overflow: hidden; border-radius: var(--uk-radius-xs) !important;">
                                        @if($product->image)
                                            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-box text-muted"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('owner.products.show', $product) }}" class="fw-bold text-dark text-decoration-none" style="font-size: 0.85rem;">
                                            {{ $product->name }}
                                        </a>
                                        @if($product->sku)
                                            <div class="text-muted small" style="font-size: 0.72rem;">SKU: {{ $product->sku }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($product->category)
                                    <span class="badge bg-light text-dark border px-2 py-0.5 rounded" style="font-size: 0.72rem;">{{ $product->category->name }}</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold text-dark" style="font-size: 0.88rem;">
                                {{ format_rupiah($product->selling_price) }}
                            </td>
                            <td class="text-center">
                                <span class="fw-bold {{ $product->isOutOfStock() ? 'text-danger' : ($product->isLowStock() ? 'text-warning' : 'text-dark') }}" style="font-size: 0.9rem;">
                                    {{ number_format($product->stock) }}
                                </span>
                                <span class="text-muted small">{{ $product->unit }}</span>
                            </td>
                            <td class="text-center">
                                @if($product->isOutOfStock())
                                    <span class="badge bg-danger text-white px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                                        Stok Habis
                                    </span>
                                @elseif($product->isLowStock())
                                    <span class="badge bg-warning text-dark px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                                        Menipis
                                    </span>
                                @else
                                    <span class="badge px-2 py-0.5 rounded-pill" style="background-color: rgba(51, 153, 137, 0.15); color: var(--uk-primary); font-size: 0.68rem;">
                                        Aman
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('owner.products.toggle', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm border-0 bg-transparent p-0" title="Klik untuk mengubah status aktif/nonaktif">
                                        @if($product->is_active)
                                            <span class="badge px-2 py-0.5 rounded-pill" style="background-color: rgba(51, 153, 137, 0.15); color: var(--uk-primary); font-size: 0.7rem;">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">Nonaktif</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('owner.products.show', $product) }}" class="btn btn-sm btn-uk-outline" title="Detail Produk">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>
                                    <a href="{{ route('owner.products.edit', $product) }}" class="btn btn-sm btn-uk-outline" title="Edit Produk">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <form action="{{ route('owner.products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-uk-outline text-danger"
                                                onclick="confirmDelete(event, 'Yakin ingin menghapus produk ini? Riwayat transaksi lama tetap tersimpan.')"
                                                title="Hapus Produk">
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
        @if($products->hasPages())
            <div class="p-3 px-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                </small>
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
