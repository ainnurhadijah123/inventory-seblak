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
            <h2 class="fw-bold text-dark mb-1">Master Supplier</h2>
            <p class="text-muted mb-0">Kelola data agen dan supplier bahan baku.</p>
        </div>

        <div>
            <a href="{{ route('suppliers.create') }}" class="btn btn-custom-red text-white fw-bold px-4 py-2 rounded-pill shadow-sm">
                + Tambah Supplier
            </a>
        </div>
    </div>

    <!-- Tabel langsung ditampilkan tanpa dibungkus card putih -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 bg-white shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <thead style="background: linear-gradient(135deg, #cb2d3e, #ef473a) !important; color: white;">
                <tr>
                    <th class="py-3 px-4 text-white" style="background: transparent;">No</th>
                    <th class="py-3 px-4 text-white" style="background: transparent;">Nama Supplier</th>
                    <th class="py-3 px-4 text-white" style="background: transparent;">No. Telepon</th>
                    <th class="py-3 px-4 text-white" style="background: transparent;">Email</th>
                    <th class="py-3 px-4 text-white" style="background: transparent;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers ?? [] as $index => $supplier)
                <tr>
                    <td class="py-3 px-4 fw-bold text-dark">{{ $loop->iteration }}</td>
                    <td class="py-3 px-4 fw-bold text-dark">{{ $supplier->name }}</td>
                    <td class="py-3 px-4">{{ $supplier->phone ?? '-' }}</td>
                    <td class="py-3 px-4 text-muted">{{ $supplier->email ?? '-' }}</td>
                    <td class="py-3 px-4">
                        <div class="d-flex gap-2">
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-bold text-dark">Edit</a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada data supplier.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
</style>
@endsection