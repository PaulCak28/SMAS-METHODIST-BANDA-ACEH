@extends('layouts.admin')

@section('title', 'Edit Guru - Portal Akademik')

@section('content')

<div class="theme-portal rounded-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-4 border-bottom">
        <div>
            <div class="text-uppercase tracking-wide text-accent fw-bold small mb-1">Data Sekolah</div>
            <h2 class="fw-bold mb-1 text-dark">Edit Data Guru</h2>
            <p class="text-muted-portal mb-0">NIP: <code>{{ $teacher->nip }}</code></p>
        </div>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
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

        <form action="{{ route('admin.teachers.update', $teacher->nip) }}" method="POST" class="portal-card p-4">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIP</label>
                    <input type="text" value="{{ $teacher->nip }}" class="form-control bg-light" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $teacher->user->name ?? '') }}" maxlength="100" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $teacher->user->email ?? '') }}" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. Telepon / HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher->user->phone ?? '') }}" maxlength="20" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Spesialisasi / Bidang Studi</label>
                    <input type="text" name="specialization" value="{{ old('specialization', $teacher->specialization) }}" maxlength="100" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                    <input type="text" name="education_level" value="{{ old('education_level', $teacher->education_level) }}" maxlength="30" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Bergabung</label>
                    <input type="date" name="hire_date"
                           value="{{ old('hire_date', $teacher->hire_date ? \Carbon\Carbon::parse($teacher->hire_date)->format('Y-m-d') : '') }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status Kepegawaian</label>
                    <select name="status" class="form-select">
                        @foreach(['aktif' => 'Aktif', 'cuti' => 'Cuti', 'pensiun' => 'Pensiun', 'resign' => 'Resign'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $teacher->status) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="address" rows="2" maxlength="255" class="form-control">{{ old('address', $teacher->address) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-portal-primary px-4">
                    <i class="bi bi-save me-1"></i> Perbarui Data Guru
                </button>
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Batal</a>
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