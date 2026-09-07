<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Untung Klik') - Untung Klik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1a1d23;
            --sidebar-hover: #252830;
            --sidebar-active: #22c55e;
            --navbar-height: 60px;
            --body-bg: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-bg);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand i {
            color: var(--sidebar-active);
            font-size: 1.5rem;
        }

        .sidebar-brand span {
            color: #ffffff;
            font-size: 1.125rem;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .sidebar-user {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-user-name {
            color: #ffffff;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.125rem;
        }

        .sidebar-user-role {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            text-transform: capitalize;
        }

        .sidebar-nav {
            flex: 1;
            padding: 0.75rem 0;
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1.5rem;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 400;
            transition: all 0.15s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-nav-item:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
        }

        .sidebar-nav-item.active {
            color: #ffffff;
            background-color: rgba(34, 197, 94, 0.1);
            border-left-color: var(--sidebar-active);
        }

        .sidebar-nav-item.active i {
            color: var(--sidebar-active);
        }

        .sidebar-nav-item i {
            width: 20px;
            font-size: 0.9375rem;
            text-align: center;
        }

        .sidebar-section-label {
            padding: 1rem 1.5rem 0.375rem;
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-footer .btn-logout {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.625rem 0;
            color: rgba(255, 255, 255, 0.6);
            background: none;
            border: none;
            font-size: 0.875rem;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .sidebar-footer .btn-logout:hover {
            color: #ef4444;
        }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Navbar */
        .top-navbar {
            position: sticky;
            top: 0;
            height: var(--navbar-height);
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 999;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #374151;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.375rem;
            border-radius: 0.375rem;
            transition: background-color 0.15s ease;
        }

        .btn-sidebar-toggle:hover {
            background-color: #f3f4f6;
        }

        .navbar-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-dropdown {
            position: relative;
        }

        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: 1px solid #e5e7eb;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .user-dropdown-toggle:hover {
            background-color: #f9fafb;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--sidebar-active);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .user-info-mini {
            text-align: left;
        }

        .user-info-mini .name {
            font-size: 0.8125rem;
            font-weight: 500;
            color: #111827;
            line-height: 1.2;
        }

        .user-info-mini .role {
            font-size: 0.6875rem;
            color: #6b7280;
            text-transform: capitalize;
        }

        /* Content area */
        .content-area {
            padding: 1.5rem;
        }

        /* Mobile offcanvas */
        .sidebar-offcanvas .offcanvas {
            background-color: var(--sidebar-bg);
            width: var(--sidebar-width);
            border: none;
        }

        .sidebar-offcanvas .offcanvas-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-offcanvas .offcanvas-body {
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .btn-close-sidebar {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 0.25rem;
        }

        .btn-close-sidebar:hover {
            color: #ffffff;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .btn-sidebar-toggle {
                display: block;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    @auth
    <aside class="sidebar d-none d-lg-flex flex-column" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-wallet"></i>
            <span>Untung Klik</span>
        </div>

        <div class="sidebar-user">
            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
            <div class="sidebar-user-role">{{ auth()->user()->role }}</div>
        </div>

        <nav class="sidebar-nav">
            @if(auth()->user()->isOwner())
                <div class="sidebar-section-label">Menu Utama</div>
                <a href="{{ route('owner.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('owner.transactions.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-down"></i>
                    <span>Uang Masuk</span>
                </a>
                <a href="{{ route('owner.capital.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.capital.*') ? 'active' : '' }}">
                    <i class="fas fa-coins"></i>
                    <span>Modal</span>
                </a>
                <a href="{{ route('owner.expenses.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.expenses.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-up"></i>
                    <span>Uang Keluar</span>
                </a>
                <a href="{{ route('owner.categories.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Kategori</span>
                </a>

                <div class="sidebar-section-label">Laporan & Analisis</div>
                <a href="{{ route('owner.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan</span>
                </a>
                <a href="{{ route('owner.charts.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.charts.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Grafik</span>
                </a>

                <div class="sidebar-section-label">Pengaturan</div>
                <a href="{{ route('owner.users.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Manajemen Pengguna</span>
                </a>
                <a href="{{ route('owner.profile') }}" class="sidebar-nav-item {{ request()->routeIs('owner.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
            @else
                <div class="sidebar-section-label">Menu</div>
                <a href="{{ route('karyawan.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('karyawan.transactions.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-cash-register"></i>
                    <span>Penjualan</span>
                </a>
                <a href="{{ route('karyawan.transactions.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Transaksi</span>
                </a>

                <div class="sidebar-section-label">Laporan</div>
                <a href="{{ route('karyawan.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan Penjualan</span>
                </a>

                <div class="sidebar-section-label">Pengaturan</div>
                <a href="{{ route('karyawan.profile') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>
    @endauth

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Mobile Offcanvas Sidebar -->
    @auth
    <div class="offcanvas offcanvas-start sidebar-offcanvas d-lg-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-wallet" style="color: #22c55e; font-size: 1.25rem;"></i>
                <span style="color: #ffffff; font-size: 1rem; font-weight: 600;">Untung Klik</span>
            </div>
            <button type="button" class="btn-close-sidebar" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="sidebar-user">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">{{ auth()->user()->role }}</div>
            </div>

            <nav class="sidebar-nav">
                @if(auth()->user()->isOwner())
                    <div class="sidebar-section-label">Menu Utama</div>
                    <a href="{{ route('owner.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('owner.transactions.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.transactions.*') ? 'active' : '' }}">
                        <i class="fas fa-arrow-down"></i>
                        <span>Uang Masuk</span>
                    </a>
                    <a href="{{ route('owner.capital.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.capital.*') ? 'active' : '' }}">
                        <i class="fas fa-coins"></i>
                        <span>Modal</span>
                    </a>
                    <a href="{{ route('owner.expenses.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.expenses.*') ? 'active' : '' }}">
                        <i class="fas fa-arrow-up"></i>
                        <span>Uang Keluar</span>
                    </a>
                    <a href="{{ route('owner.categories.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Kategori</span>
                    </a>

                    <div class="sidebar-section-label">Laporan & Analisis</div>
                    <a href="{{ route('owner.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                    <a href="{{ route('owner.charts.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.charts.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Grafik</span>
                    </a>

                    <div class="sidebar-section-label">Pengaturan</div>
                    <a href="{{ route('owner.users.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                    <a href="{{ route('owner.profile') }}" class="sidebar-nav-item {{ request()->routeIs('owner.profile') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                @else
                    <div class="sidebar-section-label">Menu</div>
                    <a href="{{ route('karyawan.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('karyawan.transactions.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.transactions.*') ? 'active' : '' }}">
                        <i class="fas fa-cash-register"></i>
                        <span>Penjualan</span>
                    </a>
                    <a href="{{ route('karyawan.transactions.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.transactions.*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>Riwayat Transaksi</span>
                    </a>

                    <div class="sidebar-section-label">Laporan</div>
                    <a href="{{ route('karyawan.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan Penjualan</span>
                    </a>

                    <div class="sidebar-section-label">Pengaturan</div>
                    <a href="{{ route('karyawan.profile') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.profile') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endauth

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="navbar-left">
                <button type="button" class="btn-sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="navbar-title">@yield('title', 'Dashboard')</h1>
            </div>

            <div class="navbar-right">
                @auth
                <div class="user-dropdown">
                    <button class="user-dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info-mini d-none d-sm-block">
                            <div class="name">{{ auth()->user()->name }}</div>
                            <div class="role">{{ auth()->user()->role }}</div>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size: 0.625rem; color: #9ca3af;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 180px;">
                        @if(auth()->user()->isOwner())
                            <li><a class="dropdown-item" href="{{ route('owner.profile') }}"><i class="fas fa-user-circle me-2"></i>Profile</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('karyawan.profile') }}"><i class="fas fa-user-circle me-2"></i>Profile</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mobileSidebar = document.getElementById('mobileSidebar');

            if (window.innerWidth < 992) {
                const bsOffcanvas = bootstrap.Offcanvas.getInstance(mobileSidebar);
                if (bsOffcanvas) {
                    bsOffcanvas.toggle();
                } else {
                    const newOffcanvas = new bootstrap.Offcanvas(mobileSidebar);
                    newOffcanvas.show();
                }
            }
        }
    </script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{!! session('success') !!}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{!! session('error') !!}'
        });
    </script>
    @endif

    @if(session('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: '{!! session('warning') !!}'
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
