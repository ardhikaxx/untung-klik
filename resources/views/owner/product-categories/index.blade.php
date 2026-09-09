@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Kategori Produk</h2>
        <p class="uk-page-subtitle">Kelola kelompok dan klasifikasi produk untuk mempermudah operasional kasir dan laporan.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('owner.products.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
            <i class="fas fa-box me-1.5"></i>Lihat Produk
        </a>
        <button type="button" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 shadow-xs fw-bold" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus me-1.5"></i>Tambah Kategori
        </button>
    </div>
</div>

<div class="uk-card p-0 overflow-hidden">
    @if($categories->isEmpty())
        <div class="uk-empty-state py-5">
            <div class="uk-empty-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="uk-empty-title">Belum Ada Kategori Produk</div>
            <p class="uk-empty-desc mb-3">Buat kelompok kategori untuk memudahkan pengelompokan dan pencarian barang dagangan.</p>
            <button type="button" class="btn btn-sm btn-uk-primary rounded-pill px-3.5" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus me-1"></i>Tambah Kategori Pertama
            </button>
        </div>
    @else
        <div class="table-responsive mb-0">
            <table class="table uk-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Produk</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $index => $category)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $categories->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $category->name }}</span>
                            </td>
                            <td class="text-muted small">
                                {{ $category->description ?: '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge px-2 py-0.5 rounded" style="background-color: var(--uk-background); color: var(--uk-dark); border: 1px solid var(--uk-border); font-size: 0.72rem;">
                                    {{ $category->products_count }} Produk
                                </span>
                            </td>
                            <td class="text-center">
                                @if($category->is_active)
                                    <span class="badge px-2 py-0.5 rounded-pill" style="background-color: rgba(14, 71, 73, 0.12); color: var(--uk-primary); font-size: 0.7rem;">Aktif</span>
                                @else
                                    <span class="badge px-2 py-0.5 rounded-pill" style="background-color: rgba(0, 38, 38, 0.08); color: var(--uk-dark); font-size: 0.7rem;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button type="button" class="btn btn-sm btn-uk-outline"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $category->id }}"
                                            title="Edit Kategori">
                                        <i class="fas fa-edit text-warning"></i>
                                    </button>
                                    <form action="{{ route('owner.product-categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-uk-outline text-danger"
                                                onclick="confirmDelete(event, 'Kategori ini akan dihapus. Produk yang terkait tidak akan terhapus.')"
                                                title="Hapus Kategori">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start border-0 shadow-lg" style="border-radius: var(--uk-radius);">
                                            <form action="{{ route('owner.product-categories.update', $category) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header border-bottom py-3 px-4">
                                                    <h6 class="modal-title fw-bold text-dark mb-0">Edit Kategori Produk</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Nama Kategori <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Deskripsi</label>
                                                        <textarea class="form-control" name="description" rows="2">{{ $category->description }}</textarea>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label fw-semibold small text-dark mb-1">Status</label>
                                                        <select class="form-select form-select-sm" name="is_active">
                                                            <option value="1" {{ $category->is_active ? 'selected' : '' }}>Aktif</option>
                                                            <option value="0" {{ ! $category->is_active ? 'selected' : '' }}>Nonaktif</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top py-2.5 px-4 bg-light">
                                                    <button type="button" class="btn btn-sm btn-uk-secondary rounded-pill px-3" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1.5"></i>Batal
                                                    </button>
                                                    <button type="submit" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 fw-bold">
                                                        <i class="fas fa-save me-1.5"></i>Simpan Perubahan
                                                    </button>
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
            <div class="p-3 px-4 border-top">
                {{ $categories->links() }}
            </div>
        @endif
    @endif
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start border-0 shadow-lg" style="border-radius: var(--uk-radius);">
            <form action="{{ route('owner.product-categories.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom py-3 px-4">
                    <h6 class="modal-title fw-bold text-dark mb-0">Tambah Kategori Produk</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark mb-1">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Contoh: Makanan, Minuman, Sembako, Pakaian" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark mb-1">Deskripsi (Opsional)</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="Keterangan singkat kategori..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-2.5 px-4 bg-light">
                    <button type="button" class="btn btn-sm btn-uk-secondary rounded-pill px-3" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1.5"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 fw-bold">
                        <i class="fas fa-save me-1.5"></i>Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
