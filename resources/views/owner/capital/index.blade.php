@extends('layouts.app')

@section('title', 'Modal Usaha')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Modal Usaha</h4>
        <p class="text-muted mb-0">Kelola modal usaha Anda</p>
    </div>
    <a href="{{ route('owner.capital.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Tambah Modal
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-coins text-success fa-lg"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Total Modal</p>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($totalCapital, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('owner.capital.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                @if(request('start_date') || request('end_date'))
                <a href="{{ route('owner.capital.index') }}" class="btn btn-outline-secondary">
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
                        <th width="180" class="text-end">Jumlah</th>
                        <th>Sumber</th>
                        <th>Keterangan</th>
                        <th width="160">Dicatat Oleh</th>
                        <th width="140" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($capitalEntries as $entry)
                    <tr>
                        <td class="text-center">{{ $capitalEntries->firstItem() + $loop->index }}</td>
                        <td>{{ $entry->entry_date->format('d/m/Y') }}</td>
                        <td class="text-end fw-semibold">Rp {{ number_format($entry->amount, 0, ',', '.') }}</td>
                        <td>{{ $entry->source }}</td>
                        <td>{{ $entry->description ?? '-' }}</td>
                        <td>{{ $entry->user->name ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('owner.capital.edit', $entry) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('owner.capital.destroy', $entry) }}" method="POST" class="d-inline" id="delete-form-{{ $entry->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-id="{{ $entry->id }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-coins fa-2x mb-2 d-block"></i>
                            Belum ada data modal
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($capitalEntries->hasPages())
    <div class="card-footer bg-white border-top-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">
                Menampilkan {{ $capitalEntries->firstItem() }} - {{ $capitalEntries->lastItem() }} dari {{ $capitalEntries->total() }} data
            </small>
            <div>
                {{ $capitalEntries->withQueryString()->links() }}
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
            title: 'Hapus Data Modal?',
            text: 'Data modal yang dihapus tidak dapat dikembalikan.',
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
