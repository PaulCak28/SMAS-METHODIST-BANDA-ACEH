@extends('layouts.admin')

@section('title', 'Data Kelas & Daftar Siswa - Portal Akademik')

@section('content')

<div class="theme-portal rounded-4 shadow-sm">

    {{-- HEADER HALAMAN --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-4 border-bottom">
        <div>
            <div class="text-uppercase tracking-wide text-accent fw-bold small mb-1">Manajemen Akademik</div>
            <h2 class="fw-bold mb-1 text-dark">Data Kelas, Wali Kelas & Siswa</h2>
            <p class="text-muted-portal mb-0">
                Daftar seluruh kelas beserta wali kelas pengampu dan jumlah siswa terdaftar.
            </p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="p-4 pt-0">

        {{-- FORM PENCARIAN & FILTER --}}
        <form method="GET" action="{{ route('admin.classes.index') }}" class="portal-card p-3 mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control" placeholder="Cari nama kelas, tahun ajaran, NIP, atau nama wali kelas...">
                </div>
                <div class="col-md-3">
                    <select name="academic_year" class="form-select">
                        <option value="">-- Semua Tahun Ajaran --</option>
                        @foreach($academicYears ?? [] as $year)
                            <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-portal-primary px-4">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- TABEL DAFTAR KELAS (DARI VIEW v_class_summary) --}}
        <div class="portal-card">
            <div class="card-header-portal d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-building text-accent me-2"></i> Daftar Rombongan Belajar ({{ $classes->total() }} Kelas)
                </h5>
                <span class="badge bg-secondary-subtle text-secondary">Sumber: v_class_summary</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead class="text-muted-portal small border-bottom border-secondary bg-light">
                        <tr>
                            <th class="ps-4" style="width: 60px;">No</th>
                            <th>Nama Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Wali Kelas (Homeroom Teacher)</th>
                            <th class="text-center">Jumlah Siswa</th>
                            <th class="text-end pe-4">Rincian Siswa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $index => $class)
                            @php
                                $classStudents = $studentsByClass[$class->class_id] ?? collect();
                            @endphp
                            <tr>
                                <td class="ps-4 text-muted-portal">
                                    {{ $classes->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $class->class_name }}</div>
                                </td>
                                <td class="text-muted-portal">
                                    {{ $class->academic_year ?? '-' }}
                                </td>
                                <td>
                                    @if(!empty($class->homeroom_teacher_name))
                                        <div class="fw-semibold text-dark">
                                            <i class="bi bi-person-badge text-success me-1"></i> {{ $class->homeroom_teacher_name }}
                                        </div>
                                        @if(!empty($class->homeroom_teacher_nip))
                                            <small class="text-muted-portal">NIP: {{ $class->homeroom_teacher_nip }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted-portal fst-italic small">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                                        <i class="bi bi-people-fill me-1"></i> {{ $class->total_students ?? 0 }} Siswa
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#siswa-kelas-{{ $class->class_id }}">
                                        <i class="bi bi-eye me-1"></i> Lihat Siswa
                                    </button>
                                </td>
                            </tr>

                            {{-- BARIS COLLAPSE DAFTAR SISWA DI KELAS INI --}}
                            <tr class="collapse bg-light" id="siswa-kelas-{{ $class->class_id }}">
                                <td colspan="6" class="p-3 ps-4 pe-4">
                                    <div class="bg-white rounded-3 border p-3">
                                        <div class="fw-bold text-dark small mb-2">
                                            <i class="bi bi-people text-accent me-1"></i>
                                            Daftar Siswa Kelas {{ $class->class_name }} ({{ $classStudents->count() }} orang)
                                        </div>
                                        @if($classStudents->isEmpty())
                                            <p class="text-muted-portal small mb-0 fst-italic">Belum ada siswa di kelas ini.</p>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table table-sm table-borderless mb-0 small">
                                                    <thead class="border-bottom text-muted-portal">
                                                        <tr>
                                                            <th style="width: 50px;">#</th>
                                                            <th>NISN</th>
                                                            <th>Nama Siswa</th>
                                                            <th>Jenis Kelamin</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($classStudents as $sIdx => $student)
                                                            <tr>
                                                                <td class="text-muted-portal">{{ $sIdx + 1 }}</td>
                                                                <td><code>{{ $student->nis }}</code></td>
                                                                <td class="fw-medium text-dark">
                                                                    {{ $student->student_name ?? 'Siswa #' . $student->nis }}
                                                                </td>
                                                                <td class="text-muted-portal">
                                                                    {{ $student->gender ?? '-' }}
                                                                </td>
                                                                <td>
                                                                    <span class="badge {{ ($student->status ?? 'aktif') === 'aktif' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                                        {{ ucfirst($student->status ?? 'aktif') }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted-portal">
                                    Tidak ada data kelas yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($classes->hasPages())
                <div class="p-3 border-top">
                    {{ $classes->withQueryString()->links() }}
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