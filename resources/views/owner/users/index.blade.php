@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Manajemen Tim & Pengguna</h2>
        <p class="uk-page-subtitle">Kelola akun kasir, staf operasional toko, peran akses, dan status keaktifan akun.</p>
    </div>
    <a href="{{ route('owner.users.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 shadow-xs fw-bold">
        <i class="fas fa-user-plus me-1.5"></i>Tambah Pengguna
    </a>
</div>

<div class="card uk-card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table uk-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th>Pengguna</th>
                        <th>Username</th>
                        <th>No. Telepon</th>
                        <th>Peran (Role)</th>
                        <th>Status</th>
                        <th class="text-center pe-4" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $item)
                        <tr>
                            <td class="ps-4 text-muted small">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-6" style="width: 38px; height: 38px; background-color: var(--uk-dark); color: #FFFFFF;">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $item->name }}</div>
                                        @if($item->id === auth()->id())
                                            <span class="badge badge-uk-accent text-dark" style="font-size: 0.65rem;">Akun Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="font-monospace text-muted small"><i class="fas fa-at me-1"></i>{{ $item->username }}</span>
                            </td>
                            <td>
                                <span class="text-dark small">{{ $item->phone ?: '-' }}</span>
                            </td>
                            <td>
                                @if ($item->role === 'owner')
                                    <span class="badge badge-uk-dark">Owner</span>
                                @elseif ($item->role === 'admin')
                                    <span class="badge badge-uk-primary">Admin</span>
                                @else
                                    <span class="badge badge-uk-accent text-dark fw-medium">Karyawan</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->is_active)
                                    <span class="badge badge-uk-primary">Aktif</span>
                                @else
                                    <span class="badge badge-uk-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-1.5 justify-content-center">
                                    <a href="{{ route('owner.users.edit', $item) }}"
                                       class="btn btn-xs btn-uk-outline rounded-pill px-2.5" title="Edit Pengguna">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    @if ($item->id !== auth()->id())
                                        <button type="button"
                                                class="btn btn-xs btn-uk-secondary rounded-pill px-2.5 btn-deactivate"
                                                data-id="{{ $item->id }}"
                                                data-name="{{ $item->name }}"
                                                title="Nonaktifkan Akun">
                                            <i class="fas fa-ban me-1"></i>Nonaktif
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="uk-empty-state py-4">
                                    <div class="uk-empty-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="uk-empty-title">Belum Ada Data Pengguna</div>
                                    <p class="uk-empty-desc mb-3">Tambahkan akun kasir atau staf untuk operasional toko Anda.</p>
                                    <a href="{{ route('owner.users.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 fw-bold">
                                        <i class="fas fa-user-plus me-1.5"></i>Tambah Pengguna
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($users->hasPages())
        <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3">
            <small class="text-muted">
                Menampilkan <span class="fw-semibold text-dark">{{ $users->firstItem() }}</span> - <span class="fw-semibold text-dark">{{ $users->lastItem() }}</span> dari <span class="fw-semibold text-dark">{{ $users->total() }}</span> pengguna
            </small>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-deactivate').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;

            Swal.fire({
                title: 'Nonaktifkan Pengguna?',
                html: 'Akun <strong>' + userName + '</strong> tidak akan dapat melakukan login ke sistem kasir.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E55812',
                cancelButtonColor: '#002626',
                confirmButtonText: 'Ya, Nonaktifkan',
                cancelButtonText: 'Batal',
                background: '#FFFFFF'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ url("owner/users") }}/' + userId;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
