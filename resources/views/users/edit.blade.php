@extends('layouts.app')

@section('content')
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4 text-dark text-center">Edit Role Pengguna</h4>
                    
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small mb-1">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control form-control-lg" value="{{ $user->name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small mb-1">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg" value="{{ $user->email }}" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Pilih Role Akses Baru</label>
                            <select name="role" class="form-select form-select-lg">
                                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
                                <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Kasir (Staff POS)</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin (Pemilik)</option>
                            </select>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-lg w-50 rounded-pill fw-bold">Batal</a>
                            <button type="submit" class="btn btn-danger btn-lg w-50 rounded-pill fw-bold shadow-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection