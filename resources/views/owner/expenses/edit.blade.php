@extends('layouts.app')

@section('title', 'Edit Pengeluaran Operasional')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('owner.expenses.index') }}" class="btn btn-uk-outline btn-sm me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Edit Pengeluaran</h4>
                <p class="text-muted small mb-0">Perbarui rincian catatan beban biaya operasional</p>
            </div>
        </div>

        <div class="card uk-card border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.expenses.update', $expense) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Jumlah Pengeluaran (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text fw-bold bg-white text-dark border-end-0">Rp</span>
                            <input
                                type="number"
                                class="form-control form-control-lg border-start-0 ps-0 fw-bold @error('amount') is-invalid @enderror"
                                id="amount"
                                name="amount"
                                value="{{ old('amount', $expense->amount) }}"
                                placeholder="0"
                                min="0"
                                required
                                autofocus
                                style="font-size: 1.5rem; color: var(--uk-dark-secondary);"
                            >
                        </div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="expense_date" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input
                            type="date"
                            class="form-control @error('expense_date') is-invalid @enderror"
                            id="expense_date"
                            name="expense_date"
                            value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}"
                            required
                        >
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Kategori Pengeluaran <span class="text-danger">*</span></label>
                        <select
                            class="form-select @error('category_id') is-invalid @enderror"
                            id="category_id"
                            name="category_id"
                            required
                        >
                            <option value="">Pilih Kategori Beban</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
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
                            placeholder="Deskripsi singkat atau rincian keperluan pengeluaran ini"
                        >{{ old('description', $expense->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center gap-2 pt-2">
                        <button type="submit" class="btn btn-uk-primary px-4">
                            <i class="fas fa-save me-1"></i>Perbarui Pengeluaran
                        </button>
                        <a href="{{ route('owner.expenses.index') }}" class="btn btn-uk-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
