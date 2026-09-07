@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Kategori Produk</h4>
        <p class="text-muted mb-0">Kelola kelompok dan pengelompokan produk usaha Anda</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-box me-1"></i>Lihat Produk
        </a>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus me-1"></i>Tambah Kategori
        </button>
    </div>
</div>

<div class="card border shadow-sm">
    <div class="card-body p-0">
        @if($categories->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-layer-group fa-3x text-muted opacity-50"></i>
                </div>
                <h6 class="text-muted fw-bold">Belum Ada Kategori Produk</h6>
                <p class="text-muted small mb-3">Buat kategori produk untuk memudahkan pengelompokan dan pencarian barang dagangan.</p>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus me-1"></i>Tambah Kategori Pertama
                </button>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 60px;">No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Jumlah Produk</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-3" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $index => $category)
                            <tr>
                                <td class="ps-3 text-muted">{{ $categories->firstItem() + $index }}</td>
                                <td class="fw-semibold text-dark">{{ $category->name }}</td>
                                <td class="text-muted small">{{ $category->description ?: '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">
                                        {{ $category->products_count }} Produk
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($category->is_active)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center pe-3">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $category->id }}"
                                                title="Edit Kategori">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('owner.product-categories.destroy', $category) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete(event, 'Kategori ini akan dihapus. Produk yang menggunakan kategori ini tidak akan terhapus.')"
                                                    title="Hapus Kategori">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <form action="{{ route('owner.product-categories.update', $category) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">Edit Kategori Produk</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Deskripsi</label>
                                                            <textarea class="form-control" name="description" rows="3">{{ $category->description }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Status</label>
                                                            <select class="form-select" name="is_active">
                                                                <option value="1" {{ $category->is_active ? 'selected' : '' }}>Aktif</option>
                                                                <option value="0" {{ ! $category->is_active ? 'selected' : '' }}>Nonaktif</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
                <div class="p-3 border-top">
                    {{ $categories->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('owner.product-categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Kategori Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Contoh: Makanan, Minuman, Pakaian, Sembako" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Keterangan singkat kategori (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
