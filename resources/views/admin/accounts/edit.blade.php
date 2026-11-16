@extends('layouts.admin')

@section('title', 'Edit Akun Pengguna')

@section('content')
<div class="mb-4 d-flex align-items-center gap-3">
    <a href="{{ route('admin.accounts.index') }}" class="btn btn-white border-0 shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
        <i class="bi bi-arrow-left text-dark fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-1 text-dark">Edit Akun Pengguna</h4>
        <p class="text-muted small mb-0">Perbarui data akun <strong>{{ $user->name }}</strong>.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.accounts.update', $user->user_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="role" class="form-label fw-semibold">Role Pengguna <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="guru" {{ old('role', $user->role) === 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="siswa" {{ old('role', $user->role) === 'siswa' ? 'selected' : '' }}>Siswa</option>
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
                               value="{{ old('user_id', $user->user_id) }}"
                               maxlength="{{ old('role', $user->role) === 'siswa' ? 10 : 18 }}"
                               class="form-control @error('user_id') is-invalid @enderror"
                               required>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $user->name) }}"
                               maxlength="100"
                               class="form-control @error('name') is-invalid @enderror"
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
                               value="{{ old('email', $user->email) }}"
                               class="form-control @error('email') is-invalid @enderror"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password Baru</label>
                        <input type="password"
                               name="password"
                               id="password"
                               minlength="6"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.accounts.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Ringkasan Nilai dari View v_student_grade_summary (jika akun siswa) --}}
    @if($user->role === 'siswa')
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h6 class="fw-bold mb-0 text-dark">Rekap Nilai Akademik Siswa</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
    <thead class="table-light text-muted small">
        <tr>
            <th class="ps-4">Mata Pelajaran</th>
            <th>Kelas</th>
            <th class="pe-4 text-end">Nilai Rata-rata</th>
        </tr>
    </thead>
    <tbody>
        @forelse($gradeSummary ?? [] as $g)
            <tr>
                <td class="ps-4 fw-semibold text-dark">{{ $g->subject_name }}</td>
                <td class="text-muted small">{{ $g->class_name }}</td>
                <td class="pe-4 text-end fw-bold text-primary">
                    {{ $g->final_score !== null ? number_format((float) $g->final_score, 2) : '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center py-4 text-muted">
                    Belum ada mata pelajaran / nilai tercatat untuk siswa ini.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
                </div>
            </div>
        </div>
    @endif
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