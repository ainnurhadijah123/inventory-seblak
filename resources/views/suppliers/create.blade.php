@php
    $layout = 'layouts.app';
    if(auth()->check() && auth()->user()->role === 'admin') {
        $layout = 'layouts.admin';
    }
@endphp

@extends($layout)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header text-white fw-bold py-3" style="background: linear-gradient(135deg, #cb2d3e, #ef473a);">
                    <h4 class="mb-0">Tambah Supplier Baru</h4>
                </div>
                <div class="card-body p-4 bg-white">
                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Supplier / Agen</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Agen Sosis Krueng Geukuh" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Contoh: 081234567890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold">Alamat</label>
                            <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" placeholder="Masukkan alamat supplier...">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary px-4 py-2 rounded-pill fw-bold">Kembali</a>
                            <button type="submit" class="btn text-white px-5 py-2 rounded-pill fw-bold shadow-sm" style="background: linear-gradient(135deg, #cb2d3e, #ef473a); border: none;">
                                Simpan Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection