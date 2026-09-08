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
            height: 100dvh;
            background-color: var(--sidebar-bg);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            flex-shrink: 0;
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
            flex-shrink: 0;
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
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.75rem 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.25) rgba(0, 0, 0, 0.25);
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.25);
            border-radius: 10px;
            margin: 6px 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.25);
            border-radius: 10px;
            transition: background-color 0.2s ease;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background-color: var(--sidebar-active);
        }

        .sidebar-nav::-webkit-scrollbar-thumb:active {
            background-color: #16a34a;
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
            background-color: var(--sidebar-bg);
            flex-shrink: 0;
            margin-top: auto;
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
            display: flex;
            flex-direction: column;
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
            flex: 1 0 auto;
        }

        /* Footer */
        .main-footer {
            background-color: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
            text-align: center;
            color: #6b7280;
            font-size: 0.8125rem;
            margin-top: auto;
        }

        /* Mobile offcanvas */
        .sidebar-offcanvas.offcanvas,
        .offcanvas.sidebar-offcanvas {
            background-color: var(--sidebar-bg);
            width: var(--sidebar-width);
            border: none;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .sidebar-offcanvas .offcanvas-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            flex-shrink: 0;
        }

        .sidebar-offcanvas .offcanvas-body {
            padding: 0;
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            min-height: 0;
            overflow: hidden;
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

        /* Stock Movement Badges */
        .badge-stok-initial { background-color: #2563eb !important; color: #ffffff !important; }
        .badge-stok-in { background-color: #16a34a !important; color: #ffffff !important; }
        .badge-stok-out { background-color: #ea580c !important; color: #ffffff !important; }
        .badge-stok-sale { background-color: #7c3aed !important; color: #ffffff !important; }
        .badge-stok-adjustment { background-color: #d97706 !important; color: #ffffff !important; }
        .badge-stok-damaged { background-color: #dc2626 !important; color: #ffffff !important; }
        .badge-stok-lost { background-color: #334155 !important; color: #ffffff !important; }
        .badge-stok-correction { background-color: #0d9488 !important; color: #ffffff !important; }

        /* Bootstrap 4 Legacy Badge Fallbacks in Bootstrap 5 */
        .badge-success { background-color: #16a34a !important; color: #ffffff !important; }
        .badge-danger { background-color: #dc2626 !important; color: #ffffff !important; }
        .badge-info { background-color: #0284c7 !important; color: #ffffff !important; }
        .badge-warning { background-color: #d97706 !important; color: #ffffff !important; }
        .badge-secondary { background-color: #64748b !important; color: #ffffff !important; }
        .badge-primary { background-color: #2563eb !important; color: #ffffff !important; }
        /* Modern Stat Cards Design System */
        .modern-stat-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.85);
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.02);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .modern-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 28px -6px rgba(15, 23, 42, 0.09), 0 6px 10px -4px rgba(15, 23, 42, 0.04);
            border-color: rgba(203, 213, 225, 1);
        }

        .modern-stat-card.accent-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        .modern-stat-card.accent-danger::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }

        .modern-stat-card.accent-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #2563eb);
        }

        .modern-stat-card.accent-warning::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }

        .modern-stat-card.accent-purple::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #8b5cf6, #7c3aed);
        }

        .modern-stat-card.accent-teal::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #14b8a6, #0d9488);
        }

        .modern-stat-card.accent-dark::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #475569, #1e293b);
        }

        .stat-icon-pod {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
            transition: transform 0.25s ease;
        }

        .modern-stat-card:hover .stat-icon-pod {
            transform: scale(1.08);
        }

        .stat-icon-pod.pod-green {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.16) 0%, rgba(22, 163, 74, 0.08) 100%);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.25);
        }

        .stat-icon-pod.pod-red {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.16) 0%, rgba(220, 38, 38, 0.08) 100%);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .stat-icon-pod.pod-blue {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.16) 0%, rgba(37, 99, 235, 0.08) 100%);
            color: #2563eb;
            border: 1px solid rgba(59, 130, 246, 0.25);
        }

        .stat-icon-pod.pod-amber {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.16) 0%, rgba(217, 119, 6, 0.08) 100%);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        .stat-icon-pod.pod-purple {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.16) 0%, rgba(124, 58, 237, 0.08) 100%);
            color: #7c3aed;
            border: 1px solid rgba(139, 92, 246, 0.25);
        }

        .stat-icon-pod.pod-teal {
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.16) 0%, rgba(13, 148, 136, 0.08) 100%);
            color: #0d9488;
            border: 1px solid rgba(20, 184, 166, 0.25);
        }

        .stat-icon-pod.pod-slate {
            background: linear-gradient(135deg, rgba(100, 116, 139, 0.16) 0%, rgba(71, 85, 105, 0.08) 100%);
            color: #475569;
            border: 1px solid rgba(100, 116, 139, 0.25);
        }

        .stat-label-text {
            font-size: 0.785rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0;
        }

        .stat-value-text {
            font-size: clamp(1.25rem, 1.5vw, 1.55rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.25;
            margin-bottom: 0;
            white-space: nowrap;
        }

        .stat-card-footer {
            border-top: 1px solid rgba(241, 245, 249, 1);
            padding-top: 0.65rem;
            margin-top: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            color: #64748b;
        }

        .stat-card-footer a {
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s ease;
        }

        .stat-card-footer a:hover {
            transform: translateX(3px);
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
                <a href="{{ route('owner.sales.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.sales.*') ? 'active' : '' }}">
                    <i class="fas fa-cash-register"></i>
                    <span>Penjualan Harian</span>
                </a>

                <div class="sidebar-section-label">Produk & Stok</div>
                <a href="{{ route('owner.products.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Daftar Produk</span>
                </a>
                <a href="{{ route('owner.stock.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.stock.*') ? 'active' : '' }}">
                    <i class="fas fa-cubes"></i>
                    <span>Stok & Mutasi</span>
                </a>
                <a href="{{ route('owner.product-categories.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.product-categories.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>
                    <span>Kategori Produk</span>
                </a>

                <div class="sidebar-section-label">Buku Kas Digital</div>
                <a href="{{ route('owner.transactions.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-down"></i>
                    <span>Uang Masuk</span>
                </a>
                <a href="{{ route('owner.capital.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.capital.*') ? 'active' : '' }}">
                    <i class="fas fa-coins"></i>
                    <span>Modal Usaha</span>
                </a>
                <a href="{{ route('owner.expenses.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.expenses.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-up"></i>
                    <span>Uang Keluar</span>
                </a>
                <a href="{{ route('owner.categories.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Kategori Kas</span>
                </a>

                <div class="sidebar-section-label">Laporan & Analisis</div>
                <a href="{{ route('owner.reports.sales') }}" class="sidebar-nav-item {{ request()->routeIs('owner.reports.sales') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Laporan Penjualan</span>
                </a>
                <a href="{{ route('owner.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.reports.index') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan Keuangan</span>
                </a>
                <a href="{{ route('owner.charts.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.charts.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Grafik Arus Kas</span>
                </a>

                <div class="sidebar-section-label">Pengaturan</div>
                <a href="{{ route('owner.users.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Manajemen Pengguna</span>
                </a>
                <a href="{{ route('owner.profile') }}" class="sidebar-nav-item {{ request()->routeIs('owner.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>Profil Usaha</span>
                </a>
            @else
                <div class="sidebar-section-label">Menu Utama</div>
                <a href="{{ route('karyawan.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('karyawan.sales.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.sales.*') ? 'active' : '' }}">
                    <i class="fas fa-cash-register"></i>
                    <span>Kasir & Penjualan</span>
                </a>
                <a href="{{ route('karyawan.transactions.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-down"></i>
                    <span>Catat Kas Masuk</span>
                </a>

                <div class="sidebar-section-label">Laporan</div>
                <a href="{{ route('karyawan.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan Penjualan</span>
                </a>

                <div class="sidebar-section-label">Pengaturan</div>
                <a href="{{ route('karyawan.profile') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>Profil</span>
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="button" class="btn-logout" onclick="confirmLogout(event)">
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
                    <a href="{{ route('owner.sales.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.sales.*') ? 'active' : '' }}">
                        <i class="fas fa-cash-register"></i>
                        <span>Penjualan Harian</span>
                    </a>

                    <div class="sidebar-section-label">Produk & Stok</div>
                    <a href="{{ route('owner.products.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.products.*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>Daftar Produk</span>
                    </a>
                    <a href="{{ route('owner.stock.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.stock.*') ? 'active' : '' }}">
                        <i class="fas fa-cubes"></i>
                        <span>Stok & Mutasi</span>
                    </a>
                    <a href="{{ route('owner.product-categories.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.product-categories.*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i>
                        <span>Kategori Produk</span>
                    </a>

                    <div class="sidebar-section-label">Buku Kas Digital</div>
                    <a href="{{ route('owner.transactions.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.transactions.*') ? 'active' : '' }}">
                        <i class="fas fa-arrow-down"></i>
                        <span>Uang Masuk</span>
                    </a>
                    <a href="{{ route('owner.capital.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.capital.*') ? 'active' : '' }}">
                        <i class="fas fa-coins"></i>
                        <span>Modal Usaha</span>
                    </a>
                    <a href="{{ route('owner.expenses.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.expenses.*') ? 'active' : '' }}">
                        <i class="fas fa-arrow-up"></i>
                        <span>Uang Keluar</span>
                    </a>
                    <a href="{{ route('owner.categories.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Kategori Kas</span>
                    </a>

                    <div class="sidebar-section-label">Laporan & Analisis</div>
                    <a href="{{ route('owner.reports.sales') }}" class="sidebar-nav-item {{ request()->routeIs('owner.reports.sales') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Laporan Penjualan</span>
                    </a>
                    <a href="{{ route('owner.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.reports.index') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                    <a href="{{ route('owner.charts.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.charts.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Grafik Arus Kas</span>
                    </a>

                    <div class="sidebar-section-label">Pengaturan</div>
                    <a href="{{ route('owner.users.index') }}" class="sidebar-nav-item {{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                    <a href="{{ route('owner.profile') }}" class="sidebar-nav-item {{ request()->routeIs('owner.profile') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Profil Usaha</span>
                    </a>
                @else
                    <div class="sidebar-section-label">Menu Utama</div>
                    <a href="{{ route('karyawan.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('karyawan.sales.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.sales.*') ? 'active' : '' }}">
                        <i class="fas fa-cash-register"></i>
                        <span>Kasir & Penjualan</span>
                    </a>
                    <a href="{{ route('karyawan.transactions.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.transactions.*') ? 'active' : '' }}">
                        <i class="fas fa-arrow-down"></i>
                        <span>Catat Kas Masuk</span>
                    </a>

                    <div class="sidebar-section-label">Laporan</div>
                    <a href="{{ route('karyawan.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan Penjualan</span>
                    </a>

                    <div class="sidebar-section-label">Pengaturan</div>
                    <a href="{{ route('karyawan.profile') }}" class="sidebar-nav-item {{ request()->routeIs('karyawan.profile') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Profil</span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="button" class="btn-logout" onclick="confirmLogout(event)">
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
                                <button type="button" class="dropdown-item text-danger" onclick="confirmLogout(event)">
                                    <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            {{-- Alert Notifikasi Status Operasi --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 5px solid #10b981 !important; border-radius: 8px;">
                    <i class="fas fa-check-circle fs-5 me-3 text-success"></i>
                    <div class="flex-grow-1">
                        <strong class="d-block mb-1">Berhasil!</strong>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 5px solid #ef4444 !important; border-radius: 8px;">
                    <i class="fas fa-exclamation-circle fs-5 me-3 text-danger"></i>
                    <div class="flex-grow-1">
                        <strong class="d-block mb-1">Gagal Memproses Permintaan:</strong>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0" role="alert" style="background-color: #fffbeb; color: #92400e; border-left: 5px solid #f59e0b !important; border-radius: 8px;">
                    <i class="fas fa-exclamation-triangle fs-5 me-3 text-warning"></i>
                    <div class="flex-grow-1">
                        <strong class="d-block mb-1">Perhatian:</strong>
                        <span>{{ session('warning') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0" role="alert" style="background-color: #eff6ff; color: #1e40af; border-left: 5px solid #3b82f6 !important; border-radius: 8px;">
                    <i class="fas fa-info-circle fs-5 me-3 text-primary"></i>
                    <div class="flex-grow-1">
                        <strong class="d-block mb-1">Informasi:</strong>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm border-0" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 5px solid #ef4444 !important; border-radius: 8px;">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-times-circle fs-5 me-2 text-danger"></i>
                        <strong class="fs-6">Terdapat beberapa data yang belum lengkap atau perlu diperbaiki:</strong>
                    </div>
                    <ul class="mb-0 ps-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <p class="mb-0">{{ config('services.copyright') }}</p>
        </footer>
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

        function confirmLogout(event) {
            if (event) event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function confirmDelete(event, message = 'Data yang dihapus tidak dapat dikembalikan!') {
            if (event) event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus Data',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: {!! json_encode(session('success')) !!},
            timer: 3500,
            timerProgressBar: true,
            confirmButtonColor: '#22c55e',
            confirmButtonText: 'Tutup'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Memproses Permintaan',
            text: {!! json_encode(session('error')) !!},
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Mengerti'
        });
    </script>
    @endif

    @if(session('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: {!! json_encode(session('warning')) !!},
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'Mengerti'
        });
    </script>
    @endif

    @if(session('info'))
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: {!! json_encode(session('info')) !!},
            confirmButtonColor: '#3b82f6',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

    @if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Data Belum Lengkap / Tidak Sesuai',
            html: '<div class="text-start small mt-2"><ul class="mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>',
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Periksa Kembali'
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
