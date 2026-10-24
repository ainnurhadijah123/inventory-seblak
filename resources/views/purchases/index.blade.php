@php
    $layout = 'layouts.app'; // Untuk Staff (Top Navbar)
    if(auth()->check() && auth()->user()->role === 'admin') {
        $layout = 'layouts.admin'; // Khusus Admin (Sidebar)
    }
@endphp

@extends($layout)

@section('content')
<div class="container py-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Riwayat Stok Masuk (Restock)</h2>
            <p class="text-muted mb-0">Pantau seluruh catatan barang masuk dari supplier.</p>
        </div>
        <form action="{{ route('purchases.index') }}" method="GET" class="d-flex align-items-center bg-white shadow-sm mb-0" style="border-radius: 50px; overflow: hidden; border: 1px solid #ddd; width: 500px; height: 45px;">
            <input type="text" name="search" class="form-control border-0 px-3 py-2" placeholder="Cari stok baru..." value="{{ request('search') }}" style="box-shadow: none;">
                @if(request('search'))
                    <a href="{{ route('purchases.index') }}" class="btn btn-light border-0 text-danger fw-bold px-4 d-flex align-items-center" style="background-color: white; z-index: 10; border-radius: 0;" title="Batalkan Pencarian">
                        ✖
                    </a>
                @endif
            <button type="submit" class="btn {{ auth()->check() && auth()->user()->role === 'admin' ? 'btn-danger' : 'btn-info text-white' }} fw-bold" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                Cari Supplier
            </button>
                
        </form>
        <div>
            <a href="{{ route('purchases.create') }}" class="btn {{ auth()->check() && auth()->user()->role === 'staff' ? 'btn-custom-blue' : 'btn-custom-red' }} text-white fw-bold px-4 py-2 rounded-pill shadow-sm">
                + Catat Stok Baru
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <!-- Header Tabel Dinamis: Biru untuk Staff, Merah untuk Admin -->
                    <thead style="background: {{ auth()->check() && auth()->user()->role === 'staff' ? 'linear-gradient(135deg, #2193b0, #6dd5ed)' : 'linear-gradient(135deg, #cb2d3e, #ef473a)' }} !important; color: white;">
                        <tr>
                            <th class="py-3 px-4 text-white" style="background: transparent;">No</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">No. Nota</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Tanggal</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Supplier</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Total Biaya</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases ?? [] as $index => $purchase)
                        <tr>
                            <td class="py-3 px-4 fw-bold text-dark">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4 fw-bold">
                                <a href="#" class="text-decoration-none {{ auth()->check() && auth()->user()->role === 'staff' ? 'text-primary' : 'text-danger' }}">
                                    {{ $purchase->invoice_number ?? 'PO-'.$purchase->id }}
                                </a>
                            </td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($purchase->created_at)->format('d M Y, H:i') }}</td>
                            <td class="py-3 px-4">{{ $purchase->supplier->name ?? '-' }}</td>
                            <td class="py-3 px-4 fw-bold">Rp {{ number_format($purchase->total_cost ?? $purchase->total ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-muted">{{ $purchase->user->name ?? 'Admin Warung' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat stok masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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