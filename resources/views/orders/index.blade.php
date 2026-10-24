@php
    $layout = 'layouts.app'; // Untuk Staff (Top Navbar)
    if(auth()->check() && auth()->user()->role === 'admin') {
        $layout = 'layouts.admin'; // Khusus Admin (Sidebar)
    }
@endphp

@extends($layout)

@section('content')
<div class="container py-4 mb-5">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Antrean Pesanan Masuk</h2>
        <p class="text-muted mb-0">Pantau dan selesaikan antrean pesanan dari customer di sini.</p>
    </div>

    <!-- Blok session success dihapus dari sini karena sudah ditangani oleh layout utama -->

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-custom-blue-gradient text-white">
                        <tr>
                            <th class="py-3 px-4 text-white">No. Transaksi</th>
                            <th class="py-3 px-4 text-white">Tanggal & Waktu</th>
                            <th class="py-3 px-4 text-white">Total Tagihan</th>
                            <th class="py-3 px-4 text-white">Status</th>
                            <th class="py-3 px-4 text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders ?? [] as $order)
                        <tr>
                            <td class="py-3 px-4 fw-bold text-dark">{{ $order->invoice_number ?? 'ORD-'.str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</td>
                            <td class="py-3 px-4 fw-bold">Rp {{ number_format($order->total ?? $order->total_price ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                @if($order->status == 'completed' || $order->status == 'Selesai')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <form action="{{ route('orders.complete', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">Tuntas</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada antrean pesanan aktif.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-custom-blue-gradient {
        background: linear-gradient(135deg, #2193b0, #6dd5ed) !important;
    }
    .bg-custom-blue-gradient th {
        color: #ffffff !important;
        background-color: transparent !important;
    }
</style>
@endsection