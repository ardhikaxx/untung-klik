@extends('layouts.app')

@section('title', 'Catat Penjualan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('karyawan.transactions.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h5 class="fw-bold mb-1">Catat Penjualan</h5>
                <p class="text-muted mb-0 small">Isi data penjualan yang ingin dicatat</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('karyawan.transactions.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold">Jumlah Penjualan</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text fw-bold" style="background-color: #f0fdf4; border-color: #86efac; color: #16a34a;">Rp</span>
                            <input
                                type="number"
                                class="form-control @error('amount') is-invalid @enderror"
                                id="amount"
                                name="amount"
                                value="{{ old('amount') }}"
                                placeholder="0"
                                min="0"
                                required
                                autofocus
                                style="font-size: 1.25rem; font-weight: 600;"
                            >
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="transaction_date" class="form-label fw-semibold">Tanggal</label>
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

                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-semibold">Kategori</label>
                        <select
                            class="form-select @error('category_id') is-invalid @enderror"
                            id="category_id"
                            name="category_id"
                            required
                        >
                            <option value="">Pilih Kategori</option>
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

                    <div class="mb-3">
                        <label for="source" class="form-label fw-semibold">Sumber</label>
                        <input
                            type="text"
                            class="form-control @error('source') is-invalid @enderror"
                            id="source"
                            name="source"
                            value="{{ old('source') }}"
                            placeholder="Contoh: Penjualan online, Kasir utama"
                        >
                        @error('source')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                        <textarea
                            class="form-control @error('description') is-invalid @enderror"
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Catatan tambahan (opsional)"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="payment_method" class="form-label fw-semibold">Metode Pembayaran</label>
                        <select
                            class="form-select @error('payment_method') is-invalid @enderror"
                            id="payment_method"
                            name="payment_method"
                        >
                            <option value="tunai" {{ old('payment_method', 'tunai') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="lainnya" {{ old('payment_method') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i>Simpan Penjualan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
