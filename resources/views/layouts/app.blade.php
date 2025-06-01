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
            background-color: #ffffff !important;
            border-bottom: 2px solid #e9ecef;
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #dc3545 !important;
        }
        
        .nav-link {
            color: #495057 !important;
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 20px;
            transition: all 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: #e9ecef;
            color: #dc3545 !important;
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-search"></i> Lost & Found
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="bi bi-house"></i> Beranda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lost-items.index') }}">
                                <i class="bi bi-exclamation-triangle"></i> Barang Hilang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('found-items.index') }}">
                                <i class="bi bi-check-circle"></i> Barang Ditemukan
                            </a>
                        </li>
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.kategori.index') }}">
                                    <i class="bi bi-tags"></i> Kategori
                                </a>
                            </li>
                        @endif
                    </ul>
                    
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person"></i> {{ auth()->user()->nama }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><span class="dropdown-item-text">{{ auth()->user()->nim }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
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