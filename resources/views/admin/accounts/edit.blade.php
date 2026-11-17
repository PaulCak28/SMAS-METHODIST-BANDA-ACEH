@extends('layouts.admin')

@section('title', 'Edit Akun - Portal Akademik')

@section('content')

<div class="theme-portal rounded-4 shadow-sm">
    <div class="d-flex align-items-center gap-3 mb-4 p-4 border-bottom">
        <a href="{{ route('admin.accounts.index') }}" class="btn btn-white border shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
            <i class="bi bi-arrow-left text-dark fs-5"></i>
        </a>
        <div>
            <div class="text-uppercase tracking-wide text-accent fw-bold small mb-1">Manajemen Pengguna</div>
            <h2 class="fw-bold mb-1 text-dark">Edit Akun: {{ $user->name }}</h2>
            <p class="text-muted-portal mb-0">Perbarui informasi profil atau reset password pengguna.</p>
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

        <form action="{{ route('admin.accounts.update', $user->user_id) }}" method="POST" class="portal-card p-4 mb-4">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">User ID <span class="text-danger">*</span></label>
                    <input type="text" name="user_id" value="{{ old('user_id', $user->user_id) }}" maxlength="18"
                           class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="100"
                           class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255"
                           class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Role Pengguna <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        @foreach(['admin' => 'Admin', 'guru' => 'Guru', 'siswa' => 'Siswa', 'orangtua' => 'Orang Tua'] as $val => $label)
                            <option value="{{ $val }}" {{ old('role', $user->role) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-portal-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>

        {{-- RINGKASAN NILAI SISWA (JIKA ROLE SISWA) --}}
        @if($user->role === 'siswa')
            <div class="portal-card overflow-hidden">
                <div class="card-header-portal">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-journal-check text-accent me-2"></i> Ringkasan Nilai Mata Pelajaran Siswa
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle custom-table mb-0">
                        <thead class="text-muted-portal small border-bottom border-secondary bg-light">
                            <tr>
                                <th class="ps-4" style="width: 60px;">No</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th class="text-end pe-4">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gradeSummary as $idx => $row)
                                <tr>
                                    <td class="ps-4 text-muted-portal">{{ $idx + 1 }}</td>
                                    <td><div class="fw-semibold text-dark">{{ $row->subject_name }}</div></td>
                                    <td><span class="badge bg-light text-dark border">{{ $row->class_name }}</span></td>
                                    <td class="text-end pe-4">
                                        <span class="badge bg-primary-subtle text-primary px-3 py-1 fw-bold fs-6">
                                            {{ $row->final_score !== null ? number_format($row->final_score, 2) : '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted-portal">
                                        Siswa ini belum memiliki catatan nilai mata pelajaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .theme-portal { background: #ffffff; color: #1e293b; border: 1px solid #e2e8f0; }
    .text-accent { color: #4f46e5 !important; }
    .text-muted-portal { color: #64748b !important; }
    .portal-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; }
    .card-header-portal { padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .custom-table { --bs-table-bg: transparent; --bs-table-color: #1e293b; }
    .btn-portal-primary { background-color: #4f46e5; border-color: #4f46e5; color: white; font-weight: 500; }
    .btn-portal-primary:hover { background-color: #4338ca; color: white; }
</style>

@endsection