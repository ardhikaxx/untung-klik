@extends('layouts.app')

@section('title', 'Kategori Transaksi')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Kategori Transaksi</h4>
        <p class="text-muted small mb-0">Kelola klasifikasi akun pos kas masuk, uang keluar, dan beban operasional</p>
    </div>
    <a href="{{ route('owner.categories.create') }}" class="btn btn-uk-primary">
        <i class="fas fa-plus me-2"></i>Tambah Kategori
    </a>
</div>

<div class="card uk-card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table uk-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th>Nama Kategori</th>
                        <th class="text-center" style="width: 160px;">Tipe Arus Kas</th>
                        <th class="text-center" style="width: 140px;">Status</th>
                        <th class="text-center pe-4" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $categories->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $category->name }}</div>
                        </td>
                        <td class="text-center">
                            @if($category->type === 'masuk')
                                <span class="badge badge-uk-success">
                                    <i class="fas fa-arrow-down me-1"></i>Masuk
                                </span>
                            @elseif($category->type === 'keluar')
                                <span class="badge badge-uk-danger">
                                    <i class="fas fa-arrow-up me-1"></i>Keluar
                                </span>
                            @else
                                <span class="badge badge-uk-primary">
                                    <i class="fas fa-briefcase me-1"></i>Operasional
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($category->is_active)
                                <span class="badge badge-uk-accent text-dark fw-medium">Aktif</span>
                            @else
                                <span class="badge badge-uk-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('owner.categories.edit', $category) }}" class="btn btn-sm btn-uk-outline" title="Edit Kategori">
                                    <i class="fas fa-edit text-warning"></i>
                                </a>
                                @if($category->is_active)
                                <form action="{{ route('owner.categories.destroy', $category) }}" method="POST" class="d-inline" id="deactivate-form-{{ $category->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-uk-outline text-danger btn-deactivate" data-id="{{ $category->id }}" data-name="{{ $category->name }}" title="Nonaktifkan Kategori">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="mb-3">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: rgba(51, 153, 137, 0.1); color: var(--uk-primary);">
                                    <i class="fas fa-tags fa-lg"></i>
                                </div>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: var(--uk-dark);">Belum Ada Kategori Transaksi</h6>
                            <p class="text-muted small mb-3">Buat kategori pos kas untuk merapikan pembukuan keuangan</p>
                            <a href="{{ route('owner.categories.create') }}" class="btn btn-uk-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Tambah Kategori
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
    <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3">
        <small class="text-muted">
            Menampilkan <span class="fw-semibold text-dark">{{ $categories->firstItem() }}</span> - <span class="fw-semibold text-dark">{{ $categories->lastItem() }}</span> dari <span class="fw-semibold text-dark">{{ $categories->total() }}</span> data
        </small>
        <div>
            {{ $categories->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.querySelectorAll('.btn-deactivate').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.dataset.id;
        var name = this.dataset.name;
        Swal.fire({
            title: 'Nonaktifkan Kategori?',
            text: 'Kategori "' + name + '" akan dinonaktifkan dari pilihan transaksi baru.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2B2C28',
            cancelButtonColor: '#131515',
            confirmButtonText: 'Ya, Nonaktifkan',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('deactivate-form-' + id).submit();
            }
        });
    });
});
</script>
@endpush
@endsection
