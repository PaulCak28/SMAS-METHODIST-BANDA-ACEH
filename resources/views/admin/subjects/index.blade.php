@extends('layouts.admin')

@section('title', 'Mata Pelajaran - Portal Akademik')

@section('content')

<div class="theme-portal rounded-4 shadow-sm">

    {{-- HEADER HALAMAN --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-4 border-bottom">
        <div>
            <div class="text-uppercase tracking-wide text-accent fw-bold small mb-1">Akademik</div>
            <h2 class="fw-bold mb-1 text-dark">Daftar Mata Pelajaran</h2>
            <p class="text-muted-portal mb-0">
                Manajemen kode dan nama mata pelajaran yang diajarkan di SMAS Methodist Banda Aceh.
            </p>
        </div>
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-portal-primary px-4 py-2 rounded-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Mata Pelajaran
        </a>
    </div>

    <div class="p-4 pt-0">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FORM PENCARIAN FULL-WIDTH RAPI --}}
        <form method="GET" action="{{ route('admin.subjects.index') }}" class="portal-card p-3 mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ $search ?? request('search') }}"
                               class="form-control border-start-0 ps-0"
                               placeholder="Cari kode mapel atau nama mata pelajaran...">
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-portal-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary px-3">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- TABEL MATA PELAJARAN --}}
        <div class="portal-card overflow-hidden">
            <div class="card-header-portal d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-book text-accent me-2"></i> Daftar Mata Pelajaran ({{ $subjects->total() }})
                </h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table mb-0">
                    <thead class="text-muted-portal small border-bottom border-secondary bg-light">
                        <tr>
                            <th class="ps-4 text-center" style="width: 70px;">No</th>
                            <th style="width: 170px;">Kode Mapel</th>
                            <th>Nama Mata Pelajaran</th>
                            <th class="text-center" style="width: 170px;">Kelas Diampu</th>
                            <th class="text-end pe-4" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $index => $subject)
                            @php
                                $count = $usageCounts[$subject->code] ?? 0;
                            @endphp
                            <tr>
                                <td class="ps-4 text-center text-muted-portal">
                                    {{ $subjects->firstItem() + $index }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace fs-6 px-2 py-1">
                                        {{ $subject->code }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $subject->name }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                                        {{ $count }} kelas
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.subjects.edit', $subject->code) }}"
                                           class="btn btn-sm btn-outline-primary px-2" title="Edit Mata Pelajaran">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @if ($count > 0)
                                            <span title="Tidak bisa dihapus: sedang diampu di {{ $count }} kelas" class="d-inline-block">
                                                <button type="button" class="btn btn-sm btn-outline-secondary px-2" disabled style="pointer-events:none;">
                                                    <i class="bi bi-lock-fill"></i>
                                                </button>
                                            </span>
                                        @else
                                            <form action="{{ route('admin.subjects.destroy', $subject->code) }}" method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus mata pelajaran {{ $subject->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted-portal">
                                    Belum ada data mata pelajaran yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($subjects->hasPages())
                <div class="p-3 border-top bg-light">
                    {{ $subjects->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .theme-portal { background: #ffffff; color: #1e293b; min-height: 80vh; border: 1px solid #e2e8f0; }
    .text-accent { color: #4f46e5 !important; }
    .text-muted-portal { color: #64748b !important; }
    .border-secondary { border-color: #e2e8f0 !important; }
    .portal-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; }
    .card-header-portal { padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .custom-table { --bs-table-bg: transparent; --bs-table-color: #1e293b; }
    .btn-portal-primary { background-color: #4f46e5; border-color: #4f46e5; color: white; font-weight: 500; }
    .btn-portal-primary:hover { background-color: #4338ca; color: white; }
</style>

@endsection