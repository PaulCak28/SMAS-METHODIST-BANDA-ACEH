@extends('layouts.admin')

@section('title', 'Kelola Akun Pengguna')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Manajemen Akun Pengguna</h4>
        <p class="text-muted small mb-0">Kelola akun Admin, Guru, dan Siswa.</p>
    </div>
    <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary rounded-3 shadow-sm">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Akun
    </a>
</div>

{{-- Filter & Pencarian --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.accounts.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text"
                           name="search"
                           value="{{ $search ?? '' }}"
                           class="form-control bg-light border-start-0"
                           placeholder="Cari nama, email, atau User ID (NIS/NIP)...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select bg-light">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ ($role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru" {{ ($role ?? '') === 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ ($role ?? '') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-3">Filter</button>
                @if(!empty($search) || !empty($role))
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary rounded-3" title="Reset">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabel Daftar Akun --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted small">
                <tr>
                    <th class="ps-4">User ID / NIS / NIP</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Rata-rata Nilai</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="ps-4 fw-semibold text-dark">{{ $user->user_id }}</td>
                        <td>{{ $user->name }}</td>
                        <td class="text-muted small">{{ $user->email }}</td>
                        <td>
                            @php
                                $badgeClass = match($user->role) {
                                    'admin'    => 'bg-danger-subtle text-danger',
                                    'guru'     => 'bg-primary-subtle text-primary',
                                    'siswa'    => 'bg-success-subtle text-success',
                                    'orangtua' => 'bg-warning-subtle text-warning-emphasis',
                                    default    => 'bg-secondary-subtle text-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} rounded-pill px-3 text-uppercase">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td>
                            @if($user->role === 'siswa')
                                @if($user->student_avg_score !== null)
                                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 fw-semibold">
                                        {{ number_format((float) $user->student_avg_score, 2) }}
                                    </span>
                                @else
                                    <span class="text-muted small">Belum ada nilai</span>
                                @endif
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.accounts.edit', $user->user_id) }}"
                                   class="btn btn-sm btn-outline-primary rounded-3"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->user_id !== auth()->id())
                                    <form action="{{ route('admin.accounts.destroy', $user->user_id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            Tidak ada data akun pengguna yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="card-footer bg-white border-top px-4 py-3">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection