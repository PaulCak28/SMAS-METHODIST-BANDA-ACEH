@extends('layouts.admin')

@section('title', 'Edit Kelas Mapel - Portal Akademik')

@section('content')

<div class="theme-portal rounded-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-4 border-bottom">
        <div>
            <div class="text-uppercase tracking-wide text-accent fw-bold small mb-1">Akademik</div>
            <h2 class="fw-bold mb-1 text-dark">Edit Kelas Mata Pelajaran</h2>
            <p class="text-muted-portal mb-0">
                Kode Gabung Saat Ini: <code class="fw-bold text-primary">{{ $cst->join_code }}</code>
            </p>
        </div>
        <a href="{{ route('admin.cst.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="p-4 pt-0">
        @if(session('error'))
            <div class="alert alert-danger rounded-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.cst.update', $cst->cst_id) }}" method="POST" class="portal-card p-4">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ruang Kelas <span class="text-danger">*</span></label>
                    <select name="class_id" class="form-select" required>
                        @foreach($rooms as $room)
                            <option value="{{ $room->class_id }}" {{ old('class_id', $cst->class_id) == $room->class_id ? 'selected' : '' }}>
                                {{ $room->name }} ({{ $room->academic_year }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                    <select name="subject_code" class="form-select" required>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->code }}" {{ old('subject_code', $cst->subject_code) == $subject->code ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Guru Pengampu <span class="text-danger">*</span></label>
                    <select name="teacher_id" class="form-select" required>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->nip }}" {{ old('teacher_id', $cst->teacher_id) == $teacher->nip ? 'selected' : '' }}>
                                {{ $teacher->user->name ?? 'Guru' }} (NIP: {{ $teacher->nip }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                    <input type="text" name="academic_year" value="{{ old('academic_year', $cst->academic_year) }}" maxlength="9"
                           class="form-control" required>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-portal-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.cst.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>

<style>
    .theme-portal { background: #ffffff; color: #1e293b; min-height: 80vh; border: 1px solid #e2e8f0; }
    .text-accent { color: #4f46e5 !important; }
    .text-muted-portal { color: #64748b !important; }
    .portal-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; }
    .btn-portal-primary { background-color: #4f46e5; border-color: #4f46e5; color: white; font-weight: 500; }
    .btn-portal-primary:hover { background-color: #4338ca; color: white; }
</style>

@endsection