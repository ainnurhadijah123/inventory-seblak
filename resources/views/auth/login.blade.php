@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg border-0 p-4 p-md-5" style="width: 100%; max-width: 480px; border-radius: 20px; background-color: #ffffff;">
        
        <!-- Logo / Judul -->
        <div class="text-center mb-4">
            <h3 class="fw-bold" style="background: linear-gradient(135deg, #ff416c, #ff4b2b); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">🔥 Seblak Ronyok</h3>
            <p class="text-muted small">Silakan masuk untuk melanjutkan pesananmu.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger shadow-sm border-0 mb-3" style="border-radius: 10px;">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-bold text-muted" style="font-size: 0.85rem; letter-spacing: 0.5px;">ALAMAT EMAIL</label>
                <input type="email" name="email" id="email" class="form-control form-control-lg bg-light border-0" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus style="border-radius: 12px; font-size: 0.95rem;">
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-bold text-muted" style="font-size: 0.85rem; letter-spacing: 0.5px;">PASSWORD</label>
                <input type="password" name="password" id="password" class="form-control form-control-lg bg-light border-0" placeholder="••••••••" required style="border-radius: 12px; font-size: 0.95rem;">
            </div>

            <!-- Tombol Masuk Gradasi Merah ke Kuning -->
            <button type="submit" class="btn btn-custom-red-yellow text-white w-100 py-3 fw-bold shadow-sm mb-3" style="border-radius: 12px; font-size: 1rem;">
                Masuk Sistem
            </button>

            <!-- Tautan Register -->
            <div class="text-center mt-3">
                <small class="text-muted">Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: #ff416c;">Daftar Pelanggan Baru</a></small>
            </div>
        </form>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #fff1eb 0%, #ace0f9 100%) !important;
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%) !important;
    }

    .btn-custom-red-yellow {
        background: linear-gradient(135deg, #f857a6, #ff5858) !important; /* Bisa juga menggunakan #ff416c ke #ff4b2b / #f12711 ke #f5af19 */
        background: linear-gradient(135deg, #f12711, #f5af19) !important;
        border: none !important;
        transition: all 0.2s ease-in-out;
    }
    
    .btn-custom-red-yellow:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(241, 39, 17, 0.3) !important;
        color: #fff;
    }

    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 3px rgba(241, 39, 17, 0.15) !important;
        border: 1px solid #f12711 !important;
    }
</style>
@endsection