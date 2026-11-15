@extends('layouts.admin')
@section('title', 'Buat Pengumuman')

@section('content')
<div class="mb-4 d-flex align-items-center gap-3">
    <a href="{{ route('admin.announcements.index') }}" class="btn btn-white border shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
        <i class="bi bi-arrow-left text-dark fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-1 text-dark">Buat Pengumuman Baru</h4>
    </div>
</div>

<div class="row">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.announcements.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Pengumuman <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control bg-light @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Target Penerima <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4 bg-light p-3 rounded-3 border">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="target_roles[]" value="all" id="target_all" {{ (is_array(old('target_roles')) && in_array('all', old('target_roles'))) ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="target_all">Semua (Publik)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="target_roles[]" value="teacher" id="target_teacher" {{ (is_array(old('target_roles')) && in_array('teacher', old('target_roles'))) ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="target_teacher">Guru</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="target_roles[]" value="student" id="target_student" {{ (is_array(old('target_roles')) && in_array('student', old('target_roles'))) ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="target_student">Siswa</label>
                            </div>
                        </div>
                        @error('target_roles')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tautan / URL Tujuan <span class="text-muted small">(Opsional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-link-45deg"></i></span>
                            <input type="url" name="link" class="form-control bg-light @error('link') is-invalid @enderror" placeholder="https://contoh-website.com" value="{{ old('link') }}">
                        </div>
                        <small class="text-muted">Diawali dengan <code>http://</code> atau <code>https://</code>. Jika diisi, tombol menuju website akan muncul.</small>
                        @error('link')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Isi Pengumuman <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control bg-light @error('content') is-invalid @enderror" rows="6" required>{{ old('content') }}</textarea>
                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-light border px-4 rounded-3 fw-medium">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-medium">Publikasikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection