@extends('layouts.app')

@section('title', 'Penyesuaian Stok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Penyesuaian Stok</h4>
        <p class="text-muted mb-0">Catat penambahan restock, barang rusak, barang hilang, atau koreksi hitung fisik</p>
    </div>
    <a href="{{ route('owner.stock.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.stock.adjust.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="product_id" class="form-label fw-semibold">Pilih Produk <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg @error('product_id') is-invalid @enderror"
                                id="product_id" name="product_id" required onchange="updateProductInfo()">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    data-stock="{{ $product->stock }}"
                                    data-unit="{{ $product->unit }}"
                                    data-min="{{ $product->min_stock }}"
                                    {{ (old('product_id', request('product_id')) == $product->id) ? 'selected' : '' }}>
                                    {{ $product->name }} (Sisa: {{ $product->stock }} {{ $product->unit }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Info Box Produk Terpilih -->
                    <div id="productInfoBox" class="p-3 bg-light rounded-3 mb-4 d-none">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <span class="text-muted small">Stok Saat Ini:</span>
                                <div class="fw-bold fs-4 text-dark" id="displayCurrentStock">0</div>
                            </div>
                            <div class="col-6">
                                <span class="text-muted small">Batas Minimum:</span>
                                <div class="fw-semibold text-muted" id="displayMinStock">0</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tipe Penyesuaian <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="form-check p-3 border rounded-3 h-100">
                                    <input class="form-check-input" type="radio" name="type" id="typeIn" value="in"
                                           {{ old('type', 'in') === 'in' ? 'checked' : '' }} onchange="updateLabel()">
                                    <label class="form-check-label fw-semibold text-success ms-1" for="typeIn">
                                        <i class="fas fa-plus-circle me-1"></i>Penambahan Stok
                                        <div class="text-muted fw-normal small">Restock dari supplier atau barang baru masuk</div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check p-3 border rounded-3 h-100">
                                    <input class="form-check-input" type="radio" name="type" id="typeDamaged" value="damaged"
                                           {{ old('type') === 'damaged' ? 'checked' : '' }} onchange="updateLabel()">
                                    <label class="form-check-label fw-semibold text-danger ms-1" for="typeDamaged">
                                        <i class="fas fa-heart-broken me-1"></i>Barang Rusak / Cacat
                                        <div class="text-muted fw-normal small">Barang rusak, kadaluarsa, pecah, atau cacat</div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check p-3 border rounded-3 h-100">
                                    <input class="form-check-input" type="radio" name="type" id="typeLost" value="lost"
                                           {{ old('type') === 'lost' ? 'checked' : '' }} onchange="updateLabel()">
                                    <label class="form-check-label fw-semibold text-danger ms-1" for="typeLost">
                                        <i class="fas fa-search-minus me-1"></i>Barang Hilang / Kurang
                                        <div class="text-muted fw-normal small">Selisih barang hilang saat opname fisik</div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check p-3 border rounded-3 h-100">
                                    <input class="form-check-input" type="radio" name="type" id="typeCorrection" value="correction"
                                           {{ old('type') === 'correction' ? 'checked' : '' }} onchange="updateLabel()">
                                    <label class="form-check-label fw-semibold text-primary ms-1" for="typeCorrection">
                                        <i class="fas fa-equals me-1"></i>Koreksi Total Stok
                                        <div class="text-muted fw-normal small">Atur langsung ke angka fisik yang sebenarnya</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold" id="amountLabel">
                            Jumlah Barang <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control form-control-lg @error('amount') is-invalid @enderror"
                               id="amount" name="amount" value="{{ old('amount', 1) }}" min="0" required>
                        <div class="form-text small" id="amountHelp">Masukkan jumlah barang yang ingin ditambahkan ke stok toko.</div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label fw-semibold">Alasan / Keterangan Penyesuaian <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3"
                                  placeholder="Contoh: Belanja kulakan pasar baru, 2 botol pecah saat bongkar muat, opname mingguan..." required>{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('owner.stock.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-warning px-4 fw-semibold">
                            <i class="fas fa-save me-1"></i>Simpan Penyesuaian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Catatan Audit Trail</h6>
                <p class="text-muted small">
                    Setiap penyesuaian stok yang Anda simpan akan mencatat:
                </p>
                <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8;">
                    <li>Waktu transaksi otomatis tersimpan</li>
                    <li>Nama petugas / pengguna yang melakukan perubahan</li>
                    <li>Jumlah stok sebelum dan sesudah perubahan</li>
                    <li>Alasan perubahan untuk pertanggungjawaban usaha</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateProductInfo() {
        const select = document.getElementById('product_id');
        const selected = select.options[select.selectedIndex];
        const infoBox = document.getElementById('productInfoBox');

        if (selected && selected.value) {
            const stock = selected.getAttribute('data-stock');
            const unit = selected.getAttribute('data-unit');
            const min = selected.getAttribute('data-min');

            document.getElementById('displayCurrentStock').textContent = stock + ' ' + unit;
            document.getElementById('displayMinStock').textContent = min + ' ' + unit;
            infoBox.classList.remove('d-none');
        } else {
            infoBox.classList.add('d-none');
        }
    }

    function updateLabel() {
        const type = document.querySelector('input[name="type"]:checked').value;
        const label = document.getElementById('amountLabel');
        const help = document.getElementById('amountHelp');

        if (type === 'in') {
            label.innerHTML = 'Jumlah Penambahan Stok <span class="text-danger">*</span>';
            help.textContent = 'Masukkan jumlah barang yang ingin ditambahkan ke stok saat ini.';
        } else if (type === 'damaged') {
            label.innerHTML = 'Jumlah Barang Rusak <span class="text-danger">*</span>';
            help.textContent = 'Jumlah barang yang rusak dan akan dipotong dari stok.';
        } else if (type === 'lost') {
            label.innerHTML = 'Jumlah Barang Hilang <span class="text-danger">*</span>';
            help.textContent = 'Jumlah barang yang hilang dan akan dipotong dari stok.';
        } else if (type === 'correction') {
            label.innerHTML = 'Jumlah Stok Fisik Riil <span class="text-danger">*</span>';
            help.textContent = 'Stok saat ini akan disesuaikan menjadi persis sama dengan angka ini.';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateProductInfo();
        updateLabel();
    });
</script>
@endpush
@endsection
