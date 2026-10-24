@extends('layouts.app')

@section('content')
<div class="container py-4 mb-5">
    <h4 class="fw-bold mb-4">Riwayat Pesanan Saya</h4>

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <!-- Header dengan Gradasi Ungu -->
                    <thead class="bg-custom-purple-gradient text-white">
                        <tr>
                            <th class="py-3 px-4 text-white">No. Transaksi</th>
                            <th class="py-3 px-4 text-white">Tanggal Pesanan</th>
                            <th class="py-3 px-4 text-white">Total Tagihan</th>
                            <th class="py-3 px-4 text-white">Status Dapur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="py-3 px-4 fw-bold">ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3 px-4">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="py-3 px-4">Rp {{ number_format($order->total ?? $order->total_amount ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                @if(isset($order->status) && $order->status === 'completed')
                                    <span class="badge bg-success rounded-pill px-3 py-2">✔ Sudah Disajikan</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">🍳 Sedang Dimasak...</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <p class="mb-2">Anda belum pernah memesan seblak.</p>
                                <a href="{{ route('menu') }}" class="btn btn-custom-purple text-white btn-sm rounded-pill px-4 py-2 fw-bold">Pesan Sekarang</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Styling Gradasi Ungu -->
<style>
    .bg-custom-purple-gradient {
        background: linear-gradient(135deg, #9d50bb, #e94b80) !important;
    }
    .bg-custom-purple-gradient th {
        color: #ffffff !important;
        background-color: transparent !important;
    }
    .btn-custom-purple {
        background: linear-gradient(135deg, #9d50bb, #e94b80) !important;
        border: none !important;
    }
    .btn-custom-purple:hover {
        opacity: 0.9;
    }
</style>
@endsection