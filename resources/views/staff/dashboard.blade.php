@extends('layouts.app')

@section('content')
<div class="container py-4 mb-5">
    <!-- Header Sapaan -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark mb-1">Halo, {{ auth()->user()->name ?? 'Kasir' }}!</h2>
        <p class="text-muted">Semangat menjalani harimu, berikan pelayanan terbaik di Seblak Ronyok.</p>
    </div>

    <!-- Kotak Statistik Utama (Model Outline Kasir) -->
    <div class="row g-4 justify-content-center mb-5">
        <!-- Total Pesanan -->
        <div class="col-md-5">
            <div class="p-4 text-center" style="border: 2px solid #2193b0; border-radius: 16px; background-color: transparent;">
                <span class="d-block text-uppercase fw-bold text-muted mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">Total Pesanan Hari Ini</span>
                <h2 class="fw-bold text-dark mb-1">{{ $totalTransactions ?? 4 }} Transaksi</h2>
                <small class="text-muted">Pesanan berhasil diproses hari ini.</small>
            </div>
        </div>

        <!-- Pendapatan Hari Ini -->
        <div class="col-md-5">
            <div class="p-4 text-center" style="border: 2px solid #2193b0; border-radius: 16px; background-color: transparent;">
                <span class="d-block text-uppercase fw-bold text-muted mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">Pendapatan Hari Ini</span>
                <h2 class="fw-bold text-dark mb-1">Rp {{ number_format($totalRevenue ?? 109000, 0, ',', '.') }}</h2>
                <small class="text-muted">Total pemasukan kasir hari ini.</small>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi Utama POS -->
    <div class="text-center mb-5">
        <a href="{{ route('menu') }}" class="btn btn-custom-blue text-white fw-bold px-5 py-3 rounded-pill shadow-sm" style="font-size: 1.05rem;">
            Buka Aplikasi POS Kasir Sekarang
        </a>
    </div>

    <!-- Bagian Rekomendasi Menu Terlaris -->
     <hr class="text-muted mb-2" style="opacity: 0.2;">
    <div class="mb-4">
        <h4 class="fw-bold text-dark text-center mb-4">Catatan Menu Terlaris</h4>
        
        <div class="row g-4">
            @forelse($popularProducts ?? [] as $product)
            <div class="col-md-4">
                <div class="card h-100 menu-card">
                    <div class="bg-custom-blue-gradient px-3 py-2 text-white d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.7rem;">Best Seller</span>
                        <span class="text-white small fw-bold text-uppercase" style="font-size: 0.75rem;">{{ $product->category->name ?? 'Menu' }}</span>
                    </div>
                    
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">{{ $product->name }}</h5>
                            <h5 class="text-primary fw-bold my-2">Rp {{ number_format($product->price ?? $product->selling_price ?? 0, 0, ',', '.') }}</h5>
                        </div>
                        
                        <a href="{{ route('menu') }}" class="btn btn-outline-primary btn-sm rounded-pill py-2 fw-bold mt-3">
                            Kelola di POS
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <!-- Contoh Tampilan Statis Jika Data Kosong -->
            <div class="col-md-4">
                <div class="card h-100 menu-card">
                    <div class="bg-custom-blue-gradient px-3 py-2 text-white d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.7rem;">Best Seller</span>
                        <span class="text-white small fw-bold text-uppercase" style="font-size: 0.75rem;">Kerupuk</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Kerupuk Mawar</h5>
                            <h5 class="text-primary fw-bold my-2">Rp 2.000</h5>
                        </div>
                        <a href="{{ route('menu') }}" class="btn btn-outline-primary btn-sm rounded-pill py-2 fw-bold mt-3">Kelola di POS</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 menu-card">
                    <div class="bg-custom-blue-gradient px-3 py-2 text-white d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.7rem;">Best Seller</span>
                        <span class="text-white small fw-bold text-uppercase" style="font-size: 0.75rem;">Topping</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Bakso Ayam</h5>
                            <h5 class="text-primary fw-bold my-2">Rp 2.500</h5>
                        </div>
                        <a href="{{ route('menu') }}" class="btn btn-outline-primary btn-sm rounded-pill py-2 fw-bold mt-3">Kelola di POS</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 menu-card">
                    <div class="bg-custom-blue-gradient px-3 py-2 text-white d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.7rem;">Best Seller</span>
                        <span class="text-white small fw-bold text-uppercase" style="font-size: 0.75rem;">Kerupuk</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Cuanki</h5>
                            <h5 class="text-primary fw-bold my-2">Rp 1.500</h5>
                        </div>
                        <a href="{{ route('menu') }}" class="btn btn-outline-primary btn-sm rounded-pill py-2 fw-bold mt-3">Kelola di POS</a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Styling Tema Gradasi Biru Khusus Kasir -->
<style>
    .bg-custom-blue-gradient {
        background: linear-gradient(135deg, #2193b0, #6dd5ed) !important;
    }
    .btn-custom-blue {
        background: linear-gradient(135deg, #2193b0, #6dd5ed) !important;
        border: none !important;
        transition: all 0.2s ease-in-out;
    }
    .btn-custom-blue:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(33, 147, 176, 0.3) !important;
    }
    .btn-outline-primary {
        color: #2193b0;
        border-color: #2193b0;
    }
    .btn-outline-primary:hover {
        background-color: #2193b0;
        border-color: #2193b0;
        color: #fff;
    }

    /* Efek Bayangan & Card Menu */
    .menu-card {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        background-color: #ffffff;
    }
    .menu-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(33, 147, 176, 0.15);
    }
</style>
@endsection