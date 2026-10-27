@php
    $layout = 'layouts.app';
    if(auth()->check() && auth()->user()->role === 'admin') {
        $layout = 'layouts.admin';
    }
@endphp

@extends($layout)

@section('content')
<div class="container py-4 mb-5">
    
    <!-- Bagian Header -->
    <div class="mb-5">
        <h3 class="fw-bold text-custom-red mb-2">Dashboard Operasional</h3>
        <p class="text-muted">Selamat datang di sistem manajemen Warung Seblak.</p>
    </div>

    <!-- BARIS 1: KARTU KEUANGAN -->
    <div class="row mb-4">
        <!-- Kartu 1: Total Penjualan (Uang Masuk) -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 border-start border-5 border-custom-red hover-card h-100" style="background-color: #ffffff;">
                <div class="card-body py-4 text-center d-flex flex-column justify-content-center">
                    <p class="text-custom-red fw-bold text-uppercase mb-1 small">Total Penjualan (Kotor)</p>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($totalPenjualan ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Kartu 2: Total Pengeluaran (Uang Keluar) -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 border-start border-5 border-custom-red hover-card h-100" style="background-color: #ffffff;">
                <div class="card-body py-4 text-center d-flex flex-column justify-content-center">
                    <p class="text-custom-red fw-bold text-uppercase mb-1 small">Total Pengeluaran Stok</p>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Kartu 3: Pendapatan Bersih (Laba) -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 border-start border-5 border-custom-red hover-card h-100" style="background-color: #ffffff;">
                <div class="card-body py-4 text-center d-flex flex-column justify-content-center">
                    <p class="text-custom-red fw-bold text-uppercase mb-1 small">Pendapatan Bersih (Laba)</p>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($pendapatanBersih ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 2: KARTU OPERASIONAL -->
    <div class="row mb-5">
        <!-- Kartu 4: Total Menu & Produk -->
        <div class="col-md-6 mb-4">
            <a href="{{ route('products.index') }}" class="text-decoration-none d-block h-100">
                <div class="card shadow-sm border-0 border-start border-5 border-custom-red hover-card h-100" style="background-color: #ffffff;">
                    <div class="card-body py-4 text-center d-flex flex-column justify-content-center">
                        <p class="text-custom-red fw-bold text-uppercase mb-1 small">Total Menu & Produk</p>
                        <h2 class="fw-bold text-dark mb-0">{{ $totalProduk ?? 0 }} Item</h2>
                    </div>
                </div>
            </a>
        </div>

        <!-- Kartu 5: Peringatan Stok -->
        <div class="col-md-6 mb-4">
            <a href="{{ route('products.index') }}" class="text-decoration-none d-block h-100">
                <div class="card shadow-sm border-0 border-start border-5 border-custom-red hover-card h-100" style="background-color: #ffffff;">
                    <div class="card-body py-4 text-center d-flex flex-column justify-content-center align-items-center">
                        <p class="text-custom-red fw-bold text-uppercase mb-2 small">Peringatan Stok</p>
                        <h2 class="fw-bold mb-3 text-dark">{{ $stokMenipis ?? 0 }} Item Menipis</h2>
                        
                        @if(($stokMenipis ?? 0) > 0)
                            <span class="badge bg-warning text-dark rounded-pill px-4 py-2 shadow-sm fw-bold">Segera Isi Stok!</span>
                        @else
                            <span class="badge bg-success rounded-pill px-4 py-2 shadow-sm fw-bold">Stok Aman</span>
                        @endif
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- TABEL RIWAYAT TRANSAKSI TERBARU -->
    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="fw-bold mb-0 text-dark">Riwayat Transaksi Terbaru</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                @php
                    $dataTransaksiDashboard = \App\Models\Sale::with('user')->latest()->take(5)->get();
                @endphp
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: linear-gradient(135deg, #cb2d3e, #ef473a) !important; color: white;">
                        <tr>
                            <th class="py-3 px-4 text-white" style="background: transparent; border: none;">No. Transaksi</th>
                            <th class="py-3 px-4 text-white" style="background: transparent; border: none;">Waktu</th>
                            <th class="py-3 px-4 text-white" style="background: transparent; border: none;">Pelanggan</th>
                            <th class="py-3 px-4 text-white" style="background: transparent; border: none;">Total Bayar</th>
                            <th class="py-3 px-4 text-white" style="background: transparent; border: none;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataTransaksiDashboard as $trx)
                        <tr>
                            <td class="py-3 px-4 fw-bold text-custom-red">#INV-{{ $trx->id }}</td>
                            <td class="py-3 px-4 text-muted">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y, H:i') }}</td>
                            <td class="py-3 px-4">{{ $trx->customer_name ?? ($trx->user->name ?? 'Pelanggan Umum') }}</td>
                            <td class="py-3 px-4 fw-bold">Rp {{ number_format($trx->total ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .text-custom-red { color: #d90429 !important; }
    .border-custom-red { border-color: #d90429 !important; }
    
    .hover-card {
        transition: all 0.3s ease-in-out;
        border-radius: 12px;
    }
    .hover-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(229, 45, 39, 0.2) !important;
    }
    .table tbody tr {
        background-color: #ffffff !important;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }
</style>
@endsection