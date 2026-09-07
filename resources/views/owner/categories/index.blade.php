@extends('layouts.app')

@section('title', 'Kategori Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Kategori Transaksi</h4>
        <p class="text-muted mb-0">Kelola kategori transaksi usaha Anda</p>
    </div>
    <a href="{{ route('owner.categories.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Tambah Kategori
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60" class="text-center">No</th>
                        <th>Nama</th>
                        <th width="150" class="text-center">Tipe</th>
                        <th width="120" class="text-center">Status</th>
                        <th width="160" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="text-center">{{ $categories->firstItem() + $loop->index }}</td>
                        <td>{{ $category->name }}</td>
                        <td class="text-center">
                            @if($category->type === 'masuk')
                                <span class="badge bg-success">Masuk</span>
                            @elseif($category->type === 'keluar')
                                <span class="badge bg-danger">Keluar</span>
                            @else
                                <span class="badge bg-primary">Operasional</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($category->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('owner.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($category->is_active)
                            <form action="{{ route('owner.categories.destroy', $category) }}" method="POST" class="d-inline" id="deactivate-form-{{ $category->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger btn-deactivate" data-id="{{ $category->id }}" data-name="{{ $category->name }}" title="Nonaktifkan">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-tags fa-2x mb-2 d-block"></i>
                            Belum ada kategori
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
    <div class="card-footer bg-white border-top-0">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $categories->firstItem() }} - {{ $categories->lastItem() }} dari {{ $categories->total() }} data
            </small>
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
            text: 'Kategori "' + name + '" akan dinonaktifkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
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
