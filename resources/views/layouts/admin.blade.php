<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Seblak Ronyok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff5f5; }
        .sidebar {
            width: 260px; min-height: 100vh;
            background: linear-gradient(135deg, #cb2d3e, #ef473a);
            position: fixed; top: 0; left: 0; z-index: 100;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8); padding: 12px 20px;
            border-radius: 8px; margin-bottom: 5px; transition: 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2); color: #ffffff; font-weight: bold;
        }
        .main-content { margin-left: 260px; padding: 20px; min-height: 100vh; }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column p-3">
        <a href="/" class="d-flex align-items-center mb-4 text-white text-decoration-none px-2">
            <h4 class="fw-bold mb-0">🔥 Seblak Ronyok</h4>
        </a>
        <ul class="nav flex-column mb-auto">
            <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Master Produk</a></li>
            <li><a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">Stok Masuk</a></li>
            <li><a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">Master Supplier</a></li>
            <li><a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">Master Kategori</a></li>
            <li><a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">Kelola Pengguna</a></li>
            <li><a href="{{ route('admin.transactions') }}" class="nav-link {{ request()->routeIs('admin.transactions') ? 'active' : '' }}">Laporan Transaksi</a></li>
            <li><a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">Pantau Pesanan Masuk</a></li>
        </ul>
        <hr class="text-white">
        <div class="d-flex justify-content-between align-items-center px-2">
            <span class="text-white fw-bold text-truncate">👋 {{ ucfirst(auth()->user()->name ?? 'Admin') }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-light fw-bold text-danger">Logout</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>