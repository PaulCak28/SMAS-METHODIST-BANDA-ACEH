@extends('layouts.admin')

@section('title', 'Data Guru - Portal Akademik')

@section('content')

<div class="theme-portal rounded-4 shadow-sm">

    {{-- HEADER HALAMAN --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-4 border-bottom">
        <div>
            <div class="text-uppercase tracking-wide text-accent fw-bold small mb-1">Data Sekolah</div>
            <h2 class="fw-bold mb-1 text-dark">Data Tenaga Pendidik (Guru)</h2>
            <p class="text-muted-portal mb-0">
                Manajemen biodata guru, spesialisasi bidang studi, dan status kepegawaian.
            </p>
        </div>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-portal-primary px-4 py-2 rounded-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Guru
        </a>
    </div>

    <div class="p-4 pt-0">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FORM PENCARIAN & FILTER --}}
        <form method="GET" action="{{ route('admin.teachers.index') }}" class="portal-card p-3 mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control border-start-0 ps-0"
                               placeholder="Cari NIP, nama guru, email, atau bidang studi...">
                    </div>
                </div>
                <div class="col-12 col-md-7 col-lg-3">
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        @foreach(['aktif' => 'Aktif', 'cuti' => 'Cuti', 'pensiun' => 'Pensiun', 'resign' => 'Resign'] as $val => $label)
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-5 col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-portal-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary px-3">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- TABEL DATA GURU --}}
        <div class="portal-card">
            <div class="card-header-portal d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-person-badge text-accent me-2"></i> Daftar Guru ({{ $teachers->total() }} Orang)
                </h5>
                <span class="badge bg-secondary-subtle text-secondary">Koneksi: mysql_admin</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead class="text-muted-portal small border-bottom border-secondary bg-light">
                        <tr>
                            <th class="ps-4" style="width: 60px;">No</th>
                            <th>NIP</th>
                            <th>Nama & Kontak</th>
                            <th>Spesialisasi & Pendidikan</th>
                            <th>Tgl Bergabung</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $index => $teacher)
                            @php
                                $badgeClass = match($teacher->status) {
                                    'aktif'   => 'bg-success-subtle text-success',
                                    'cuti'    => 'bg-warning-subtle text-warning-emphasis',
                                    'pensiun' => 'bg-secondary-subtle text-secondary',
                                    'resign'  => 'bg-danger-subtle text-danger',
                                    default   => 'bg-secondary-subtle text-secondary',
                                };
                            @endphp
                            <tr>
                                <td class="ps-4 text-muted-portal">
                                    {{ $teachers->firstItem() + $index }}
                                </td>
                                <td>
                                    <code class="fw-semibold">{{ $teacher->nip }}</code>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $teacher->user->name ?? '-' }}</div>
                                    <small class="text-muted-portal d-block">{{ $teacher->user->email ?? '-' }}</small>
                                    @if(!empty($teacher->user->phone))
                                        <small class="text-muted-portal"><i class="bi bi-telephone me-1"></i>{{ $teacher->user->phone }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-dark fw-medium">{{ $teacher->specialization ?? '-' }}</div>
                                    <small class="text-muted-portal">Pendidikan: {{ $teacher->education_level ?? '-' }}</small>
                                </td>
                                <td class="text-muted-portal small">
                                    {{ $teacher->hire_date ? \Carbon\Carbon::parse($teacher->hire_date)->format('d M Y') : '-' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeClass }} px-3 py-1 rounded-pill text-capitalize">
                                        {{ $teacher->status ?? 'aktif' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.teachers.edit', $teacher->nip) }}"
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.teachers.destroy', $teacher->nip) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus data guru {{ $teacher->user->name ?? $teacher->nip }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted-portal">
                                    Belum ada data guru yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($teachers->hasPages())
                <div class="p-3 border-top">
                    {{ $teachers->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .theme-portal {
        background: #ffffff;
        color: #1e293b;
        min-height: 80vh;
        border: 1px solid #e2e8f0;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    .text-accent { color: #4f46e5 !important; }
    .text-muted-portal { color: #64748b !important; }
    .border-secondary { border-color: #e2e8f0 !important; }
    .portal-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }
    .card-header-portal {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 12px 12px 0 0;
    }
    .custom-table {
        --bs-table-bg: transparent;
        --bs-table-color: #1e293b;
        margin-bottom: 0;
    }
    .btn-portal-primary {
        background-color: #4f46e5;
        border-color: #4f46e5;
        color: white;
        font-weight: 500;
    }
    .btn-portal-primary:hover {
        background-color: #4338ca;
        color: white;
    }
</style>

@endsection