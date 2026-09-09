<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Untung Klik') - Untung Klik</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CDN & Font Awesome 6 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Untung Klik Design System -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        @auth
        <!-- Mobile Sidebar Backdrop -->
        <div class="uk-sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

        <!-- Sidebar Navigation -->
        <aside class="uk-sidebar" id="sidebar">
            <!-- Brand Header -->
            <div class="uk-sidebar-brand">
                <div class="uk-brand-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="uk-brand-text">
                    <span class="uk-brand-name">Untung Klik</span>
                    <span class="uk-brand-tagline">Kasir &amp; Buku Kas</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="uk-sidebar-nav">
                @if(auth()->user()->isOwner())
                    {{-- Utama --}}
                    <div class="uk-nav-group">
                        <div class="uk-nav-label">Utama</div>
                        <a href="{{ route('owner.dashboard') }}" class="uk-nav-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-th-large"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('owner.sales.index') }}" class="uk-nav-item {{ request()->routeIs('owner.sales.*') ? 'active' : '' }}">
                            <i class="fas fa-cash-register"></i>
                            <span>Penjualan Harian</span>
                        </a>
                    </div>

                    {{-- Produk & Stok --}}
                    <div class="uk-nav-group">
                        <div class="uk-nav-label">Produk & Stok</div>
                        <a href="{{ route('owner.products.index') }}" class="uk-nav-item {{ request()->routeIs('owner.products.*') ? 'active' : '' }}">
                            <i class="fas fa-box"></i>
                            <span>Daftar Produk</span>
                        </a>
                        <a href="{{ route('owner.stock.index') }}" class="uk-nav-item {{ request()->routeIs('owner.stock.*') ? 'active' : '' }}">
                            <i class="fas fa-cubes"></i>
                            <span>Stok & Mutasi</span>
                        </a>
                        <a href="{{ route('owner.product-categories.index') }}" class="uk-nav-item {{ request()->routeIs('owner.product-categories.*') ? 'active' : '' }}">
                            <i class="fas fa-layer-group"></i>
                            <span>Kategori Produk</span>
                        </a>
                    </div>

                    {{-- Buku Kas Digital --}}
                    <div class="uk-nav-group">
                        <div class="uk-nav-label">Buku Kas Digital</div>
                        <a href="{{ route('owner.transactions.index') }}" class="uk-nav-item {{ request()->routeIs('owner.transactions.*') ? 'active' : '' }}">
                            <i class="fas fa-arrow-down"></i>
                            <span>Uang Masuk</span>
                        </a>
                        <a href="{{ route('owner.capital.index') }}" class="uk-nav-item {{ request()->routeIs('owner.capital.*') ? 'active' : '' }}">
                            <i class="fas fa-coins"></i>
                            <span>Modal Usaha</span>
                        </a>
                        <a href="{{ route('owner.expenses.index') }}" class="uk-nav-item {{ request()->routeIs('owner.expenses.*') ? 'active' : '' }}">
                            <i class="fas fa-arrow-up"></i>
                            <span>Uang Keluar</span>
                        </a>
                        <a href="{{ route('owner.categories.index') }}" class="uk-nav-item {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i>
                            <span>Kategori Kas</span>
                        </a>
                    </div>

                    {{-- Laporan & Analitik --}}
                    <div class="uk-nav-group">
                        <div class="uk-nav-label">Laporan & Analisis</div>
                        <a href="{{ route('owner.reports.sales') }}" class="uk-nav-item {{ request()->routeIs('owner.reports.sales') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Laporan Penjualan</span>
                        </a>
                        <a href="{{ route('owner.reports.index') }}" class="uk-nav-item {{ request()->routeIs('owner.reports.index') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Laporan Keuangan</span>
                        </a>
                        <a href="{{ route('owner.charts.index') }}" class="uk-nav-item {{ request()->routeIs('owner.charts.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Grafik Arus Kas</span>
                        </a>
                    </div>

                    {{-- Pengaturan --}}
                    <div class="uk-nav-group">
                        <div class="uk-nav-label">Pengaturan</div>
                        <a href="{{ route('owner.users.index') }}" class="uk-nav-item {{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users-cog"></i>
                            <span>Kelola Pengguna</span>
                        </a>
                        <a href="{{ route('owner.receipt.index') }}" class="uk-nav-item {{ request()->routeIs('owner.receipt.*') ? 'active' : '' }}">
                            <i class="fas fa-receipt"></i>
                            <span>Pengaturan Nota</span>
                        </a>
                        <a href="{{ route('owner.profile') }}" class="uk-nav-item {{ request()->routeIs('owner.profile') ? 'active' : '' }}">
                            <i class="fas fa-store"></i>
                            <span>Profil Usaha & Akun</span>
                        </a>
                    </div>
                @else
                    {{-- Karyawan Menus --}}
                    <div class="uk-nav-group">
                        <div class="uk-nav-label">Menu Utama</div>
                        <a href="{{ route('karyawan.dashboard') }}" class="uk-nav-item {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-th-large"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('karyawan.sales.index') }}" class="uk-nav-item {{ request()->routeIs('karyawan.sales.*') ? 'active' : '' }}">
                            <i class="fas fa-cash-register"></i>
                            <span>Kasir & Penjualan</span>
                        </a>
                        <a href="{{ route('karyawan.transactions.index') }}" class="uk-nav-item {{ request()->routeIs('karyawan.transactions.*') ? 'active' : '' }}">
                            <i class="fas fa-arrow-down"></i>
                            <span>Catat Kas Masuk</span>
                        </a>
                    </div>

                    <div class="uk-nav-group">
                        <div class="uk-nav-label">Laporan</div>
                        <a href="{{ route('karyawan.reports.index') }}" class="uk-nav-item {{ request()->routeIs('karyawan.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Laporan Penjualan</span>
                        </a>
                    </div>

                    <div class="uk-nav-group">
                        <div class="uk-nav-label">Pengaturan</div>
                        <a href="{{ route('karyawan.profile') }}" class="uk-nav-item {{ request()->routeIs('karyawan.profile') ? 'active' : '' }}">
                            <i class="fas fa-user-cog"></i>
                            <span>Profil Saya</span>
                        </a>
                    </div>
                @endif
            </nav>

            <!-- Sidebar User Card / Logout -->
            <div class="uk-sidebar-footer">
                <div class="uk-user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="uk-user-meta">
                    <div class="uk-user-name" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</div>
                    <div class="uk-user-role">
                        <span class="badge" style="background-color: {{ auth()->user()->isOwner() ? 'var(--uk-primary)' : 'var(--uk-accent)' }}; color: {{ auth()->user()->isOwner() ? '#FFFFFF' : 'var(--uk-dark)' }}; font-size: 0.65rem; padding: 2px 6px;">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="button" class="uk-btn-logout-mini" title="Keluar dari sistem" onclick="confirmLogout(event)">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </aside>
        @endauth

        <!-- Main Wrapper -->
        <div class="uk-main-wrapper main-content">
            <!-- Top Navbar -->
            <header class="uk-top-navbar top-navbar">
                <div class="uk-navbar-left navbar-left">
                    <button type="button" class="uk-sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="d-flex flex-column">
                        <h1 class="uk-navbar-title navbar-title">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="uk-navbar-right navbar-right">
                    <!-- Business Badge -->
                    <div class="uk-business-pill d-none d-sm-inline-flex">
                        <i class="fas fa-circle-check"></i>
                        <span>{{ config('app.name', 'Untung Klik') }}</span>
                    </div>

                    @auth
                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <button class="uk-dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="uk-user-avatar" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="d-none d-md-flex flex-column text-start me-1" style="line-height: 1.15;">
                                <span style="font-size: 0.82rem; font-weight: 700; color: var(--uk-dark);">{{ auth()->user()->name }}</span>
                                <span style="font-size: 0.68rem; color: var(--uk-text-muted); text-transform: capitalize;">{{ auth()->user()->role }}</span>
                            </div>
                            <i class="fas fa-chevron-down" style="font-size: 0.65rem; color: var(--uk-text-muted);"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border: 1px solid var(--uk-border); border-radius: var(--uk-radius-sm); min-width: 200px; padding: 0.5rem;">
                            <li class="px-3 py-2 border-bottom mb-1">
                                <div class="fw-bold" style="color: var(--uk-dark); font-size: 0.85rem;">{{ auth()->user()->name }}</div>
                                <div class="small text-muted" style="font-size: 0.75rem;">{{ auth()->user()->username ?? auth()->user()->email }}</div>
                            </li>
                            @if(auth()->user()->isOwner())
                                <li><a class="dropdown-item py-1.5 rounded" href="{{ route('owner.profile') }}"><i class="fas fa-store me-2 text-muted"></i>Profil Usaha</a></li>
                                <li><a class="dropdown-item py-1.5 rounded" href="{{ route('owner.users.index') }}"><i class="fas fa-users-cog me-2 text-muted"></i>Kelola Pengguna</a></li>
                            @else
                                <li><a class="dropdown-item py-1.5 rounded" href="{{ route('karyawan.profile') }}"><i class="fas fa-user-cog me-2 text-muted"></i>Profil Saya</a></li>
                            @endif
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="button" class="dropdown-item py-1.5 rounded text-danger" onclick="confirmLogout(event)">
                                        <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="uk-content-body content-area">
                {{-- Flash Notifications (Dismissible) --}}
                @if(session('success') && !request()->routeIs('*.dashboard'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-xs" role="alert" style="background-color: rgba(149, 198, 35, 0.15); color: #2e4206; border-left: 4px solid var(--uk-accent) !important; border-radius: var(--uk-radius-sm);">
                        <i class="fas fa-check-circle fs-5 me-3" style="color: var(--uk-accent);"></i>
                        <div class="flex-grow-1">
                            <strong class="d-block mb-0.5" style="font-size: 0.85rem;">Berhasil!</strong>
                            <span style="font-size: 0.85rem;">{{ session('success') }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-xs" role="alert" style="background-color: rgba(229, 88, 18, 0.12); color: var(--uk-orange); border-left: 4px solid var(--uk-orange) !important; border-radius: var(--uk-radius-sm);">
                        <i class="fas fa-exclamation-circle fs-5 me-3" style="color: var(--uk-orange);"></i>
                        <div class="flex-grow-1">
                            <strong class="d-block mb-0.5" style="font-size: 0.85rem;">Gagal Memproses Permintaan:</strong>
                            <span style="font-size: 0.85rem;">{{ session('error') }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-xs" role="alert" style="background-color: rgba(229, 88, 18, 0.12); color: var(--uk-orange); border-left: 4px solid var(--uk-orange) !important; border-radius: var(--uk-radius-sm);">
                        <i class="fas fa-exclamation-triangle fs-5 me-3" style="color: var(--uk-orange);"></i>
                        <div class="flex-grow-1">
                            <strong class="d-block mb-0.5" style="font-size: 0.85rem;">Perhatian:</strong>
                            <span style="font-size: 0.85rem;">{{ session('warning') }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-xs" role="alert" style="background-color: rgba(14, 71, 73, 0.12); color: var(--uk-primary); border-left: 4px solid var(--uk-primary) !important; border-radius: var(--uk-radius-sm);">
                        <i class="fas fa-info-circle fs-5 me-3" style="color: var(--uk-primary);"></i>
                        <div class="flex-grow-1">
                            <strong class="d-block mb-0.5" style="font-size: 0.85rem;">Informasi:</strong>
                            <span style="font-size: 0.85rem;">{{ session('info') }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-xs" role="alert" style="background-color: rgba(229, 88, 18, 0.12); color: var(--uk-orange); border-left: 4px solid var(--uk-orange) !important; border-radius: var(--uk-radius-sm);">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-times-circle fs-5 me-2" style="color: var(--uk-orange);"></i>
                            <strong style="font-size: 0.875rem;">Terdapat beberapa data yang perlu diperiksa:</strong>
                        </div>
                        <ul class="mb-0 ps-4 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="uk-footer main-footer">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span>{{ config('services.copyright', '© ' . date('Y') . ' Untung Klik. Hak Cipta Dilindungi.') }}</span>
                    <span class="text-muted small">Sistem Pembukuan Digital UMKM</span>
                </div>
            </footer>
        </div>
    </div>

    <!-- CDN JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        // Sidebar Mobile Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) sidebar.classList.toggle('show');
            if (backdrop) backdrop.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                const backdrop = document.getElementById('sidebarBackdrop');
                if (sidebar && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    if (backdrop) backdrop.classList.remove('show');
                }
            }
        });

        // Logout Confirmation Dialog
        function confirmLogout(event) {
            if (event) event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar dari sistem Untung Klik?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0E4749',
                cancelButtonColor: '#002626',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#FFFFFF'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Generic Delete Confirmation Dialog
        function confirmDelete(event, message = 'Data yang dihapus tidak dapat dikembalikan!') {
            if (event) event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E55812',
                cancelButtonColor: '#002626',
                confirmButtonText: 'Ya, Hapus Data',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#FFFFFF'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    {{-- SweetAlert2 Flash Notifications --}}
    @if(session('success') && !request()->routeIs('*.dashboard'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: {!! json_encode(session('success')) !!},
            timer: 3000,
            timerProgressBar: true,
            confirmButtonColor: '#0E4749',
            confirmButtonText: 'Tutup',
            background: '#FFFFFF'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Memproses',
            text: {!! json_encode(session('error')) !!},
            confirmButtonColor: '#0E4749',
            confirmButtonText: 'Mengerti',
            background: '#FFFFFF'
        });
    </script>
    @endif

    @if(session('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: {!! json_encode(session('warning')) !!},
            confirmButtonColor: '#0E4749',
            confirmButtonText: 'Mengerti',
            background: '#FFFFFF'
        });
    </script>
    @endif

    @if(session('info'))
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: {!! json_encode(session('info')) !!},
            confirmButtonColor: '#0E4749',
            confirmButtonText: 'OK',
            background: '#FFFFFF'
        });
    </script>
    @endif

    @if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Data Belum Lengkap / Tidak Sesuai',
            html: '<div class="text-start small mt-2"><ul class="mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>',
            confirmButtonColor: '#0E4749',
            confirmButtonText: 'Periksa Kembali',
            background: '#FFFFFF'
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
