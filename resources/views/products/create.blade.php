@extends('layouts.admin')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="card-title fw-bold mb-4">Tambah Menu Seblak Baru</h4>
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA MENU / TOPPING</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <!-- Bagian Kategori yang Dimodifikasi -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">KATEGORI</label>
                        <select name="category_id" id="kategoriSelect" class="form-select" onchange="cekKategori()" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        
                        <!-- Catatan tersembunyi yang akan muncul otomatis -->
                        <div id="catatanKerupuk" class="text-danger fw-bold d-none mt-2" style="font-size: 0.85rem;">
                            *Catatan: Input harga jualper takaran 5 buah kerupuk.
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">HARGA BELI (MODAL)</label>
                            <input type="number" name="purchase_price" class="form-control" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">HARGA JUAL</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">STOK AWAL</label>
                            <input type="number" name="stock" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small text-muted">BATAS STOK MENIPIS (WARNING)</label>
                            <input type="number" name="minimum_stock" class="form-control" min="0" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2">Simpan Menu Baru</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk memunculkan catatan khusus Kerupuk -->
<script>
    function cekKategori() {
        let select = document.getElementById('kategoriSelect');
        let catatan = document.getElementById('catatanKerupuk');
        let namaKategori = select.options[select.selectedIndex].text.toLowerCase();

        if (namaKategori.includes('kerupuk')) {
            catatan.classList.remove('d-none');
            catatan.classList.add('d-block');
        } else {
            catatan.classList.add('d-none');
            catatan.classList.remove('d-block');
        }
    }
    
    // Memicu pengecekan otomatis saat halaman pertama kali dimuat
    document.addEventListener("DOMContentLoaded", function() {
        cekKategori();
    });
</script>
@endsection