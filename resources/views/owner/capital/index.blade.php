@extends('layouts.app')

@section('title', 'Modal Usaha')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Modal Usaha</h4>
        <p class="text-muted small mb-0">Kelola dan pantau seluruh penyetoran modal kerja usaha Anda</p>
    </div>
    <a href="{{ route('owner.capital.create') }}" class="btn btn-uk-primary">
        <i class="fas fa-plus me-2"></i>Tambah Modal
    </a>
</div>

<!-- KPI Stat Card -->
<div class="row g-3 mb-4">
    <div class="col-md-5 col-xl-4">
        <div class="card uk-stat-card border-0">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="uk-stat-icon me-3" style="background: rgba(51, 153, 137, 0.15); color: var(--uk-primary);">
                    <i class="fas fa-coins fa-lg"></i>
                </div>
                <div>
                    <div class="uk-stat-label">Total Modal Terhimpun</div>
                    <div class="uk-stat-value" style="font-size: 1.5rem; color: var(--uk-dark);">Rp {{ number_format($totalCapital, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="card uk-card border-0 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('owner.capital.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Tanggal Akhir</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-uk-primary flex-grow-1">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                @if(request('start_date') || request('end_date'))
                <a href="{{ route('owner.capital.index') }}" class="btn btn-uk-outline" title="Reset Filter">
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
                        <th class="text-end" style="width: 170px;">Jumlah</th>
                        <th>Sumber Modal</th>
                        <th>Keterangan</th>
                        <th style="width: 160px;">Dicatat Oleh</th>
                        <th class="text-center pe-4" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($capitalEntries as $entry)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $capitalEntries->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $entry->entry_date->format('d/m/Y') }}</div>
                        </td>
                        <td class="text-end fw-bold text-success">
                            Rp {{ number_format($entry->amount, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge badge-uk-accent text-dark fw-medium">{{ $entry->source }}</span>
                        </td>
                        <td>
                            <span class="text-dark">{{ $entry->description ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white small" style="width: 26px; height: 26px; background-color: var(--uk-dark-secondary); font-size: 0.7rem;">
                                    {{ strtoupper(substr($entry->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="small text-muted">{{ $entry->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('owner.capital.edit', $entry) }}" class="btn btn-sm btn-uk-outline" title="Edit Modal">
                                    <i class="fas fa-edit text-warning"></i>
                                </a>
                                <form action="{{ route('owner.capital.destroy', $entry) }}" method="POST" class="d-inline" id="delete-form-{{ $entry->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-uk-outline text-danger btn-delete" data-id="{{ $entry->id }}" title="Hapus Modal">
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
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: rgba(51, 153, 137, 0.1); color: var(--uk-primary);">
                                    <i class="fas fa-coins fa-lg"></i>
                                </div>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: var(--uk-dark);">Belum Ada Riwayat Modal Usaha</h6>
                            <p class="text-muted small mb-3">Mulai catat setoran modal awal atau modal tambahan Anda</p>
                            <a href="{{ route('owner.capital.create') }}" class="btn btn-uk-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Tambah Modal Baru
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($capitalEntries->hasPages())
    <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3">
        <small class="text-muted">
            Menampilkan <span class="fw-semibold text-dark">{{ $capitalEntries->firstItem() }}</span> - <span class="fw-semibold text-dark">{{ $capitalEntries->lastItem() }}</span> dari <span class="fw-semibold text-dark">{{ $capitalEntries->total() }}</span> data
        </small>
        <div>
            {{ $capitalEntries->withQueryString()->links() }}
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
            confirmButtonColor: '#2B2C28',
            cancelButtonColor: '#131515',
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
