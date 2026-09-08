@extends('layouts.app')

@section('title', 'Edit Produk - ' . $product->name)

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Edit Produk: {{ $product->name }}</h2>
        <p class="uk-page-subtitle">Perbarui data katalog produk, harga jual, margin usaha, atau batas minimum peringatan stok.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('owner.products.show', $product) }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
            <i class="fas fa-eye me-1.5"></i>Lihat Detail
        </a>
        <a href="{{ route('owner.products.index') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left me-1.5"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Form Utama -->
    <div class="col-lg-8">
        <div class="uk-card p-3 p-md-4">
            <form method="POST" action="{{ route('owner.products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Informasi Utama Produk -->
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="fas fa-info-circle text-primary"></i>
                    <h6 class="fw-bold text-dark mb-0">Informasi Utama Produk</h6>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold small text-dark mb-1">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $product->name) }}"
                           placeholder="Contoh: Kopi Susu Gula Aren" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="sku" class="form-label fw-semibold small text-dark mb-1">Kode Produk / SKU</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                               id="sku" name="sku" value="{{ old('sku', $product->sku) }}"
                               placeholder="Contoh: BR-001">
                        <div class="form-text small" style="font-size: 0.72rem;">Kode unik untuk barcode atau pencarian cepat di kasir.</div>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label fw-semibold small text-dark mb-1">Kategori Produk</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                            <option value="">-- Tanpa Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
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
                    <i class="fas fa-tag text-primary"></i>
                    <h6 class="fw-bold text-dark mb-0">Harga & Satuan Jual</h6>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="selling_price" class="form-label fw-semibold small text-dark mb-1">Harga Jual (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-dark fw-medium">Rp</span>
                            <input type="text" class="form-control @error('selling_price') is-invalid @enderror"
                                   id="selling_price" name="selling_price"
                                   value="{{ old('selling_price', number_format($product->selling_price, 0, ',', '.')) }}"
                                   required>
                        </div>
                        <div class="form-text small" style="font-size: 0.72rem;">Harga yang dibayar oleh pelanggan saat checkout kasir.</div>
                        @error('selling_price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="purchase_price" class="form-label fw-semibold small text-dark mb-1">Harga Modal / Beli (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-dark fw-medium">Rp</span>
                            <input type="text" class="form-control @error('purchase_price') is-invalid @enderror"
                                   id="purchase_price" name="purchase_price"
                                   value="{{ old('purchase_price', $product->purchase_price ? number_format($product->purchase_price, 0, ',', '.') : '') }}">
                        </div>
                        <div class="form-text small" style="font-size: 0.72rem;">Harga beli/kulakan modal per unit produk.</div>
                        @error('purchase_price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="unit" class="form-label fw-semibold small text-dark mb-1">Satuan Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('unit') is-invalid @enderror"
                               id="unit" name="unit" value="{{ old('unit', $product->unit) }}"
                               list="unitOptions" required>
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
                    <div class="col-md-6">
                        <label for="min_stock" class="form-label fw-semibold small text-dark mb-1">Batas Minimum Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                               id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}"
                               min="0" required>
                        <div class="form-text small" style="font-size: 0.72rem;">Peringatan "Stok Menipis" muncul jika sisa stok &le; angka ini.</div>
                        @error('min_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Status Keaktifan & Deskripsi -->
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom mt-4">
                    <i class="fas fa-toggle-on text-primary"></i>
                    <h6 class="fw-bold text-dark mb-0">Status Keaktifan & Deskripsi</h6>
                </div>

                <div class="mb-3">
                    <label for="is_active" class="form-label fw-semibold small text-dark mb-1">Status Visibilitas di Kasir</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="1" {{ old('is_active', $product->is_active) ? 'selected' : '' }}>Aktif (Dapat dipilih dan dijual di sistem kasir)</option>
                        <option value="0" {{ ! old('is_active', $product->is_active) ? 'selected' : '' }}>Nonaktif (Disembunyikan dari katalog kasir sementara)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold small text-dark mb-1">Deskripsi Produk (Opsional)</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="2"
                              placeholder="Keterangan rasa, varian, kemasan, atau catatan khusus...">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Foto Produk -->
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom mt-4">
                    <i class="fas fa-image text-primary"></i>
                    <h6 class="fw-bold text-dark mb-0">Foto Produk</h6>
                </div>

                <div class="mb-4">
                    @if($product->image)
                        <div class="p-3 bg-light rounded-3 border mb-3 d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded border" style="width: 70px; height: 70px; object-fit: cover;">
                            <div>
                                <span class="fw-semibold text-dark small d-block mb-1">Foto Produk Saat Ini</span>
                                <span class="text-muted small" style="font-size: 0.72rem;">Unggah gambar baru di bawah ini jika ingin mengganti foto saat ini.</span>
                            </div>
                        </div>
                    @endif
                    <label for="image" class="form-label fw-semibold small text-dark mb-1">Pilih File Foto Baru</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                           id="image" name="image" accept="image/*">
                    <div class="form-text small" style="font-size: 0.72rem;">Format JPG, PNG, atau WebP (Maksimal 2MB). Kosongkan jika tidak ingin mengubah foto.</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('owner.products.index') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3">Batal</a>
                    <button type="submit" class="btn btn-sm btn-uk-primary rounded-pill px-4 shadow-xs fw-bold">
                        <i class="fas fa-save me-1.5"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Rujukan Stok & Panduan -->
    <div class="col-lg-4">
        <!-- Kartu Rujukan Stok Fisik -->
        <div class="uk-card p-3 p-md-4 mb-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="fas fa-cubes text-primary"></i>
                <h6 class="fw-bold mb-0 text-dark">Status Stok Fisik</h6>
            </div>
            <div class="p-3 rounded-3 border mb-3 bg-light text-center">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Stok Saat Ini</small>
                <div class="fw-bold fs-3 text-dark mt-1">
                    {{ number_format($product->stock) }} <span class="fs-6 fw-normal text-muted">{{ $product->unit }}</span>
                </div>
                <div class="mt-2">
                    @if($product->isOutOfStock())
                        <span class="badge-stok-out">Stok Habis</span>
                    @elseif($product->isLowStock())
                        <span class="badge-stok-low">Stok Menipis</span>
                    @else
                        <span class="badge-stok-safe">Stok Tersedia</span>
                    @endif
                </div>
            </div>
            <p class="text-muted small mb-3" style="font-size: 0.78rem; line-height: 1.6;">
                Untuk menjaga akurasi audit buku kas dan inventaris, stok fisik tidak diedit manual di sini.
            </p>
            <a href="{{ route('owner.stock.adjust', ['product_id' => $product->id]) }}" class="btn btn-sm btn-uk-outline w-100 rounded-pill">
                <i class="fas fa-sliders-h me-1.5"></i>Sesuaikan Stok di Menu Mutasi &rarr;
            </a>
        </div>

        <!-- Kartu Panduan SaaS -->
        <div class="uk-card p-3 p-md-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="fas fa-lightbulb text-primary"></i>
                <h6 class="fw-bold mb-0 text-dark">Panduan Katalog</h6>
            </div>
            <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8; font-size: 0.8rem;">
                <li><strong>Satuan Baku:</strong> Pastikan satuan konsisten (misal: <em>pcs</em>, <em>kg</em>) untuk mempermudah perhitungan stok.</li>
                <li><strong>Estimasi Margin:</strong> Margin keuntungan otomatis dihitung berdasarkan selisih harga jual dan harga modal beli.</li>
                <li><strong>Riwayat Mutasi:</strong> Semua penjualan kasir dan penyesuaian opname tersimpan rapi di tab audit stok.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
