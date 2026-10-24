@extends('layouts.admin')

@section('content')
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">Kelola Pengguna</h3>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 0.9rem;">
                <thead style="background: linear-gradient(135deg, #cb2d3e, #ef473a) !important; color: white;">
                    <tr>
                        <th class="py-3 px-4 text-white" style="background: transparent;">No</th>
                        <th class="py-3 px-4 text-white" style="background: transparent;">Nama Lengkap</th>
                        <th class="py-3 px-4 text-white" style="background: transparent;">Email</th>
                        <th class="py-3 px-4 text-white" style="background: transparent;">Role</th>
                        <th class="py-3 px-4 text-white" style="background: transparent;">Tanggal Mendaftar</th>
                        <th class="py-3 px-4 text-white" style="background: transparent;">Aksi</th>
                    </tr>
                </thead>    
                <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td class="text-muted">{{ $index + 1 + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            
                            <td class="text-start fw-bold text-dark">{{ $user->name }}</td>
                            <td class="text-start text-muted">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger rounded-pill px-3">Admin</span>
                                @elseif($user->role === 'staff')
                                    <span class="badge bg-primary rounded-pill px-3">Kasir (Staff)</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3">Customer</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $user->created_at->format('d M Y, H:i') }}</td>
                            
                            <!-- INI BAGIAN TOMBOL AKSI YANG TERTINGGAL -->
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-danger btn-sm rounded-pill px-3">Edit Role</a>
                                    
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }} secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">Hapus</button>
                                    </form>
                                </div>
                            </td>
                            
                        </tr>
                        @empty
                        <tr>
                            <!-- Ubah colspan dari 5 menjadi 6 -->
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>    
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection