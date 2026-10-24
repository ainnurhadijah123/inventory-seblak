@extends('layouts.admin')

@section('content')
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">Master Kategori</h3>
    </div>

    <div class="row">
        <!-- Form Tambah Kategori (Kiri) -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Tambah Kategori</h5>
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Kategori</label>
                            <input type="text" name="name" class="form-control" placeholder="Cth: Minuman, Topping..." required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 rounded-pill fw-bold">Simpan Kategori</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Kategori (Kanan) -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 0.9rem;">
                            <thead style="background: linear-gradient(135deg, #cb2d3e, #ef473a) !important; color: white;">
                                <tr>
                                    <th class="py-3 px-4 text-white" style="background: transparent;">No</th>
                                    <th class="py-3 px-4 text-white" style="background: transparent;">Nama Kategori</th>
                                    <th class="py-3 px-4 text-white" style="background: transparent;">Total Produk</th>
                                    <th class="py-3 px-4 text-white" style="background: transparent;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td class="text-start fw-bold text-dark">{{ $category->name }}</td>
                                    <td class="text-muted">
                                        <span class="badge bg-secondary rounded-pill px-3">{{ $category->products()->count() }} Item</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada data kategori.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection