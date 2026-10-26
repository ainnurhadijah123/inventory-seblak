@extends('layouts.app')

@section('content')
@php
    // Jika yang login adalah staff, gunakan biru. Jika customer, gunakan ungu.
    $theme = (auth()->check() && auth()->user()->role === 'staff') ? 'primary' : 'danger';
    $btnClass = (auth()->check() && auth()->user()->role === 'staff') ? 'btn-custom-blue' : 'btn-custom-purple';
@endphp
<div class="container py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Menu Spesial Seblak</h2>
        <p class="text-muted mb-3">Pilih sendiri isian seblakmu!</p>

        <form action="{{ route('menu') }}" method="GET" class="mb-4">
            <div class="input-group input-group-lg shadow-sm" style="border-radius: 50px; overflow: hidden; max-width: 800px;">
                <input type="text" name="search" class="form-control border-0 px-4" placeholder="Cari topping, kerupuk, atau minuman..." value="{{ request('search') }}">

                @if(request('search'))
                    <a href="{{ route('menu') }}" class="btn btn-light border-0 text-danger fw-bold px-4 d-flex align-items-center" style="background-color: white; z-index: 10; border-radius: 0;" title="Batalkan Pencarian">
                        ✖
                    </a>
                @endif

                <button type="submit" class="btn {{ $btnClass }} text-white px-4 px-md-5 fw-bold border-0" style="z-index: 10;">
                    Cari Menu
                </button>
        
            </div>
        </form>
    </div>

    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div>
    @endif

    <!-- FORM PEMESANAN UTAMA -->
    <form action="{{ route('order.confirm') }}" method="POST" id="orderForm">
        @csrf

        @if(auth()->check() && auth()->user()->role === 'staff')
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold text-dark mb-3">ATAS NAMA:</h5>
                <input type="text" name="customer_name" class="form-control form-control-lg rounded-pill" placeholder="Ketik nama pelanggan di sini..." required>
                <small class="text-muted ms-2 mt-2 d-block">*Wajib diisi agar pesanan tidak tertukar.</small>
            </div>
        </div>
        @endif

        <!-- 1. PILIH LEVEL PEDAS -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">PILIH LEVEL SEBLAK</h5>
                <select name="spicy_level" id="spicyLevel" class="form-select form-select-lg" onchange="calculateTotal()" required>
                    <option value="0" data-price="0" selected>Level 0 - Tidak Pedas (Gratis)</option>
                    <option value="1" data-price="0">Level 1 - Sedang (Gratis)</option>
                    <option value="2" data-price="1000">Level 2 - Pedas Standar (+Rp 1.000)</option>
                    <option value="3" data-price="2000">Level 3 - Pedas Mercon (+Rp 2.000)</option>
                </select>
                <small class="text-muted d-block mt-2">*Level 2 ke atas dikenakan biaya tambahan cabai.</small>
            </div>
        </div>

        @php
            $makanan = $products->filter(function($p) {
                return strtolower($p->category->name ?? '') !== 'minuman';
            });
            $minuman = $products->filter(function($p) {
                return strtolower($p->category->name ?? '') === 'minuman';
            });
        @endphp

        <!-- 2. PEMISAH MENU MAKANAN -->
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <h5 class="fw-bold mb-0">PILIH TOPPING & KERUPUK</h5>
                <hr class="flex-grow-1 ms-3 text-secondary">
            </div>

            <div class="row g-3">
                @forelse($makanan as $product)
                @php
                    $hargaProduk = $product->price ?? $product->selling_price ?? 0;
                @endphp
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 text-center p-3" style="border-radius: 15px;">
                        <div class="card-body d-flex flex-column justify-content-between p-1">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">{{ $product->name }}</h6>
                                <span class="badge bg-light text-secondary border mb-2">{{ $product->category->name ?? 'Topping' }}</span>
                                
                                @if(strtolower($product->category->name ?? '') === 'kerupuk')
                                    <div class="text-warning small fw-bold mb-1" style="font-size: 0.75rem;">
                                        *Harga per 5 keping
                                    </div>
                                @endif

                                <h6 class="text-danger fw-bold my-2">Rp {{ number_format($hargaProduk, 0, ',', '.') }}</h6>
                                <small class="text-muted d-block mb-3">Stok: {{ $product->stock }}</small>
                            </div>

                            <div class="input-group input-group-sm justify-content-center mx-auto" style="max-width: 130px;">
                                <button type="button" class="btn btn-outline-danger px-2" onclick="changeQty({{ $product->id }}, -1)">-</button>
                                <input type="number" 
                                    name="items[{{ $product->id }}]" 
                                    id="qty-{{ $product->id }}" 
                                    class="form-control fw-bold text-center item-qty px-1" 
                                    value="0" 
                                    min="0" 
                                    max="{{ $product->stock }}" 
                                    data-price="{{ $hargaProduk }}"
                                    oninput="calculateTotal()"
                                    style="width: 50px;">
                                <button type="button" class="btn btn-outline-danger px-2" onclick="changeQty({{ $product->id }}, 1)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-muted fst-italic">Stok topping & kerupuk sedang kosong.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- 3. PEMISAH MENU MINUMAN -->
        <div class="mb-4 mt-5">
            <div class="d-flex align-items-center mb-3">
                <h5 class="fw-bold mb-0">PILIHAN MINUMAN</h5>
                <hr class="flex-grow-1 ms-3 text-secondary">
            </div>

            <div class="row g-3">
                @forelse($minuman as $product)
                @php
                    $hargaMinuman = $product->price ?? $product->selling_price ?? 0;
                @endphp
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 text-center p-3" style="border-radius: 15px; background-color: #fdfdfd;">
                        <div class="card-body d-flex flex-column justify-content-between p-1">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">{{ $product->name }}</h6>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info mb-2">Minuman</span>
                                <h6 class="text-danger fw-bold my-2">Rp {{ number_format($hargaMinuman, 0, ',', '.') }}</h6>
                                <small class="text-muted d-block mb-3">Stok: {{ $product->stock }}</small>
                            </div>

                            <div class="input-group input-group-sm justify-content-center mx-auto" style="max-width: 130px;">
                                <button type="button" class="btn btn-outline-danger px-2" onclick="changeQty({{ $product->id }}, -1)">-</button>
                                <input type="number" 
                                    name="items[{{ $product->id }}]" 
                                    id="qty-{{ $product->id }}" 
                                    class="form-control text-center item-qty px-1" 
                                    value="0" 
                                    min="0" 
                                    max="{{ $product->stock }}" 
                                    data-price="{{ $hargaMinuman }}"
                                    oninput="calculateTotal()"
                                    style="width: 50px;">
                                <button type="button" class="btn btn-outline-danger px-2" onclick="changeQty({{ $product->id }}, 1)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-muted fst-italic">Belum ada menu minuman yang tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Jarak ruang ekstra agar konten terbawah tidak tertutup tombol fixed-bottom -->
        <div style="height: 100px;"></div>

    </form>
    <!-- AKHIR FORM PEMESANAN UTAMA -->

