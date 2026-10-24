@extends(auth()->check() && auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="container py-4">
    <!-- Judul, Search Bar, dan Tombol Tambah dalam Satu Baris -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Master Produk</h2>
            <p class="text-muted mb-0">Kelola data menu, harga, dan stok seblak.</p>
        </div>

        <!-- Bagian Tengah: Form Pencarian -->
        <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center bg-white shadow-sm mb-0" style="border-radius: 50px; overflow: hidden; border: 1px solid #ddd; width: 500px; height: 45px;">
            <input type="text" name="search" class="form-control border-0 px-3 py-2" placeholder="Cari topping, kerupuk, atau menu..." value="{{ request('search') }}" style="box-shadow: none;">
                @if(request('search'))
                    <a href="{{ route('products.index') }}" class="btn btn-light border-0 text-danger fw-bold px-4 d-flex align-items-center" style="background-color: white; z-index: 10; border-radius: 0;" title="Batalkan Pencarian">
                        ✖
                    </a>
                @endif
            <button type="submit" class="btn {{ auth()->check() && auth()->user()->role === 'admin' ? 'btn-danger' : 'btn-info text-white' }} fw-bold">
                Cari Produk
            </button>
                
        </form>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('products.create') }}" class="btn btn-danger fw-bold rounded-pill px-4">
            + Tambah Produk
        </a>
        @endif

    </div>
    
    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <!-- Menggunakan Inline Style langsung agar warna gradient pasti muncul -->
                    <thead style="background: {{ auth()->check() && auth()->user()->role === 'staff' ? 'linear-gradient(135deg, #2193b0, #6dd5ed)' : 'linear-gradient(135deg, #cb2d3e, #ef473a)' }} !important; color: white;">
                        <tr>
                            <th class="py-3 px-4 text-white" style="background: transparent;">No</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Nama Menu</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Kategori</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Harga Modal</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Harga Jual</th>
                            <th class="py-3 px-4 text-white" style="background: transparent;">Stok</th>
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <th class="py-3 px-4 text-white" style="background: transparent;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products ?? [] as $index => $product)
                        <tr>
                            <td class="py-3 px-4 fw-bold text-dark">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4 fw-bold text-dark">{{ $product->name }}</td>
                            <td class="py-3 px-4">
                                <span class="badge bg-light text-dark border px-2 py-1">{{ $product->category->name ?? 'Umum' }}</span>
                            </td>
                            <td class="py-3 px-4">Rp {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 fw-bold text-danger">Rp {{ number_format($product->price ?? $product->selling_price ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                <span class="badge {{ $product->stock <= 5 ? 'bg-danger' : 'bg-success' }} px-3 py-2 rounded-pill">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <td class="py-3 px-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-bold text-dark">Edit</a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->check() && auth()->user()->role === 'admin' ? '7' : '6' }}" class="text-center text-muted py-4">Belum ada data produk.</td>
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
</style>
@endsection