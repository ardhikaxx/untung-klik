@extends('layouts.app')

@section('title', 'Edit Produk - ' . $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Produk</h4>
        <p class="text-muted mb-0">Perbarui informasi harga, kategori, atau status produk</p>
    </div>
    <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Produk -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>Informasi Utama Produk
                    </h6>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="sku" class="form-label fw-semibold">Kode Produk / SKU</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold">Kategori Produk</label>
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
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
                        <i class="fas fa-tag text-success me-2"></i>Harga & Satuan
                    </h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="selling_price" class="form-label fw-semibold">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="text" class="form-control form-control-lg @error('selling_price') is-invalid @enderror"
                                       id="selling_price" name="selling_price"
                                       value="{{ old('selling_price', number_format($product->selling_price, 0, ',', '.')) }}"
                                       required>
                            </div>
                            @error('selling_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="purchase_price" class="form-label fw-semibold">Harga Modal / Beli (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="text" class="form-control form-control-lg @error('purchase_price') is-invalid @enderror"
                                       id="purchase_price" name="purchase_price"
                                       value="{{ old('purchase_price', $product->purchase_price ? number_format($product->purchase_price, 0, ',', '.') : '') }}">
                            </div>
                            @error('purchase_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="unit" class="form-label fw-semibold">Satuan Barang <span class="text-danger">*</span></label>
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
                                <option value="box">
                            </datalist>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="min_stock" class="form-label fw-semibold">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                                   id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}"
                                   min="0" required>
                            @error('min_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status Stok Saat Ini -->
                    <div class="p-3 bg-light rounded-3 mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="text-muted small">Stok Fisik Saat Ini:</span>
                            <span class="fw-bold fs-5 ms-2 text-dark">{{ number_format($product->stock) }} {{ $product->unit }}</span>
                        </div>
                        <a href="{{ route('owner.stock.adjust', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-sliders-h me-1"></i>Ubah Jumlah Stok di Menu Penyesuaian
                        </a>
                    </div>

                    <!-- Status & Foto -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
                        <i class="fas fa-toggle-on text-primary me-2"></i>Status & Foto
                    </h6>

                    <div class="mb-3">
                        <label for="is_active" class="form-label fw-semibold">Status Produk</label>
                        <select class="form-select" id="is_active" name="is_active">
                            <option value="1" {{ old('is_active', $product->is_active) ? 'selected' : '' }}>Aktif (Dapat dijual)</option>
                            <option value="0" {{ ! old('is_active', $product->is_active) ? 'selected' : '' }}>Nonaktif (Disembunyikan dari penjualan)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Deskripsi Produk</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label fw-semibold">Foto Produk</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded border" style="max-height: 120px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*">
                        <div class="form-text small">Unggah gambar baru untuk mengganti gambar lama.</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
