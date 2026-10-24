@extends('layouts.app')

@section('content')
<div class="container py-4 mb-5">
    <!-- Header Sapaan -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark mb-1">Halo, {{ auth()->user()->name ?? 'Pelanggan' }}!</h2>
        <p class="text-muted">Selamat datang di Seblak Ronyok. Seblak kesukaanmu di sini tempatnya.</p>
    </div>

    <!-- Kotak Statistik Utama -->
    <div class="row g-4 justify-content-center mb-5">
        <div class="col-md-6">
            <div class="p-4 text-center" style="border: 2px solid #9d50bb; border-radius: 16px; background-color: transparent;">
                <span class="d-block text-uppercase fw-bold text-muted mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">Total Pesanan Anda</span>
                <h2 class="fw-bold text-dark mb-1">{{ $totalOrders ?? 3 }} Kali</h2>
                <small class="text-muted">Terima kasih sudah menjadi pelanggan setia kami!</small>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi Utama Pesan Seblak -->
    <div class="text-center mb-5">
        <a href="{{ route('menu') }}" class="btn btn-custom-purple text-white fw-bold px-5 py-3 rounded-pill shadow-sm" style="font-size: 1.05rem;">
            Pesan Seblak Sekarang
        </a>
    </div>

    <!-- Bagian Rekomendasi Menu Terlaris -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark text-center mb-4">Rekomendasi Menu Terlaris</h4>
        
        <div class="row g-4">
            @forelse($popularProducts ?? [] as $product)
            <div class="col-md-4">
                <div class="card h-100 menu-card-purple">
                    <div class="bg-custom-purple-gradient px-3 py-2 text-white d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.7rem;">Best Seller</span>
                        <span class="text-white small fw-bold text-uppercase" style="font-size: 0.75rem;">{{ $product->category->name ?? 'Menu' }}</span>
                    </div>
                    
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">{{ $product->name }}</h5>
                            <h5 class="text-danger fw-bold my-2">Rp {{ number_format($product->price ?? $product->selling_price ?? 0, 0, ',', '.') }}</h5>
                        </div>
                        
                        <a href="{{ route('menu', ['add' => $product->id]) }}" class="btn btn-outline-purple btn-sm rounded-pill py-2 fw-bold mt-3">
                            Tambah ke Pesanan
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-md-4">
                <div class="card h-100 menu-card-purple">
                    <div class="bg-custom-purple-gradient px-3 py-2 text-white d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.7rem;">Best Seller</span>
                        <span class="text-white small fw-bold text-uppercase" style="font-size: 0.75rem;">Kerupuk</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Kerupuk Mawar</h5>
                            <h5 class="text-danger fw-bold my-2">Rp 2.000</h5>
                        </div>
                        <a href="{{ route('menu') }}" class="btn btn-outline-purple btn-sm rounded-pill py-2 fw-bold mt-3">Tambah ke Pesanan</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 menu-card-purple">
                    <div class="bg-custom-purple-gradient px-3 py-2 text-white d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.7rem;">Best Seller</span>
                        <span class="text-white small fw-bold text-uppercase" style="font-size: 0.75rem;">Topping</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Bakso Ayam</h5>
                            <h5 class="text-danger fw-bold my-2">Rp 2.500</h5>
                        </div>
                        <a href="{{ route('menu') }}" class="btn btn-outline-purple btn-sm rounded-pill py-2 fw-bold mt-3">Tambah ke Pesanan</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 menu-card-purple">
                    <div class="bg-custom-purple-gradient px-3 py-2 text-white d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.7rem;">Best Seller</span>
                        <span class="text-white small fw-bold text-uppercase" style="font-size: 0.75rem;">Kerupuk</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Cuanki</h5>
                            <h5 class="text-danger fw-bold my-2">Rp 1.500</h5>
                        </div>
                        <a href="{{ route('menu') }}" class="btn btn-outline-purple btn-sm rounded-pill py-2 fw-bold mt-3">Tambah ke Pesanan</a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .bg-custom-purple-gradient {
        background: linear-gradient(135deg, #9d50bb, #e94b80) !important;
    }
    .btn-custom-purple {
        background: linear-gradient(135deg, #9d50bb, #e94b80) !important;
        border: none !important;
        transition: all 0.2s ease-in-out;
    }
    .btn-custom-purple:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(157, 80, 187, 0.3) !important;
    }
    .btn-outline-purple {
        color: #9d50bb;
        border-color: #9d50bb;
    }
    .btn-outline-purple:hover {
        background-color: #9d50bb;
        border-color: #9d50bb;
        color: #fff;
    }

    .menu-card-purple {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        background-color: #ffffff;
    }
    .menu-card-purple:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(157, 80, 187, 0.15);
    }
</style>
@endsection