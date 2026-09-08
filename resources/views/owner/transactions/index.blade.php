@extends('layouts.app')

@section('title', 'Uang Masuk & Keluar')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Buku Kas & Transaksi</h4>
        <p class="text-muted small mb-0">Kelola dan pantau seluruh catatan arus kas masuk maupun keluar secara digital</p>
    </div>
    <a href="{{ route('owner.transactions.create') }}" class="btn btn-uk-primary">
        <i class="fas fa-plus me-2"></i>Tambah Transaksi
    </a>
</div>

<!-- Filter Card -->
<div class="card uk-card mb-4 border-0">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('owner.transactions.index') }}">
            <div class="row g-3">
                <div class="col-6 col-md-2">
                    <label for="type" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Tipe Transaksi</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Semua Tipe</option>
                        <option value="masuk" {{ request('type') === 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ request('type') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="category_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Kategori</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="start_date" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label for="end_date" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-12 col-md-2">
                    <label for="search" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Cari Transaksi</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Sumber/keterangan...">
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-uk-primary flex-grow-1">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['type', 'category_id', 'start_date', 'end_date', 'search']))
                        <a href="{{ route('owner.transactions.index') }}" class="btn btn-uk-outline" title="Reset Filter">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Transactions Table Card -->
<div class="card uk-card border-0">
    <div class="card-body p-0">
        @if($transactions->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background: rgba(51, 153, 137, 0.1); color: var(--uk-primary);">
                        <i class="fas fa-wallet fa-2x"></i>
                    </div>
                </div>
                <h6 class="fw-bold mb-1" style="color: var(--uk-dark);">Belum Ada Transaksi Tercatat</h6>
                <p class="text-muted small mb-3">Mulai catat transaksi uang masuk atau pengeluaran operasional Anda</p>
                <a href="{{ route('owner.transactions.create') }}" class="btn btn-uk-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Tambah Transaksi Pertama
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table uk-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 60px;">No</th>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Kategori</th>
                            <th>Sumber & Keterangan</th>
                            <th class="text-end">Jumlah</th>
                            <th>Pencatat</th>
                            <th class="text-center pe-4" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $index => $transaction)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $transactions->firstItem() + $index }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $transaction->transaction_date->format('d/m/Y') }}</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $transaction->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td>
                                    @if($transaction->type === 'masuk')
                                        <span class="badge badge-uk-success">
                                            <i class="fas fa-arrow-down me-1"></i>Masuk
                                        </span>
                                    @else
                                        <span class="badge badge-uk-danger">
                                            <i class="fas fa-arrow-up me-1"></i>Keluar
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->category)
                                        <span class="badge badge-uk-accent text-dark fw-medium">
                                            {{ $transaction->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->is_sale)
                                        <div class="mb-1">
                                            <span class="badge badge-uk-primary">
                                                <i class="fas fa-receipt me-1"></i>{{ $transaction->invoice_number ?? 'Penjualan Kasir' }}
                                            </span>
                                            @if($transaction->customer_name)
                                                <span class="small text-muted ms-1">({{ $transaction->customer_name }})</span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="fw-medium text-dark">{{ $transaction->source ?: '-' }}</div>
                                    @if($transaction->description)
                                        <small class="text-muted d-block text-truncate" style="max-width: 260px;">{{ $transaction->description }}</small>
                                    @endif
                                </td>
                                <td class="text-end fw-bold {{ $transaction->type === 'masuk' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white small" style="width: 26px; height: 26px; background-color: var(--uk-dark-secondary); font-size: 0.7rem;">
                                            {{ strtoupper(substr($transaction->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="small text-muted">{{ $transaction->user->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex gap-1 justify-content-center">
                                        @if($transaction->is_sale)
                                            <a href="{{ route('owner.sales.show', $transaction) }}"
                                               class="btn btn-sm btn-uk-outline" title="Lihat Struk Penjualan">
                                                <i class="fas fa-receipt text-success"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('owner.transactions.show', $transaction) }}"
                                               class="btn btn-sm btn-uk-outline" title="Detail Transaksi">
                                                <i class="fas fa-eye text-primary"></i>
                                            </a>
                                            <a href="{{ route('owner.transactions.edit', $transaction) }}"
                                               class="btn btn-sm btn-uk-outline" title="Edit Transaksi">
                                                <i class="fas fa-edit text-warning"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('owner.transactions.destroy', $transaction) }}"
                                              method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-uk-outline btn-delete text-danger"
                                                    title="Hapus Transaksi" data-id="{{ $transaction->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3 border-top">
                <small class="text-muted">
                    Menampilkan <span class="fw-semibold text-dark">{{ $transactions->firstItem() }}</span> - <span class="fw-semibold text-dark">{{ $transactions->lastItem() }}</span>
                    dari <span class="fw-semibold text-dark">{{ $transactions->total() }}</span> transaksi
                </small>
                <div>
                    {{ $transactions->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var form = this.closest('form');
            Swal.fire({
                title: 'Hapus Transaksi?',
                text: 'Transaksi yang dihapus akan dikeluarkan dari buku kas dan tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2B2C28',
                cancelButtonColor: '#131515',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
