@extends('layouts.admin')

@section('title', 'Tambah Akun Pengguna')

@section('content')
<div class="mb-4 d-flex align-items-center gap-3">
    <a href="{{ route('admin.accounts.index') }}" class="btn btn-white border-0 shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
        <i class="bi bi-arrow-left text-dark fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-1 text-dark">Tambah Akun Baru</h4>
        <p class="text-muted small mb-0">Buat akun pengguna dan inisialisasi profil guru atau siswa secara otomatis.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4" style="max-width: 720px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.accounts.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="role" class="form-label fw-semibold">Role Pengguna <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru" {{ old('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="user_id" class="form-label fw-semibold">User ID / NIS / NIP <span class="text-danger">*</span></label>
                <input type="text"
                       name="user_id"
                       id="user_id"
                       value="{{ old('user_id') }}"
                       maxlength="18"
                       class="form-control @error('user_id') is-invalid @enderror"
                       placeholder="Masukkan NIS (maks 10 digit) atau NIP/ID (maks 18 karakter)"
                       required>
                <div class="form-text" id="userIdHelp">Siswa maksimal 10 karakter (NIS), Guru/Admin maksimal 18 karakter.</div>
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name') }}"
                       maxlength="100"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Contoh: Budi Santoso"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="nama@methodist.sch.id"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                <input type="password"
                       name="password"
                       id="password"
                       minlength="6"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimal 6 karakter"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
                <button type="submit" class="btn btn-primary rounded-3 px-4">
                    <i class="bi bi-save me-1"></i> Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('role')?.addEventListener('change', function () {
    const userIdInput = document.getElementById('user_id');
    if (this.value === 'siswa') {
        userIdInput.setAttribute('maxlength', '10');
    } else {
        userIdInput.setAttribute('maxlength', '18');
    }
});
</script>
@endsection