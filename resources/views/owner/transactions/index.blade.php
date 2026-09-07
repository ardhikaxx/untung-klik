@extends('layouts.app')

@section('title', 'Uang Masuk & Keluar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Uang Masuk & Keluar</h4>
        <p class="text-muted mb-0">Kelola semua transaksi keuangan</p>
    </div>
    <a href="{{ route('owner.transactions.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>Tambah Transaksi
    </a>
</div>

<div class="card border shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('owner.transactions.index') }}">
            <div class="row g-3">
                <div class="col-md-2">
                    <label for="type" class="form-label fw-semibold small">Tipe</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Semua</option>
                        <option value="masuk" {{ request('type') === 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ request('type') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="category_id" class="form-label fw-semibold small">Kategori</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Semua</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label fw-semibold small">Dari</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label fw-semibold small">Sampai</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="search" class="form-label fw-semibold small">Cari</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Sumber/keterangan">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('owner.transactions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border shadow-sm">
    <div class="card-body p-0">
        @if($transactions->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-inbox fa-3x text-muted"></i>
                </div>
                <h6 class="text-muted">Belum ada transaksi</h6>
                <p class="text-muted small mb-3">Mulai catat transaksi keuangan usaha Anda</p>
                <a href="{{ route('owner.transactions.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>Tambah Transaksi
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">No</th>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Kategori</th>
                            <th>Sumber/Keterangan</th>
                            <th class="text-end">Jumlah</th>
                            <th>User</th>
                            <th class="text-center pe-3" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $index => $transaction)
                            <tr>
                                <td class="ps-3">{{ $transactions->firstItem() + $index }}</td>
                                <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($transaction->type === 'masuk')
                                        <span class="badge bg-success">Masuk</span>
                                    @else
                                        <span class="badge bg-danger">Keluar</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->category->name ?? '-' }}</td>
                                <td>
                                    @if($transaction->is_sale)
                                        <div class="mb-1">
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                <i class="fas fa-receipt me-1"></i>{{ $transaction->invoice_number ?? 'Penjualan' }}
                                            </span>
                                            @if($transaction->customer_name)
                                                <span class="small text-muted">({{ $transaction->customer_name }})</span>
                                            @endif
                                        </div>
                                    @endif
                                    <div>{{ $transaction->source ?: '-' }}</div>
                                    @if($transaction->description)
                                        <small class="text-muted">{{ Str::limit($transaction->description, 40) }}</small>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold {{ $transaction->type === 'masuk' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td class="text-muted small">{{ $transaction->user->name }}</td>
                                <td class="text-center pe-3">
                                    <div class="d-flex gap-1 justify-content-center">
                                        @if($transaction->is_sale)
                                            <a href="{{ route('owner.sales.show', $transaction) }}"
                                               class="btn btn-sm btn-outline-success" title="Lihat Struk Penjualan">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('owner.transactions.show', $transaction) }}"
                                               class="btn btn-sm btn-outline-info" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('owner.transactions.edit', $transaction) }}"
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('owner.transactions.destroy', $transaction) }}"
                                              method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                                    title="Hapus" data-id="{{ $transaction->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 px-3 py-3 border-top">
                <small class="text-muted">
                    Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }}
                    dari {{ $transactions->total() }} transaksi
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
                text: 'Transaksi yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
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
