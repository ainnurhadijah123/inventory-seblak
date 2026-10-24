@extends('layouts.app')

@section('content')
<div class="container py-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="mb-4">
                        <h3 class="fw-bold text-dark mb-1">Edit Produk</h3>
                        <p class="text-muted small">Perbarui informasi menu, harga, atau stok produk.</p>
                    </div>

                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">NAMA MENU</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg bg-light border-0" value="{{ old('name', $product->name) }}" required style="border-radius: 12px; font-size: 0.95rem;">
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">KATEGORI</label>
                            <select name="category_id" id="category_id" class="form-select form-select-lg bg-light border-0" style="border-radius: 12px; font-size: 0.95rem;">
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="purchase_price" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">HARGA MODAL (Rp)</label>
                                <input type="number" name="purchase_price" id="purchase_price" class="form-control form-control-lg bg-light border-0" value="{{ old('purchase_price', $product->purchase_price) }}" required style="border-radius: 12px; font-size: 0.95rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">HARGA JUAL (Rp)</label>
                                <input type="number" name="price" id="price" class="form-control form-control-lg bg-light border-0" value="{{ old('price', $product->price ?? $product->selling_price) }}" required style="border-radius: 12px; font-size: 0.95rem;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="stock" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">STOK</label>
                            <input type="number" name="stock" id="stock" class="form-control form-control-lg bg-light border-0" value="{{ old('stock', $product->stock) }}" required style="border-radius: 12px; font-size: 0.95rem;">
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('products.index') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold text-muted">Kembali</a>
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
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 3px rgba(203, 45, 62, 0.15) !important;
        border: 1px solid #cb2d3e !important;
    }
</style>
@endsection