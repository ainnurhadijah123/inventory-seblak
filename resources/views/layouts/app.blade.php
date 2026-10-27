<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seblak Ronyok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @php
        $navbarBg = 'linear-gradient(135deg, #cb2d3e, #e94b80)'; 
        
        if(auth()->check()) {
            if(auth()->user()->role === 'staff') {
                $navbarBg = 'linear-gradient(135deg, #36D1DC, #5B86E5)'; 
            } elseif(auth()->user()->role === 'customer') {
                $navbarBg = 'linear-gradient(135deg, #9C27B0, #e94b80)'; 
            }
        }
    @endphp

    <style>
        body { background-color: #f8f9fa; padding-top: 70px; } 
        .navbar-custom { background: {{ $navbarBg }}; border-bottom: none; }
        .nav-link { color: rgba(255,255,255,0.8) !important; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: white !important; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Navbar Atas -->
@if(!request()->routeIs('login') && !request()->routeIs('register'))
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">🔥 Seblak Ronyok</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    
                    <!-- MENU DASHBOARD (Muncul untuk Staff & Customer) -->
                    @if(auth()->check())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @endif

                    <!-- MENU KHUSUS STAFF / KASIR -->
                    @if(auth()->check() && auth()->user()->role === 'staff')
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('menu') ? 'active' : '' }}" href="{{ route('menu') }}">Menu Kasir</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}" href="{{ route('orders.index') }}">Antrean Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Data Produk</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}" href="{{ route('purchases.index') }}">Stok Masuk</a></li>
                    
                    <!-- MENU KHUSUS CUSTOMER -->
                    @elseif(auth()->check() && auth()->user()->role === 'customer')
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('menu') ? 'active' : '' }}" href="{{ route('menu') }}">Pesan Seblak</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('my.orders') ? 'active' : '' }}" href="{{ route('my.orders') }}">Riwayat Pesanan</a></li>
                    @endif
                </ul>
                
                <!-- Profil & Logout -->
                <div class="d-flex align-items-center gap-3">
                    <span class="text-white fw-bold">👋 {{ auth()->check() ? ucfirst(auth()->user()->name) : 'Tamu' }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light fw-bold rounded-pill px-3 text-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
@endif
    <!-- Konten Utama -->
    <div class="container-fluid pb-5 pt-3">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>