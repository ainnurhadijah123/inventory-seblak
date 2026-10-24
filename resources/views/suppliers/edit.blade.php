@extends('layouts.app')

@section('content')
<div class="container py-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="mb-4">
                        <h3 class="fw-bold text-dark mb-1">Edit Supplier</h3>
                        <p class="text-muted small">Perbarui informasi agen atau supplier bahan baku.</p>
                    </div>

                    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">NAMA SUPPLIER</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg bg-light border-0" value="{{ old('name', $supplier->name) }}" required style="border-radius: 12px; font-size: 0.95rem;">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">NO. TELEPON</label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-lg bg-light border-0" value="{{ old('phone', $supplier->phone) }}" style="border-radius: 12px; font-size: 0.95rem;">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">EMAIL</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg bg-light border-0" value="{{ old('email', $supplier->email) }}" style="border-radius: 12px; font-size: 0.95rem;">
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold text-muted">Kembali</a>
                            <button type="submit" class="btn btn-custom-red text-white px-5 py-2 rounded-pill fw-bold shadow-sm">Simpan Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-custom-red {
        background: linear-gradient(135deg, #cb2d3e, #ef473a) !important;
        border: none !important;
        transition: all 0.2s ease-in-out;
    }
    .btn-custom-red:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(203, 45, 62, 0.3) !important;
    }
    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 3px rgba(203, 45, 62, 0.15) !important;
        border: 1px solid #cb2d3e !important;
    }
</style>
@endsection