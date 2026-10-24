@extends('layouts.admin')

@section('content')
<div class="container mb-5">
    
    <!-- Bagian Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-0">Laporan Transaksi Pelanggan</h3>
            <p class="text-muted mb-0">Pantau seluruh riwayat pesanan dan pendapatan Seblak Ronyok.</p>
        </div>
        
        <!-- Kotak Total Pendapatan -->
        <div class="bg-white px-4 py-2 shadow-sm text-center" style="border-radius: 12px; border: 1px solid #ddd;">
            <span class="d-block text-muted fw-bold" style="font-size: 0.85rem;">Total Pendapatan</span>
            <h4 class="fw-bold text-dark mb-0">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h4>
        </div>
    </div>

    <!-- Kartu Grafik Pendapatan -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Grafik Pendapatan 7 Hari Terakhir</h5>
            <div style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Kartu Tabel Transaksi -->
    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body p-0">
          <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 bg-white shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <thead style="background: linear-gradient(135deg, #cb2d3e, #ef473a) !important; color: white;">
                        <tr>
                            <th class="py-3 px-4 text-white" style="background: transparent;">No</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">No. Nota</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Tanggal & Waktu</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Pelanggan</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Total Belanja</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Status</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- PASTIKAN MENGGUNAKAN $orders DI SINI -->
                        @forelse($orders as $index => $trx)
                        <tr>
                            <td class="py-3 px-4 fw-bold text-dark">{{ $orders->firstItem() + $index }}</td>
                            <td class="py-3 px-4 fw-bold text-danger">{{ $trx->invoice_number ?? $trx->sale_number ?? '-' }}</td>
                            <td class="py-3 px-4 text-muted">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                            <td class="py-3 px-4 fw-bold text-dark">{{ $trx->user->name ?? $trx->customer_name ?? 'Pelanggan' }}</td>
                            <td class="py-3 px-4 fw-bold text-dark">Rp {{ number_format($trx->total ?? $trx->total_price ?? $trx->total_amount ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                            </td>
                            <td class="py-3 px-4">
                                <!-- Tombol Lihat Struk disesuaikan dengan route di SaleController -->
                                <a href="{{ route('order.receipt', $trx->id) }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">Lihat Struk</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>  
        </div>
        
        <!-- Pagination -->
        @if(isset($orders) && $orders->hasPages())
        <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart');
        if(ctx) {
            const labels = {!! json_encode($chartLabels ?? []) !!};
            const data = {!! json_encode($chartValues ?? []) !!};

            new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Pendapatan (Rp)',
                        data: data,
                        backgroundColor: 'rgba(220, 53, 69, 0.6)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection