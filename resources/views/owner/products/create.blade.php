@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Tambah Produk Baru</h4>
        <p class="text-muted mb-0">Lengkapi formulir untuk menambahkan produk ke katalog toko Anda</p>
    </div>
    <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Informasi Produk -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>Informasi Utama Produk
                    </h6>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}"
                               placeholder="Contoh: Beras Ramos 5kg, Kopi Susu Aren, Kaos Polos L" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="sku" class="form-label fw-semibold">Kode Produk / SKU</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   id="sku" name="sku" value="{{ old('sku') }}"
                                   placeholder="Contoh: BR-001 (opsional)">
                            <div class="form-text small">Kode unik internal untuk memudahkan pencarian.</div>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold">Kategori Produk</label>
                            <div class="input-group">
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                    <option value="">-- Tanpa Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Harga & Satuan -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
                        <i class="fas fa-tag text-success me-2"></i>Harga & Satuan
                    </h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="selling_price" class="form-label fw-semibold">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="text" class="form-control form-control-lg @error('selling_price') is-invalid @enderror"
                                       id="selling_price" name="selling_price" value="{{ old('selling_price') }}"
                                       placeholder="0" inputmode="numeric" required>
                            </div>
                            <div class="form-text small">Harga yang dibayar oleh pelanggan saat transaksi.</div>
                            @error('selling_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="purchase_price" class="form-label fw-semibold">Harga Modal / Beli (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="text" class="form-control form-control-lg @error('purchase_price') is-invalid @enderror"
                                       id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}"
                                       placeholder="0" inputmode="numeric">
                            </div>
                            <div class="form-text small">Biaya modal beli per unit (opsional untuk hitung margin).</div>
                            @error('purchase_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="unit" class="form-label fw-semibold">Satuan Barang <span class="text-danger">*</span></label>
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

                    <!-- Pengelolaan Stok -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
                        <i class="fas fa-cubes text-warning me-2"></i>Pengelolaan Stok Sederhana
                    </h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="stock" class="form-label fw-semibold">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                   id="stock" name="stock" value="{{ old('stock', 0) }}"
                                   min="0" required>
                            <div class="form-text small">Jumlah fisik barang yang tersedia saat ini di toko.</div>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="min_stock" class="form-label fw-semibold">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                                   id="min_stock" name="min_stock" value="{{ old('min_stock', 5) }}"
                                   min="0" required>
                            <div class="form-text small">Sistem memberi peringatan jika stok &le; angka ini.</div>
                            @error('min_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tambahan -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
                        <i class="fas fa-file-alt text-secondary me-2"></i>Keterangan & Foto (Opsional)
                    </h6>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Deskripsi Produk</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3"
                                  placeholder="Catatan tambahan spesifikasi atau detail produk...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label fw-semibold">Foto Produk</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*">
                        <div class="form-text small">Format JPG, PNG, atau WebP (Maksimal 2MB).</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save me-1"></i>Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Informasi Petunjuk Samping -->
    <div class="col-lg-4">
        <div class="card border shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb text-warning me-2"></i>Panduan Stok</h6>
                <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8;">
                    <li><strong>Fleksibel:</strong> Berlaku untuk warung, toko kelontong, pakaian, kuliner, maupun usaha rumahan.</li>
                    <li><strong>Stok Awal:</strong> Nilai stok awal akan otomatis tercatat di histori mutasi stok.</li>
                    <li><strong>Peringatan Otomatis:</strong> Indikator <span class="badge bg-warning text-dark">Stok Menipis</span> muncul jika sisa stok mencapai batas minimum.</li>
                    <li><strong>Penjualan Terintegrasi:</strong> Setiap kali produk terjual di menu Penjualan Harian, stok akan otomatis terpotong.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
