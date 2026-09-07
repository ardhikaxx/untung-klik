@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Detail Produk</h4>
        <p class="text-muted mb-0">Informasi lengkap spesifikasi dan riwayat pergerakan stok barang</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('owner.stock.adjust', ['product_id' => $product->id]) }}" class="btn btn-outline-warning">
            <i class="fas fa-sliders-h me-1"></i>Penyesuaian Stok
        </a>
        <a href="{{ route('owner.products.edit', $product) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Edit Produk
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Kartu Info Produk -->
    <div class="col-lg-5">
        <div class="card border shadow-sm mb-4">
            @if($product->image)
                <div class="text-center p-3 bg-light border-bottom">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 220px; object-fit: contain;">
                </div>
            @endif
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-light text-dark border">{{ $product->category ? $product->category->name : 'Tanpa Kategori' }}</span>
                    @if($product->is_active)
                        <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Nonaktif</span>
                    @endif
                </div>

                <h4 class="fw-bold text-dark mb-1">{{ $product->name }}</h4>
                @if($product->sku)
                    <p class="text-muted small mb-3">Kode / SKU: <code>{{ $product->sku }}</code></p>
                @endif

                @if($product->description)
                    <p class="text-muted small border-top pt-3 mb-3">{{ $product->description }}</p>
                @endif

                <!-- Harga & Margin -->
                <div class="p-3 bg-light rounded-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Harga Jual:</span>
                        <span class="fw-bold fs-5 text-success">{{ format_rupiah($product->selling_price) }}</span>
                    </div>
                    @if($product->purchase_price)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Harga Modal:</span>
                            <span class="fw-semibold text-muted">{{ format_rupiah($product->purchase_price) }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2">
                            <span class="text-muted small">Estimasi Margin:</span>
                            <span class="fw-bold text-primary">
                                {{ format_rupiah($product->selling_price - $product->purchase_price) }}
                                ({{ round((($product->selling_price - $product->purchase_price) / $product->selling_price) * 100) }}%)
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Kondisi Stok -->
                <div class="border rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Status Stok:</span>
                        @if($product->isOutOfStock())
                            <span class="badge bg-danger text-white"><i class="fas fa-times-circle me-1"></i>Stok Habis</span>
                        @elseif($product->isLowStock())
                            <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i>Stok Menipis</span>
                        @else
                            <span class="badge bg-success text-white"><i class="fas fa-check-circle me-1"></i>Stok Aman</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Stok Tersedia:</span>
                        <span class="fw-bold fs-4 text-dark">{{ number_format($product->stock) }} {{ $product->unit }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Batas Minimum:</span>
                        <span class="text-muted">{{ number_format($product->min_stock) }} {{ $product->unit }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Histori Mutasi Stok Produk Ini -->
    <div class="col-lg-7">
        <div class="card border shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-history text-muted me-2"></i>Histori Pergerakan Stok
                </h6>
                <span class="text-muted small">10 Catatan Terakhir</span>
            </div>
            <div class="card-body p-0">
                @if($product->stockMovements->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-cubes fa-2x text-muted opacity-50 mb-2"></i>
                        <p class="text-muted small mb-0">Belum ada catatan mutasi stok untuk produk ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Waktu</th>
                                    <th>Aktivitas</th>
                                    <th class="text-center">Perubahan</th>
                                    <th class="text-center">Sisa Stok</th>
                                    <th>Keterangan / Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->stockMovements as $move)
                                    <tr>
                                        <td class="ps-3 small text-muted">
                                            {{ $move->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $move->type_badge_class }}">
                                                {{ $move->type_label }}
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold {{ $move->quantity > 0 ? 'text-success' : ($move->quantity < 0 ? 'text-danger' : 'text-muted') }}">
                                            {{ $move->quantity > 0 ? '+' : '' }}{{ number_format($move->quantity) }}
                                        </td>
                                        <td class="text-center fw-semibold text-dark">
                                            {{ number_format($move->stock_after) }}
                                        </td>
                                        <td class="small">
                                            <div class="text-dark">{{ $move->notes ?: '-' }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">Oleh: {{ $move->user ? $move->user->name : 'Sistem' }}</div>
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