</div>

<!-- PANEL PEMBAYARAN BAWAH -->
@php
    // Set default warna (Merah untuk Admin/Guest)
    $themeBg = 'linear-gradient(135deg, #cb2d3e, #ef473a)'; 
    $themeText = '#cb2d3e'; 

    if(auth()->check()) {
        if(auth()->user()->role === 'staff') {
            // Biru/Tosca untuk Kasir
            $themeBg = 'linear-gradient(135deg, #36D1DC, #5B86E5)'; 
            $themeText = '#5B86E5'; 
        } elseif(auth()->user()->role === 'customer') {
            // Gradasi Ungu ke Pink
            $themeBg = 'linear-gradient(135deg, #9C27B0, #E91E63)'; 
            $themeText = '#E91E63'; 
        }
    }
@endphp

<div class="bg-white border-top shadow-lg p-3 fixed-bottom" style="z-index: 1030;">
    <div class="container d-flex justify-content-center align-items-center gap-4">
        
        <div class="text-end">
            <p class="text-muted mb-0 small">Estimasi Total Pembayaran:</p>
            <h3 class="fw-bold mb-0" style="color: {{ $themeText }};" id="totalBelanjaDisplay">Rp 0</h3>
        </div>
        
        <div>
            <!-- type="submit" dan form="orderForm" agar berfungsi men-submit pesanan -->
            <button type="submit" form="orderForm" id="btn-proses" class="btn text-white fw-bold rounded-pill px-4 py-2 shadow-sm" style="background: {{ $themeBg }};" disabled>
                Pesan Sekarang (<span id="totalItemDisplay">0</span> Item)
            </button>
        </div>

    </div>
</div>

<script>
    function changeQty(id, delta) {
        const input = document.getElementById('qty-' + id);
        let current = parseInt(input.value) || 0;
        let max = parseInt(input.getAttribute('max')) || 0;
        let next = current + delta;

        if (next >= 0 && next <= max) {
            input.value = next;
            calculateTotal();
        }
    }

    function calculateTotal() {
        let itemsTotal = 0;
        let countItems = 0;

        document.querySelectorAll('.item-qty').forEach(input => {
            let qty = parseInt(input.value) || 0;
            let price = parseInt(input.getAttribute('data-price')) || 0;
            if (qty > 0) {
                itemsTotal += (qty * price);
                countItems += qty;
            }
        });

        const spicySelect = document.getElementById('spicyLevel');
        const spicyPrice = parseInt(spicySelect.options[spicySelect.selectedIndex].getAttribute('data-price')) || 0;

        let grandTotal = countItems > 0 ? (itemsTotal + spicyPrice) : 0;

        document.getElementById('totalBelanjaDisplay').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        document.getElementById('totalItemDisplay').innerText = countItems;

        const btnProses = document.getElementById('btn-proses');
        if (countItems > 0) {
            btnProses.removeAttribute('disabled');
        } else {
            btnProses.setAttribute('disabled', 'true');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const addProductId = urlParams.get('add');

        if (addProductId) {
            const qtyInput = document.getElementById('qty-' + addProductId);
            if (qtyInput) {
                let currentVal = parseInt(qtyInput.value) || 0;
                let maxVal = parseInt(qtyInput.getAttribute('max')) || 99;
                
                if (currentVal < maxVal) {
                    qtyInput.value = currentVal + 1;
                    calculateTotal();
                }
            }
        }
    });
</script>

<style>
    /* Tema Gradasi Ungu (Customer) */
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

    /* Tema Gradasi Biru (Staff / Kasir) */
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

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
        text-align: center !important; 
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
</style>
@endsection