@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="card-title fw-bold text-dark mb-4">Catat Stok Masuk (Restock)</h4>
                
                <form action="{{ route('purchases.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PILIH PRODUK / BAHAN</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kolom Supplier diubah menjadi input teks (form) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA SUPPLIER / AGEN</label>
                        <input type="text" name="supplier_name" class="form-control" placeholder="Ketik nama agen atau supplier..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">JUMLAH DITAMBAHKAN (Qty)</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">TOTAL BIAYA KULAKAN (Rp)</label>
                        <input type="number" name="total_cost" class="form-control" min="0" required>
                    </div>

                    <button type="submit" class="btn {{ auth()->check() && auth()->user()->role === 'staff' ? 'btn-custom-blue' : 'btn-custom-red' }} text-white w-100 py-3 rounded-pill fw-bold shadow-sm" style="font-size: 1rem;">
                        Simpan & Tambah Stok
                    </button>
                </form>
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
</style>
@endsection