@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="container py-4">
        <div class="card mx-auto shadow-sm" style="max-width: 400px;">
            <div class="card-body p-4 p-md-5 bg-white">
                
                <!-- Header Struk -->
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark mb-0">🔥 Seblak Ronyok</h3>
                    <p class="text-muted small mb-0">Jl. Medan-Banda Aceh, Krueng Geukuh</p>
                    <p class="text-muted small">Telp: 0812-3456-7890</p>
                </div>
                
                <!-- Info Pesanan -->
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">No. Pesanan:</span>
                    <span class="fw-bold text-dark">ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Tanggal:</span>
                    <span class="text-dark">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="d-flex justify-content-between small mb-3">
                    <span class="text-muted">Nama Pelanggan:</span>
                    <span class="text-dark fw-semibold">{{ $order->customer_name ?? $order->user->name ?? auth()->user()->name }}</span>
                </div>
                
                <hr style="border-top: 2px dashed #ccc;">
                
                @php
                    // Menghitung subtotal murni dari item yang dipesan
                    $subtotalProduk = 0;
                @endphp

                <!-- Daftar Menu yang Dipesan -->
                <table class="table table-borderless table-sm mb-0 small text-dark">
                    @foreach($order->items as $item)
                    @php
                        $hargaSatuan = $item->unit_price ?? $item->price ?? 0;
                        $subtotalItem = $item->subtotal ?? ($hargaSatuan * $item->quantity);
                        $subtotalProduk += $subtotalItem;
                    @endphp
                    <tr>
                        <td class="px-0 py-1" style="width: 65%;">
                            {{ $item->product->name ?? 'Produk' }}<br>
                            <span class="text-muted">{{ $item->quantity }} x Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-0 py-1 text-end align-middle fw-bold">
                            Rp {{ number_format($subtotalItem, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach

                    @php
                        // Selisih antara Total Tagihan dengan Subtotal Produk adalah Biaya Level Pedas
                        $biayaLevel = max(0, ($order->total ?? 0) - $subtotalProduk);
                        
                        // Menebak level pedas jika kolom spicy_level belum ada di database
                        $daftarLevel = [1000 => 2, 2000 => 3, 3000 => 4, 5000 => 5];
                        $infoLevel = $order->spicy_level ?? $order->level ?? ($daftarLevel[$biayaLevel] ?? null);
                    @endphp

                    <!-- Baris Rincian Level Pedas -->
                    <tr class="border-top">
                        <td class="px-0 pt-2 pb-1" style="width: 65%;">
                            🔥 Level Pedas {{ $infoLevel !== null ? '(Level ' . $infoLevel . ')' : '' }}<br>
                            <span class="text-muted">{{ $biayaLevel > 0 ? 'Biaya tambahan cabai' : 'Gratis (Level 0 / 1)' }}</span>
                        </td>
                        <td class="px-0 pt-2 pb-1 text-end align-middle fw-bold">
                            Rp {{ number_format($biayaLevel, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
                
                <hr style="border-top: 2px dashed #ccc;">
                
                <!-- Total Pembayaran -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold fs-6 text-dark">Total Tagihan:</span>
                    <span class="fw-bold fs-5 text-danger">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
                
                <!-- Footer Struk -->
                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">Terima kasih atas pesanan Anda!</p>
                    <p class="small text-muted">Silakan selesaikan pembayaran di kasir.</p>
                </div>

            </div>
        </div>

        <div class="text-center mt-4 d-print-none gap-2 d-flex justify-content-center">
            <button onclick="window.print()" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                Cetak Struk
            </button>
            
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.transactions') }}" class="btn btn-outline-dark fw-bold px-4 rounded-pill bg-white shadow-sm">
                    Kembali ke Laporan
                </a>
            @elseif(auth()->user()->role === 'staff')
                <a href="{{ route('orders.index') }}" class="btn btn-outline-dark fw-bold px-4 rounded-pill bg-white shadow-sm">
                    Kembali ke Antrean
                </a>
            @else
                <a href="{{ route('menu') }}" class="btn btn-outline-dark fw-bold px-4 rounded-pill">
                    Kembali Menu
                </a>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        body { background: white !important; }
        .navbar, .custom-navbar, .alert { display: none !important; }
        .card { box-shadow: none !important; border: none !important; }
    }
</style>
@endsection