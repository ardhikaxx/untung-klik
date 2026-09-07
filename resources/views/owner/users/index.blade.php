@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Pengguna</h4>
        <p class="text-muted mb-0">Kelola akun pengguna dalam bisnis Anda</p>
    </div>
    <a href="{{ route('owner.users.create') }}" class="btn btn-success btn-sm">
        <i class="fas fa-plus me-1"></i> Tambah Pengguna
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Telepon</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end pe-3" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $item)
                        <tr>
                            <td class="ps-3">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td class="fw-semibold">{{ $item->name }}</td>
                            <td>{{ $item->username }}</td>
                            <td>{{ $item->phone ?? '-' }}</td>
                            <td>
                                @if ($item->role === 'owner')
                                    <span class="badge bg-dark">Owner</span>
                                @elseif ($item->role === 'admin')
                                    <span class="badge bg-primary">Admin</span>
                                @else
                                    <span class="badge bg-info">Karyawan</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->is_active)
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('owner.users.edit', $item) }}"
                                       class="btn btn-outline-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if ($item->id !== auth()->id())
                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm btn-deactivate"
                                                data-id="{{ $item->id }}"
                                                data-name="{{ $item->name }}"
                                                title="Nonaktifkan">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-users fa-2x mb-2 d-block"></i>
                                Belum ada data pengguna
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($users->hasPages())
        <div class="card-footer bg-white border-top">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                </small>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1) !important; }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.btn-deactivate').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;

            Swal.fire({
                title: 'Nonaktifkan Pengguna?',
                html: 'Akun <strong>' + userName + '</strong> akan dinonaktifkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Nonaktifkan',
                cancelButtonText: 'Batal'
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
