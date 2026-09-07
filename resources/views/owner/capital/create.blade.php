@extends('layouts.app')

@section('title', 'Tambah Modal')

@section('content')
<div class="mb-4">
    <a href="{{ route('owner.capital.index') }}" class="text-decoration-none text-muted">
        <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar Modal
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="fw-bold mb-0">Tambah Modal Baru</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.capital.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="amount" class="form-label">Jumlah Modal <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input
                                type="number"
                                class="form-control form-control-lg @error('amount') is-invalid @enderror"
                                id="amount"
                                name="amount"
                                value="{{ old('amount') }}"
                                placeholder="0"
                                min="0"
                                required
                                autofocus
                            >
                        </div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="entry_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
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
                        <label for="source" class="form-label">Sumber <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control @error('source') is-invalid @enderror"
                            id="source"
                            name="source"
                            value="{{ old('source') }}"
                            placeholder="Contoh: Setoran pribadi, Pinjaman bank"
                            required
                        >
                        @error('source')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Keterangan</label>
                        <textarea
                            class="form-control @error('description') is-invalid @enderror"
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Deskripsi singkat mengenai modal ini"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                        <a href="{{ route('owner.capital.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
