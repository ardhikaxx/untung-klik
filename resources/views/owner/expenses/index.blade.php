@extends('layouts.app')

@section('title', 'Pengeluaran Operasional')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Pengeluaran Operasional</h4>
        <p class="text-muted small mb-0">Kelola dan pantau seluruh beban biaya dan pengeluaran operasional usaha</p>
    </div>
    <a href="{{ route('owner.expenses.create') }}" class="btn btn-uk-primary">
        <i class="fas fa-plus me-2"></i>Tambah Pengeluaran
    </a>
</div>

<!-- KPI Stat Card -->
<div class="row g-3 mb-4">
    <div class="col-md-5 col-xl-4">
        <div class="card uk-stat-card border-0">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="uk-stat-icon me-3" style="background: rgba(229, 88, 18, 0.12); color: var(--uk-orange);">
                    <i class="fas fa-arrow-up fa-lg"></i>
                </div>
                <div>
                    <div class="uk-stat-label">Total Beban Pengeluaran</div>
                    <div class="uk-stat-value" style="font-size: 1.5rem; color: var(--uk-orange);">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="card uk-card border-0 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('owner.expenses.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="category_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Kategori Biaya</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="start_date" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Tanggal Akhir</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-uk-primary flex-grow-1">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                @if(request('category_id') || request('start_date') || request('end_date'))
                <a href="{{ route('owner.expenses.index') }}" class="btn btn-uk-outline" title="Reset Filter">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card uk-card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table uk-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th style="width: 130px;">Tanggal</th>
                        <th style="width: 160px;">Kategori</th>
                        <th class="text-end" style="width: 170px;">Jumlah</th>
                        <th>Keterangan</th>
                        <th style="width: 160px;">Dicatat Oleh</th>
                        <th class="text-center pe-4" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $expenses->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $expense->expense_date->format('d/m/Y') }}</div>
                        </td>
                        <td>
                            <span class="badge badge-uk-primary fw-medium">{{ $expense->category->name ?? '-' }}</span>
                        </td>
                        <td class="text-end fw-bold text-danger">
                            - Rp {{ number_format($expense->amount, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="text-dark">{{ $expense->description ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white small" style="width: 26px; height: 26px; background-color: var(--uk-dark-secondary); font-size: 0.7rem;">
                                    {{ strtoupper(substr($expense->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="small text-muted">{{ $expense->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('owner.expenses.edit', $expense) }}" class="btn btn-sm btn-uk-outline" title="Edit Pengeluaran">
                                    <i class="fas fa-edit text-warning"></i>
                                </a>
                                <form action="{{ route('owner.expenses.destroy', $expense) }}" method="POST" class="d-inline" id="delete-form-{{ $expense->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-uk-outline text-danger btn-delete" data-id="{{ $expense->id }}" title="Hapus Pengeluaran">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="mb-3">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: rgba(229, 88, 18, 0.12); color: var(--uk-orange);">
                                    <i class="fas fa-arrow-up fa-lg"></i>
                                </div>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: var(--uk-dark);">Belum Ada Riwayat Pengeluaran</h6>
                            <p class="text-muted small mb-3">Catat seluruh pengeluaran operasional usaha Anda secara tertib</p>
                            <a href="{{ route('owner.expenses.create') }}" class="btn btn-uk-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Tambah Pengeluaran Baru
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($expenses->hasPages())
    <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3">
        <small class="text-muted">
            Menampilkan <span class="fw-semibold text-dark">{{ $expenses->firstItem() }}</span> - <span class="fw-semibold text-dark">{{ $expenses->lastItem() }}</span> dari <span class="fw-semibold text-dark">{{ $expenses->total() }}</span> data
        </small>
        <div>
            {{ $expenses->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.dataset.id;
        Swal.fire({
            title: 'Hapus Pengeluaran?',
            text: 'Data pengeluaran yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E55812',
            cancelButtonColor: '#002626',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    });
});
</script>
@endpush
@endsection
