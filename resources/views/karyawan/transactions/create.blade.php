@extends('layouts.app')

@section('title', 'Catat Kas Masuk')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">
        <!-- Page Header -->
        <div class="uk-page-header mb-4">
            <div>
                <h2 class="uk-page-title">Catat Kas Masuk Manual</h2>
                <p class="uk-page-subtitle">Isi penerimaan kas non-produk seperti fee jasa, servis, tip pelanggan, dll.</p>
            </div>
            <a href="{{ route('karyawan.transactions.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
                <i class="fas fa-arrow-left me-1.5"></i>Kembali
            </a>
        </div>

        <div class="card uk-card border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('karyawan.transactions.store') }}">
                    @csrf

                    <!-- Nominal Uang Masuk Prominen -->
                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold small text-dark mb-1">
                            Jumlah Kas Masuk (Rp) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text fw-bold bg-light text-dark border-end-0">Rp</span>
                            <input
                                type="number"
                                class="form-control form-control-lg border-start-0 ps-0 fw-bold text-dark @error('amount') is-invalid @enderror"
                                id="amount"
                                name="amount"
                                value="{{ old('amount') }}"
                                placeholder="0"
                                min="0"
                                required
                                autofocus
                                style="font-size: 1.6rem;"
                            >
                        </div>
                        <div class="form-text small" style="font-size: 0.72rem;">Nominal kas masuk riil yang diterima.</div>
                        @error('amount')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="transaction_date" class="form-label fw-semibold small text-dark mb-1">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input
                                type="date"
                                class="form-control @error('transaction_date') is-invalid @enderror"
                                id="transaction_date"
                                name="transaction_date"
                                value="{{ old('transaction_date', date('Y-m-d')) }}"
                                required
                            >
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold small text-dark mb-1">Kategori Kas Masuk <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id"
                                name="category_id"
                                required
                            >
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="source" class="form-label fw-semibold small text-dark mb-1">Sumber / Dari Siapa</label>
                            <input
                                type="text"
                                class="form-control @error('source') is-invalid @enderror"
                                id="source"
                                name="source"
                                value="{{ old('source') }}"
                                placeholder="Contoh: Jasa Servis, Ongkir, Tip Pelanggan"
                            >
                            @error('source')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="payment_method" class="form-label fw-semibold small text-dark mb-1">Metode Penerimaan</label>
                            <select
                                class="form-select @error('payment_method') is-invalid @enderror"
                                id="payment_method"
                                name="payment_method"
                            >
                                <option value="tunai" {{ old('payment_method', 'tunai') === 'tunai' ? 'selected' : '' }}>Tunai / Cash</option>
                                <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="lainnya" {{ old('payment_method') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold small text-dark mb-1">Catatan / Keterangan Rinci (Opsional)</label>
                        <textarea
                            class="form-control @error('description') is-invalid @enderror"
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Catatan tambahan mengenai penerimaan kas ini..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('karyawan.transactions.index') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3">
                            <i class="fas fa-times me-1.5"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-sm btn-uk-primary rounded-pill px-4 shadow-xs fw-bold">
                            <i class="fas fa-save me-1.5"></i>Simpan Kas Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
