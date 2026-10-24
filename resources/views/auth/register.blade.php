@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="auth-card">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-danger mb-1">🔥 Seblak Ronyok</h4>
            <p class="text-muted small">Buat akun pelanggan baru</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold text-uppercase">Nama Lengkap</label>
                <input type="text" name="name" class="form-control auth-input" placeholder="Nama lengkap Anda" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold text-uppercase">Alamat Email</label>
                <input type="email" name="email" class="form-control auth-input" placeholder="admin@gmail.com" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold text-uppercase">Password</label>
                <input type="password" name="password" class="form-control auth-input" placeholder="••••••••" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label text-muted small fw-bold text-uppercase">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control auth-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-auth">Daftar Sekarang</button>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted small mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-danger fw-bold text-decoration-none">Login di sini</a></p>
        </div>
    </div>
</div>
<style>
    /* 1. Background seragam dengan halaman Login (Gradasi Peach/Oranye) */
    body {
        background: linear-gradient(135deg, #fff1eb 0%, #ace0f9 100%) !important;
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%) !important;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
    }

    /* 2. Ukuran Card dikunci agar lebarnya persis sama dengan Login */
    .auth-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        width: 100%;
        max-width: 450px; 
        padding: 40px 30px;
    }

    /* 3. Warna Input seragam (Biru pucat) */
    .auth-input {
        background-color: #eff3f6;
        border: 1px solid transparent;
        border-radius: 8px;
        padding: 12px 15px;
    }
    .auth-input:focus {
        background-color: #ffffff;
        border: 1px solid #ef3b24;
        box-shadow: 0 0 0 0.2rem rgba(239, 59, 36, 0.15);
    }

    /* 4. Tombol disamakan menjadi Gradasi Oranye */
    .btn-auth {
        background: linear-gradient(90deg, #ef3b24, #f5a623);
        border: none;
        color: white;
        font-weight: bold;
        padding: 12px;
        border-radius: 8px;
        width: 100%;
        transition: all 0.3s ease;
    }
    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(245, 166, 35, 0.4);
        color: white;
    }
</style>

@endsection