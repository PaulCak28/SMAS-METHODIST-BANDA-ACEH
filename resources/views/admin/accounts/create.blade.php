@extends('layouts.admin')

@section('title', 'Tambah Akun Baru - Portal Akademik')

@section('content')

<div class="theme-portal rounded-4 shadow-sm">
    <div class="d-flex align-items-center gap-3 mb-4 p-4 border-bottom">
        <a href="{{ route('admin.accounts.index') }}" class="btn btn-white border shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
            <i class="bi bi-arrow-left text-dark fs-5"></i>
        </a>
        <div>
            <div class="text-uppercase tracking-wide text-accent fw-bold small mb-1">Manajemen Pengguna</div>
            <h2 class="fw-bold mb-1 text-dark">Tambah Akun Pengguna Baru</h2>
            <p class="text-muted-portal mb-0">Isi informasi kredensial untuk membuat akun baru.</p>
        </div>
    </div>

    <div class="p-4 pt-0">
        @if($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.accounts.store') }}" method="POST" class="portal-card p-4">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">User ID (NIS / NIP / ID Unik) <span class="text-danger">*</span></label>
                    <input type="text" name="user_id" value="{{ old('user_id') }}" maxlength="18"
                           class="form-control" placeholder="Contoh: 2026001 atau NIP guru" required>
                    <small class="text-muted-portal">Untuk role Siswa, maksimal 10 karakter (NIS).</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" maxlength="100"
                           class="form-control" placeholder="Nama lengkap sesuai identitas" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" maxlength="255"
                           class="form-control" placeholder="nama@email.com" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Role Pengguna <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach(['admin' => 'Admin', 'guru' => 'Guru', 'siswa' => 'Siswa'] as $val => $label)
                            <option value="{{ $val }}" {{ old('role') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-portal-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan Akun Baru
                </button>
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>

<style>
    .theme-portal { background: #ffffff; color: #1e293b; border: 1px solid #e2e8f0; }
    .text-accent { color: #4f46e5 !important; }
    .text-muted-portal { color: #64748b !important; }
    .portal-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; }
    .btn-portal-primary { background-color: #4f46e5; border-color: #4f46e5; color: white; font-weight: 500; }
    .btn-portal-primary:hover { background-color: #4338ca; color: white; }
</style>

@endsection