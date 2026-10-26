@extends('layouts.app')

@section('content')
@php
    // Deteksi role untuk menyesuaikan tema warna secara dinamis
    $themeBg = 'linear-gradient(135deg, #9d50bb, #e94b80)'; // Default Ungu/Pink
    $themeText = '#e94b80'; 
    $btnClass = 'btn-custom-purple';

    if(auth()->check()) {
        if(auth()->user()->role === 'staff') {
            $themeBg = 'linear-gradient(135deg, #2193b0, #6dd5ed)'; // Biru untuk Staff
            $themeText = '#2193b0'; 
            $btnClass = 'btn-custom-blue';
        }
    }
@endphp

<div class="container ">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5" style="max-width: 450px;">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                
                <!-- HEADER CARD DISESUAIKAN TEMA -->
                <div class="card-header text-white fw-bold text-center border-0" style="border-radius: 15px 15px 0 0; padding: 15px; background: {{ $themeBg }};">
                    Rincian Konfirmasi Pesanan
                </div>
                
                <div class="card-body p-4">
                    
                    @if($customerName)
                        <div class="mb-3 border-bottom pb-2">
                            <span class="text-muted small">Atas Nama:</span>
                            <h6 class="fw-bold mb-0">{{ $customerName }}</h6>
                        </div>
                    @endif

                    <div class="mb-3 border-bottom pb-2">
                        <span class="text-muted small">Tingkat Kepedasan:</span>
                        <!-- Teks level pedas pakai warna tema -->
                        <h6 class="fw-bold mb-0" style="color: {{ $themeText }};">
                            Level {{ $spicyLevel }} 
                            @if($spicyCost > 0) (+Rp {{ number_format($spicyCost, 0, ',', '.') }}) @endif
                        </h6>
                    </div>

                    <span class="text-muted small">Daftar Pesanan:</span>
                    <ul class="list-group list-group-flush mb-4 mt-2">
                        @foreach($selectedItems as $item)
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold">{{ $item['name'] }}</div>
                                    <small class="text-muted">{{ $item['qty'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</small>
                                </div>
                                <span class="fw-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light" style="border-radius: 10px;">
                        <span class="fw-bold text-dark mb-0">TOTAL PEMBAYARAN</span>
                        <!-- Angka total pakai warna tema -->
                        <h4 class="fw-bold mb-0" style="color: {{ $themeText }};">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h4>
                    </div>

                    <!-- Form Lanjutan ke Penyimpanan (Store) -->
                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        
                        {{-- Mengirim ulang data --}}
                        @if(isset($orderData['customer_name']))
                            <input type="hidden" name="customer_name" value="{{ $orderData['customer_name'] }}">
                        @endif
                        <input type="hidden" name="spicy_level" value="{{ $orderData['spicy_level'] }}">
                        
                        @foreach($orderData['items'] as $id => $qty)
                            <input type="hidden" name="items[{{ $id }}]" value="{{ $qty }}">
                        @endforeach
                        
                        <div class="d-grid gap-2">
                            <!-- TOMBOL LANJUTKAN DISESUAIKAN TEMA -->
                            <button type="submit" class="btn {{ $btnClass }} text-white fw-bold py-3 rounded-pill shadow-sm">
                                Ya, Lanjutkan & Cetak Struk
                            </button>
                            
                            <a href="javascript:history.back()" class="btn btn-outline-secondary fw-bold py-2 rounded-pill">
                                Kembali untuk Ubah Pesanan
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- STYLE TEMA TOMBOL -->
<style>
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