@extends('layouts.app')

@section('title', 'Tambah Modal')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('owner.capital.index') }}" class="btn btn-uk-outline btn-sm me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Tambah Modal Baru</h4>
                <p class="text-muted small mb-0">Catat penambahan modal awal atau modal tambahan usaha</p>
            </div>
        </div>

        <div class="card uk-card border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.capital.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Jumlah Modal (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text fw-bold bg-white text-dark border-end-0">Rp</span>
                            <input
                                type="number"
                                class="form-control form-control-lg border-start-0 ps-0 fw-bold @error('amount') is-invalid @enderror"
                                id="amount"
                                name="amount"
                                value="{{ old('amount') }}"
                                placeholder="0"
                                min="0"
                                required
                                autofocus
                                style="font-size: 1.5rem; color: var(--uk-dark);"
                            >
                        </div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="entry_date" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Tanggal Penyetoran <span class="text-danger">*</span></label>
                        <input
                            type="date"
                            class="form-control @error('entry_date') is-invalid @enderror"
                            id="entry_date"
                            name="entry_date"
                            value="{{ old('entry_date', date('Y-m-d')) }}"
                            required
                        >
                        @error('entry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="source" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Sumber Modal <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control @error('source') is-invalid @enderror"
                            id="source"
                            name="source"
                            value="{{ old('source') }}"
                            placeholder="Contoh: Tabungan Pribadi, Investor Mitra, Pinjaman Bank"
                            required
                        >
                        @error('source')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Keterangan Rinci</label>
                        <textarea
                            class="form-control @error('description') is-invalid @enderror"
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Catatan tambahan mengenai penggunaan alokasi modal ini (opsional)"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center gap-2 pt-2">
                        <button type="submit" class="btn btn-uk-primary px-4">
                            <i class="fas fa-check-circle me-1"></i>Simpan Modal
                        </button>
                        <a href="{{ route('owner.capital.index') }}" class="btn btn-uk-outline">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
