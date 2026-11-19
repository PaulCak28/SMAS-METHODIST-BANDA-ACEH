@extends('layouts.admin')

@section('title', 'Tambah Mata Pelajaran - Portal Akademik')

@section('content')

<div class="theme-portal rounded-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-4 border-bottom">
        <div>
            <div class="text-uppercase tracking-wide text-accent fw-bold small mb-1">Akademik</div>
            <h2 class="fw-bold mb-1 text-dark">Tambah Mata Pelajaran Baru</h2>
            <p class="text-muted-portal mb-0">Masukkan kode unik dan nama resmi mata pelajaran.</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3">
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

        <form action="{{ route('admin.subjects.store') }}" method="POST" class="portal-card p-4">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Mata Pelajaran <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" maxlength="20"
                           class="form-control text-uppercase" placeholder="Contoh: MAT-W, FIS, BIN" required>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" maxlength="255"
                           class="form-control" placeholder="Contoh: Matematika Wajib" required>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-portal-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan Mata Pelajaran
                </button>
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">Batal</a>
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