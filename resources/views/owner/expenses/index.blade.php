@extends('layouts.app')

@section('title', 'Pengeluaran Operasional')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Pengeluaran Operasional</h4>
        <p class="text-muted mb-0">Kelola pengeluaran operasional usaha Anda</p>
    </div>
    <a href="{{ route('owner.expenses.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Tambah Pengeluaran
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                            <i class="fas fa-arrow-up text-danger fa-lg"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Total Pengeluaran</p>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('owner.expenses.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                @if(request('category_id') || request('start_date') || request('end_date'))
                <a href="{{ route('owner.expenses.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Reset
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60" class="text-center">No</th>
                        <th width="130">Tanggal</th>
                        <th width="150">Kategori</th>
                        <th width="180" class="text-end">Jumlah</th>
                        <th>Keterangan</th>
                        <th width="160">Dicatat Oleh</th>
                        <th width="140" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td class="text-center">{{ $expenses->firstItem() + $loop->index }}</td>
                        <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $expense->category->name ?? '-' }}</span>
                        </td>
                        <td class="text-end fw-semibold">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                        <td>{{ $expense->description ?? '-' }}</td>
                        <td>{{ $expense->user->name ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('owner.expenses.edit', $expense) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('owner.expenses.destroy', $expense) }}" method="POST" class="d-inline" id="delete-form-{{ $expense->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-id="{{ $expense->id }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-arrow-up fa-2x mb-2 d-block"></i>
                            Belum ada data pengeluaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($expenses->hasPages())
    <div class="card-footer bg-white border-top-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">
                Menampilkan {{ $expenses->firstItem() }} - {{ $expenses->lastItem() }} dari {{ $expenses->total() }} data
            </small>
            <div>
                {{ $expenses->withQueryString()->links() }}
            </div>
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
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
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
