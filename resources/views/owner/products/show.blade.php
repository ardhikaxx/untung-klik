@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Detail Produk: {{ $product->name }}</h2>
        <p class="uk-page-subtitle">Informasi spesifikasi lengkap produk, margin harga jual, dan audit riwayat mutasi stok.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('owner.products.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
            <i class="fas fa-arrow-left me-1.5"></i>Kembali
        </a>
        <a href="{{ route('owner.stock.adjust', ['product_id' => $product->id]) }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3">
            <i class="fas fa-sliders-h me-1.5"></i>Penyesuaian Stok
        </a>
        <a href="{{ route('owner.products.edit', $product) }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 shadow-xs fw-bold">
            <i class="fas fa-edit me-1.5"></i>Edit Produk
        </a>
        <form action="{{ route('owner.products.destroy', $product) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-sm btn-uk-outline text-danger rounded-pill px-3"
                    onclick="confirmDelete(event, 'Yakin ingin menghapus produk ini? Riwayat transaksi lama tetap tersimpan.')"
                    title="Hapus Produk">
                <i class="fas fa-trash-alt me-1.5"></i>Hapus Produk
            </button>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Spesifikasi Produk -->
    <div class="col-lg-5">
        <div class="uk-card mb-4 p-0 overflow-hidden">
            @if($product->image)
                <div class="text-center p-3 bg-light border-bottom">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 220px; object-fit: contain;">
                </div>
            @endif
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge badge-uk-accent text-dark fw-medium">{{ $product->category ? $product->category->name : 'Tanpa Kategori' }}</span>
                    @if($product->is_active)
                        <span class="badge badge-uk-primary">Aktif di Kasir</span>
                    @else
                        <span class="badge badge-uk-dark">Nonaktif</span>
                    @endif
                </div>

                <h4 class="fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">{{ $product->name }}</h4>
                @if($product->sku)
                    <p class="text-muted small mb-3">Kode / SKU: <code class="fw-semibold">{{ $product->sku }}</code></p>
                @endif

                @if($product->description)
                    <p class="text-muted small border-top pt-3 mb-3" style="line-height: 1.6;">{{ $product->description }}</p>
                @endif

                <!-- Harga Jual & Margin -->
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Harga Jual:</span>
                        <span class="fw-bold fs-5 text-primary">{{ format_rupiah($product->selling_price) }}</span>
                    </div>
                    @if($product->purchase_price)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Harga Modal Beli:</span>
                            <span class="fw-semibold text-dark">{{ format_rupiah($product->purchase_price) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top pt-2">
                            <span class="text-muted small">Estimasi Margin:</span>
                            <span class="fw-bold text-dark">
                                +{{ format_rupiah($product->selling_price - $product->purchase_price) }}
                                <span class="badge badge-uk-accent text-dark ms-1">
                                    {{ round((($product->selling_price - $product->purchase_price) / $product->selling_price) * 100) }}%
                                </span>
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Kondisi Stok Fisik -->
                <div class="border rounded-3 p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Status Ketersediaan:</span>
                        @if($product->isOutOfStock())
                            <span class="badge-stok-out">Stok Habis</span>
                        @elseif($product->isLowStock())
                            <span class="badge-stok-low">Stok Menipis</span>
                        @else
                            <span class="badge-stok-safe">Stok Aman</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Stok Fisik Tersedia:</span>
                        <span class="fw-bold fs-5 text-dark">{{ number_format($product->stock) }} {{ $product->unit }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Batas Minimum Peringatan:</span>
                        <span class="text-muted small">{{ number_format($product->min_stock) }} {{ $product->unit }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Histori Mutasi Stok Produk -->
    <div class="col-lg-7">
        <div class="uk-card p-0 overflow-hidden">
            <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-history text-primary"></i>
                    <h6 class="fw-bold mb-0 text-dark">Histori Pergerakan Stok</h6>
                </div>
                <span class="text-muted small">10 Catatan Terakhir</span>
            </div>
            <div class="p-0">
                @if($product->stockMovements->isEmpty())
                    <div class="uk-empty-state py-5">
                        <div class="uk-empty-icon">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div class="uk-empty-title">Belum Ada Catatan Mutasi</div>
                        <p class="uk-empty-desc mb-0">Belum ada aktivitas penambahan, pengurangan, atau penjualan kasir untuk produk ini.</p>
                    </div>
                @else
                    <div class="table-responsive mb-0">
                        <table class="table uk-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Waktu</th>
                                    <th>Aktivitas</th>
                                    <th class="text-center">Perubahan</th>
                                    <th class="text-center">Sisa Stok</th>
                                    <th class="pe-4">Keterangan / User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->stockMovements as $move)
                                    <tr>
                                        <td class="ps-4 text-muted small">
                                            {{ $move->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            @if($move->type === 'in')
                                                <span class="badge badge-uk-primary"><i class="fas fa-arrow-down me-1"></i>Masuk</span>
                                            @elseif($move->type === 'out' || $move->type === 'sale')
                                                <span class="badge badge-uk-accent text-dark"><i class="fas fa-shopping-cart me-1"></i>Penjualan</span>
                                            @elseif($move->type === 'damaged')
                                                <span class="badge badge-uk-dark"><i class="fas fa-heart-crack me-1"></i>Rusak</span>
                                            @elseif($move->type === 'lost')
                                                <span class="badge badge-uk-dark"><i class="fas fa-search-minus me-1"></i>Hilang</span>
                                            @else
                                                <span class="badge badge-uk-secondary"><i class="fas fa-sliders-h me-1"></i>Koreksi</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold {{ $move->quantity > 0 ? 'text-success' : ($move->quantity < 0 ? 'text-dark' : 'text-muted') }}" style="font-size: 0.88rem;">
                                            {{ $move->quantity > 0 ? '+' : '' }}{{ number_format($move->quantity) }}
                                        </td>
                                        <td class="text-center fw-semibold text-dark" style="font-size: 0.88rem;">
                                            {{ number_format($move->stock_after) }}
                                        </td>
                                        <td class="pe-4 small">
                                            <div class="text-dark">{{ $move->notes ?: '-' }}</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Oleh: {{ $move->user ? $move->user->name : 'Sistem' }}</div>
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
