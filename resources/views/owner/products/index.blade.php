@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Katalog Produk & Stok</h4>
        <p class="text-muted mb-0">Kelola daftar barang, harga jual, dan pantau ketersediaan stok</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('owner.stock.adjust') }}" class="btn btn-outline-warning">
            <i class="fas fa-sliders-h me-1"></i>Penyesuaian Stok
        </a>
        <a href="{{ route('owner.product-categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-layer-group me-1"></i>Kategori
        </a>
        <a href="{{ route('owner.products.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i>Tambah Produk
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-primary-subtle text-primary rounded-3">
                    <i class="fas fa-boxes fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Total Produk</div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($totalProducts) }}</div>
                    <div class="small text-muted">Item terdaftar</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-warning-subtle text-warning rounded-3">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Stok Menipis</div>
                    <div class="fs-4 fw-bold text-warning">{{ number_format($lowStockCount) }}</div>
                    <div class="small text-muted">&le; batas minimum stok</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card border shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-danger-subtle text-danger rounded-3">
                    <i class="fas fa-times-circle fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Stok Habis</div>
                    <div class="fs-4 fw-bold text-danger">{{ number_format($outOfStockCount) }}</div>
                    <div class="small text-muted">Perlu segera restock</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Box -->
<div class="card border shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('owner.products.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label fw-semibold small">Cari Produk</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Nama produk atau SKU...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="category_id" class="form-label fw-semibold small">Kategori</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="stock_status" class="form-label fw-semibold small">Kondisi Stok</label>
                    <select class="form-select" id="stock_status" name="stock_status">
                        <option value="">Semua Stok</option>
                        <option value="safe" {{ request('stock_status') === 'safe' ? 'selected' : '' }}>Stok Aman</option>
                        <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label fw-semibold small">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Product Table -->
<div class="card border shadow-sm">
    <div class="card-body p-0">
        @if($products->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-box-open fa-3x text-muted opacity-50"></i>
                </div>
                <h6 class="text-muted fw-bold">Belum Ada Produk Ditemukan</h6>
                <p class="text-muted small mb-3">Mulai tambahkan katalog produk dagangan untuk memantau stok dan mencatat penjualan.</p>
                <a href="{{ route('owner.products.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>Tambah Produk Pertama
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">No</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-center">Sisa Stok</th>
                            <th class="text-center">Kondisi Stok</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-3" style="width: 170px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $index => $product)
                            <tr>
                                <td class="ps-3 text-muted">{{ $products->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width: 38px; height: 38px; flex-shrink: 0; overflow: hidden;">
                                            @if($product->image)
                                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fas fa-box"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('owner.products.show', $product) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $product->name }}
                                            </a>
                                            @if($product->sku)
                                                <div class="text-muted small">SKU: {{ $product->sku }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($product->category)
                                        <span class="badge bg-light text-dark border">{{ $product->category->name }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold text-dark">
                                    {{ format_rupiah($product->selling_price) }}
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold fs-6 {{ $product->isOutOfStock() ? 'text-danger' : ($product->isLowStock() ? 'text-warning' : 'text-dark') }}">
                                        {{ number_format($product->stock) }}
                                    </span>
                                    <span class="text-muted small">{{ $product->unit }}</span>
                                </td>
                                <td class="text-center">
                                    @if($product->isOutOfStock())
                                        <span class="badge bg-danger text-white px-2 py-1">
                                            <i class="fas fa-times-circle me-1"></i>Stok Habis
                                        </span>
                                    @elseif($product->isLowStock())
                                        <span class="badge bg-warning text-dark px-2 py-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Stok Menipis
                                        </span>
                                    @else
                                        <span class="badge bg-success text-white px-2 py-1">
                                            <i class="fas fa-check-circle me-1"></i>Stok Aman
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('owner.products.toggle', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm border-0 bg-transparent p-0" title="Klik untuk mengubah status">
                                            @if($product->is_active)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Nonaktif</span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center pe-3">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('owner.products.show', $product) }}" class="btn btn-sm btn-outline-info" title="Detail Produk">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('owner.products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Edit Produk">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('owner.products.destroy', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete(event, 'Yakin ingin menghapus produk ini?')"
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
                <div class="p-3 border-top">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
