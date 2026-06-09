<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GreensaInn Admin - Panel Kontrol')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- FontAwesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Admin-Specific CSS Customizations -->
    <style>
        :root {
            --primary-color: #0f4c5c;
            --primary-dark: #0a3641;
            --primary-light: #e6f0f2;
            --accent-color: #fb8b24;
            --dark-color: #111b21;
            --sidebar-width: 260px;
            --light-bg: #f4f7f9;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-bg);
            color: #495057;
            overflow-x: hidden;
            display: flex;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--dark-color);
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--dark-color);
            color: rgba(255, 255, 255, 0.7);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: var(--transition-smooth);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 24px;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.4rem;
            color: white !important;
            text-decoration: none;
            letter-spacing: -0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-brand span {
            color: var(--accent-color);
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 12px;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-item {
            margin-bottom: 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.7) !important;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition-smooth);
            gap: 12px;
        }

        .sidebar-link:hover, .sidebar-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.08);
        }

        .sidebar-link.active {
            background-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(15, 76, 92, 0.3);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Main Content wrapper */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header Navbar */
        .admin-header {
            background-color: white;
            border-bottom: 1px solid rgba(15, 76, 92, 0.08);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .admin-content {
            padding: 30px;
            flex-grow: 1;
        }

        /* Sidebar Toggle Button (Mobile) */
        .toggle-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-color);
            cursor: pointer;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border: 1px solid rgba(15, 76, 92, 0.05);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 5px 20px rgba(15, 76, 92, 0.02);
            transition: var(--transition-smooth);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(15, 76, 92, 0.06);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        /* Table Styling */
        .table-responsive {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid rgba(15, 76, 92, 0.05);
            box-shadow: 0 5px 20px rgba(15, 76, 92, 0.02);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            border-bottom: 1.5px solid #f1f3f5;
            padding: 15px 10px;
        }

        .table td {
            padding: 15px 10px;
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f3f5;
        }

        /* Status Badges */
        .badge-status {
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.75rem;
        }

        .badge-status.success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .badge-status.warning {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        .badge-status.danger {
            background-color: #ffebee;
            color: #c62828;
        }

        /* Responsive Layout adjustments */
        @media (max-width: 991.98px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.active {
                left: 0;
            }
            .main-wrapper {
                margin-left: 0;
            }
            .toggle-btn {
                display: block;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="adminSidebar">
        <a href="{{ url('/admin') }}" class="sidebar-brand">
            <img src="{{ asset('assets/images/logoGreenSa.jpeg') }}" alt="Logo" style="width: 200px; height: 120px;" />
        </a>
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="{{ url('/admin') }}" class="sidebar-link {{ Request::is('admin') || Request::is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.ruangan.index') }}" class="sidebar-link {{ Request::is('admin/ruangan*') ? 'active' : '' }}">
                    <i class="fa-solid fa-door-open"></i> Kelola Ruangan
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.fasilitas.index') }}" class="sidebar-link {{ Request::is('admin/fasilitas*') ? 'active' : '' }}">
                    <i class="fa-solid fa-couch"></i> Masterdata Fasilitas
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ url('/admin/reviews') }}" class="sidebar-link {{ Request::is('admin/reviews') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-shield"></i> Peninjauan Booking
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ url('/') }}" class="sidebar-link" target="_blank">
                    <i class="fa-solid fa-globe"></i> Lihat Landing Page
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="submit" class="sidebar-link text-danger w-100 border-0 bg-transparent text-start"
                    onclick="return confirm('Yakin ingin keluar dari sesi admin?')">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Workspace -->
    <div class="main-wrapper">
        <!-- Top Navbar Header -->
        <header class="admin-header">
            <div class="d-flex align-items-center gap-3">
                <button class="toggle-btn" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h4 class="m-0 fs-5 d-none d-sm-block">@yield('page_title', 'Dashboard Overview')</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications dropdown placeholder -->
                <div class="dropdown">
                    <button class="btn btn-light position-relative p-2 rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px;">
                        <i class="fa-regular fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-white rounded-circle"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2" style="width: 280px;">
                        <li class="px-3 py-2 fw-bold text-dark border-bottom border-light">Notifikasi Terbaru</li>
                        <li><a class="dropdown-item py-2 border-bottom border-light" href="#"><span class="small d-block fw-semibold">Pemesanan Baru</span><span class="text-muted small">Synergy Seminar Hall oleh Clara Amanda</span></a></li>
                        <li><a class="dropdown-item py-2 text-center small text-primary fw-semibold" href="#">Lihat Semua</a></li>
                    </ul>
                </div>
                
                <!-- Admin User profile widget -->
                <div class="d-flex align-items-center gap-2 border-start ps-3 border-light">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=150&auto=format&fit=crop" class="rounded-circle border border-2 border-light shadow-sm" style="width: 40px; height: 40px; object-fit: cover;" alt="Admin Avatar">
                    <div class="d-none d-md-block">
                        <div class="small fw-bold text-dark lh-sm">{{ Auth::user()->name }}</div>
                        <span class="text-muted small" style="font-size: 0.75rem;">{{ Auth::user()->role }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5.3.3 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('active');
        }
    </script>
    @yield('scripts')
</body>
</html>
