@extends('layouts.admin')

@section('content')
<div class="container py-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Pantau Pesanan Masuk</h2>
            <p class="text-muted mb-0">Pantau seluruh antrean pesanan dari customer berdasarkan tanggal.</p>
        </div>
    </div>

    <!-- Form Filter Tanggal -->
    <div class="mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <form action="{{ route('orders.index') }}" method="GET" class="d-flex gap-3 align-items-end">
                <div>
                    <label for="tanggal" class="form-label fw-bold text-muted small mb-1">Pilih Tanggal Pesanan</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ request('tanggal') }}" style="border-radius: 8px;">
                </div>
                <div>
                    <button type="submit" class="btn text-white fw-bold px-4" style="background: linear-gradient(135deg, #cb2d3e, #ef473a); border-radius: 8px;">
                        Filter
                    </button>
                    <a href="{{ route('orders.index') }}" class="btn btn-light border fw-bold px-4" style="border-radius: 8px;">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Pesanan Admin -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 bg-white shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <thead style="background: linear-gradient(135deg, #cb2d3e, #ef473a) !important; color: white;">
                <tr>
                    <th class="py-3 px-4 text-white" style="background: transparent;">No</th>
                    <th class="py-3 px-4 text-white" style="background: transparent;">No. Transaksi</th>
                    <th class="py-3 px-4 text-white" style="background: transparent;">Tanggal & Waktu</th>
                    <th class="py-3 px-4 text-white" style="background: transparent;">Total Tagihan</th>
                    <th class="py-3 px-4 text-white" style="background: transparent;">Status</th>
                    <th class="py-3 px-4 text-white" style="background: transparent;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $index => $order)
                <tr>
                    <td class="py-3 px-4 fw-bold text-dark">{{ (method_exists($orders, 'firstItem') ? $orders->firstItem() + $index : $loop->iteration) }}</td>
                    <td class="py-3 px-4 fw-bold text-danger">{{ $order->invoice_number ?? $order->sale_number ?? ('ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT)) }}</td>
                    <td class="py-3 px-4 text-muted">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    <td class="py-3 px-4 fw-bold text-dark">Rp {{ number_format($order->total ?? $order->total_price ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        @if($order->status == 'completed' || $order->status == 'selesai')
                            <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                        @else
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <a href="{{ route('order.receipt', $order->id) }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">Lihat Struk</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Tidak ada pesanan ditemukan pada tanggal ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginasi -->
    @if(isset($orders) && method_exists($orders, 'hasPages') && $orders->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection