@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Tambah Produk Baru</h2>
        <p class="uk-page-subtitle">Lengkapi data produk untuk menambahkan item barang baru ke katalog toko Anda.</p>
    </div>
    <a href="{{ route('owner.products.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
        <i class="fas fa-arrow-left me-1.5"></i>Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="uk-card p-3 p-md-4">
            <form method="POST" action="{{ route('owner.products.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Informasi Utama Produk -->
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="fas fa-info-circle text-primary"></i>
                    <h6 class="fw-bold text-dark mb-0">Informasi Utama Produk</h6>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold small text-dark mb-1">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}"
                           placeholder="Contoh: Kopi Susu Aren, Beras Premium 5kg" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="sku" class="form-label fw-semibold small text-dark mb-1">Kode Produk / SKU</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                               id="sku" name="sku" value="{{ old('sku') }}"
                               placeholder="Contoh: BR-001 (opsional)">
                        <div class="form-text small" style="font-size: 0.72rem;">Kode unik untuk pencarian cepat di kasir.</div>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label fw-semibold small text-dark mb-1">Kategori Produk</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                            <option value="">-- Tanpa Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Harga & Satuan -->
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom mt-4">
                    <i class="fas fa-tag text-success"></i>
                    <h6 class="fw-bold text-dark mb-0">Harga & Satuan Jual</h6>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="selling_price" class="form-label fw-semibold small text-dark mb-1">Harga Jual (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="text" class="form-control @error('selling_price') is-invalid @enderror"
                                   id="selling_price" name="selling_price" value="{{ old('selling_price') }}"
                                   placeholder="0" inputmode="numeric" required>
                        </div>
                        <div class="form-text small" style="font-size: 0.72rem;">Harga yang dibayar pelanggan.</div>
                        @error('selling_price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="purchase_price" class="form-label fw-semibold small text-dark mb-1">Harga Modal / Beli (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="text" class="form-control @error('purchase_price') is-invalid @enderror"
                                   id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}"
                                   placeholder="0" inputmode="numeric">
                        </div>
                        <div class="form-text small" style="font-size: 0.72rem;">Modal per unit (untuk menghitung estimasi margin).</div>
                        @error('purchase_price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="unit" class="form-label fw-semibold small text-dark mb-1">Satuan Barang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('unit') is-invalid @enderror"
                           id="unit" name="unit" value="{{ old('unit', 'pcs') }}"
                           list="unitOptions" placeholder="Contoh: pcs, porsi, kg, botol, pack" required>
                    <datalist id="unitOptions">
                        <option value="pcs">
                        <option value="porsi">
                        <option value="kg">
                        <option value="gram">
                        <option value="botol">
                        <option value="pack">
                        <option value="dus">
                        <option value="lembar">
                        <option value="box">
                        <option value="liter">
                        <option value="paket">
                    </datalist>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pengelolaan Stok Sederhana -->
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom mt-4">
                    <i class="fas fa-cubes text-warning"></i>
                    <h6 class="fw-bold text-dark mb-0">Pengelolaan Stok</h6>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="stock" class="form-label fw-semibold small text-dark mb-1">Stok Awal Fisik <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror"
                               id="stock" name="stock" value="{{ old('stock', 0) }}"
                               min="0" required>
                        <div class="form-text small" style="font-size: 0.72rem;">Jumlah stok barang yang tersedia saat ini di toko.</div>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="min_stock" class="form-label fw-semibold small text-dark mb-1">Batas Minimum Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                               id="min_stock" name="min_stock" value="{{ old('min_stock', 5) }}"
                               min="0" required>
                        <div class="form-text small" style="font-size: 0.72rem;">Peringatan "Stok Menipis" aktif jika sisa stok &le; angka ini.</div>
                        @error('min_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Keterangan & Foto -->
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom mt-4">
                    <i class="fas fa-image text-secondary"></i>
                    <h6 class="fw-bold text-dark mb-0">Keterangan & Foto Produk</h6>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold small text-dark mb-1">Deskripsi Produk (Opsional)</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="2"
                              placeholder="Spesifikasi, rasa, ukuran, atau catatan produk...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label fw-semibold small text-dark mb-1">Foto Produk (Opsional)</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                           id="image" name="image" accept="image/*">
                    <div class="form-text small" style="font-size: 0.72rem;">Format JPG, PNG, atau WebP (Maksimal 2MB).</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('owner.products.index') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3">
                        <i class="fas fa-times me-1.5"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-sm btn-uk-primary rounded-pill px-4 shadow-xs fw-bold">
                        <i class="fas fa-save me-1.5"></i>Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Informasi Petunjuk Samping -->
    <div class="col-lg-4">
        <div class="uk-card p-3 p-md-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="fas fa-lightbulb text-warning"></i>
                <h6 class="fw-bold mb-0 text-dark">Panduan Inventaris</h6>
            </div>
            <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8; font-size: 0.8rem;">
                <li><strong>Fleksibel:</strong> Cocok untuk warung kopi, ritel kelontong, pakaian, maupun kuliner.</li>
                <li><strong>Stok Otomatis:</strong> Nilai stok awal langsung tercatat ke riwayat mutasi stok.</li>
                <li><strong>Peringatan Dini:</strong> Status <span class="badge bg-warning text-dark px-1.5 py-0.5 rounded">Menipis</span> otomatis muncul saat stok mencapai batas minimum.</li>
                <li><strong>Sinkronisasi Kasir:</strong> Penjualan kasir otomatis memotong stok barang secara realtime.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
