<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lost and Found')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 0.5rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #ffffff !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
        }
        
        .navbar-brand i {
            font-size: 1.8rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.95) !important;
            font-weight: 500;
            font-size: 0.95rem;
            margin: 0 0.2rem;
            padding: 0.6rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff !important;
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.3);
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: #ffffff;
            border-radius: 2px;
        }
        
        .navbar-nav .nav-link i {
            font-size: 1.2rem;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border-radius: 12px;
            margin-top: 0.5rem;
            padding: 0.5rem;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .dropdown-item {
            padding: 0.7rem 1rem;
            transition: all 0.2s;
            border-radius: 8px;
            margin: 0.2rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(5px);
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
            opacity: 0.1;
        }
        
        .badge {
            font-size: 0.65rem;
            padding: 0.3rem 0.6rem;
            border-radius: 12px;
            font-weight: 600;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.95)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
        }
        
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
        }
        
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            border-radius: 15px 15px 0 0 !important;
            font-weight: bold;
            padding: 1rem 1.5rem;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .container {
            max-width: 1200px;
        }
        
        .main-content {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }
        
        .footer {
            background-color: #ffffff;
            border-top: 2px solid #e9ecef;
            padding: 2rem 0;
            margin-top: 3rem;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="bi bi-box-seam"></i>
                <span class="ms-2">Lost & Found</span>
            </a>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                @auth
                    <!-- Main Menu -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-house-door-fill"></i>
                                <span class="ms-1">Beranda</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('lost-items*') ? 'active' : '' }}" href="{{ route('lost-items.index') }}">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span class="ms-1">Barang Hilang</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('found-items*') ? 'active' : '' }}" href="{{ route('found-items.index') }}">
                                <i class="bi bi-check-circle-fill"></i>
                                <span class="ms-1">Barang Ditemukan</span>
                            </a>
                        </li>
                        
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->is('admin/*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear-fill"></i>
                                    <span class="ms-1">Admin</span>
                                </a>
                                <ul class="dropdown-menu shadow-lg">
                                    <li><h6 class="dropdown-header">Manajemen Sistem</h6></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.verifikasi.index') }}">
                                        <i class="bi bi-check-square-fill"></i> Verifikasi Laporan
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.claims.index') }}">
                                        <i class="bi bi-clipboard-check-fill"></i> Kelola Klaim
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.statistics') }}">
                                        <i class="bi bi-graph-up"></i> Statistik
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Master Data</h6></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.kategori.index') }}">
                                        <i class="bi bi-tags-fill"></i> Kategori
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        <i class="bi bi-people-fill"></i> Kelola User
                                    </a></li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('my-claims*') ? 'active' : '' }}" href="{{ route('claims.my-claims') }}">
                                    <i class="bi bi-clipboard-check-fill"></i>
                                    <span class="ms-1">Klaim Saya</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                    
                    <!-- Right Menu -->
                    <ul class="navbar-nav align-items-lg-center gap-1">
                        <!-- Chat -->
                        <li class="nav-item">
                            @if(auth()->user()->isAdmin())
                                <a class="nav-link position-relative" href="{{ route('admin.messages.index') }}" title="Chat">
                                    <i class="bi bi-chat-dots-fill"></i>
                                </a>
                            @else
                                <a class="nav-link position-relative" href="{{ route('messages.index') }}" title="Chat">
                                    <i class="bi bi-chat-dots-fill"></i>
                                </a>
                            @endif
                        </li>

                        <!-- Notifications -->
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('notifications.index') }}" title="Notifikasi">
                                <i class="bi bi-bell-fill"></i>
                                @if(auth()->user()->unreadNotificationsCount() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ auth()->user()->unreadNotificationsCount() }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        <!-- User Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="bi bi-person-fill" style="color: #667eea; font-size: 1.2rem;"></i>
                                </div>
                                <div class="d-none d-lg-flex flex-column align-items-start" style="line-height: 1.2;">
                                    <span style="font-size: 0.85rem; font-weight: 600;">{{ Str::limit(auth()->user()->nama, 15) }}</span>
                                    <small style="font-size: 0.7rem; opacity: 0.8;">{{ auth()->user()->role }}</small>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                                <li class="px-3 py-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-fill text-primary" style="font-size: 1.3rem;"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ auth()->user()->nama }}</div>
                                            <small class="text-muted">{{ auth()->user()->nim }}</small>
                                        </div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider my-2"></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a></li>
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-shield-check"></i> Admin Panel
                                    </a></li>
                                @endif
                                <li><hr class="dropdown-divider my-2"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @else
                    <!-- Guest Menu -->
                    <ul class="navbar-nav ms-auto gap-2">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span class="ms-1">Login</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i>
                                <span class="ms-1">Register</span>
                            </a>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Success Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Error Messages -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">2025 Lost and Found System. Dibuat Oleh Kelompok 3 SI4701.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html> 