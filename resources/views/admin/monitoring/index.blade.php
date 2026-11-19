@extends('layouts.admin')

@section('title', 'Monitoring Kelas - Portal Akademik')

@section('content')

<div class="theme-portal rounded-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-4 border-bottom">
        <div>
            <div class="text-uppercase tracking-wide text-accent fw-bold small mb-1">Pengawasan Ujian</div>
            <h2 class="fw-bold mb-1 text-dark">Pilih Kelas untuk Monitoring</h2>
            <p class="text-muted-portal mb-0">Klik pada kartu kelas di bawah untuk melihat daftar ujian mata pelajaran yang tersedia.</p>
        </div>
    </div>

    <div class="p-4 pt-0">
        <div class="row g-3">
            @forelse($classes as $class)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('admin.monitoring.class.exams', $class->class_id) }}" class="text-decoration-none">
                        <div class="card border shadow-sm h-100 rounded-4 p-4 bg-white class-box-card transition-all">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="bg-primary-subtle text-primary p-3 rounded-3 fw-bold fs-5">
                                    <i class="bi bi-building"></i>
                                </div>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    {{ $class->students_count ?? 0 }} Siswa
                                </span>
                            </div>

                            <h4 class="fw-bold text-dark mb-1">{{ $class->name }}</h4>
                            <p class="text-muted-portal small mb-3">Tahun Ajaran: {{ $class->academic_year }}</p>

                            <div class="d-flex align-items-center text-accent fw-semibold small mt-auto pt-2 border-top">
                                <span>Buka Daftar Ujian</span>
                                <i class="bi bi-arrow-right ms-auto"></i>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5 text-muted-portal portal-card p-4 bg-white">
                        Belum ada data kelas yang terdaftar.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .theme-portal { background: #ffffff; color: #1e293b; min-height: 80vh; border: 1px solid #e2e8f0; }
    .text-accent { color: #4f46e5 !important; }
    .text-muted-portal { color: #64748b !important; }
    .portal-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; }
    .class-box-card:hover { transform: translateY(-3px); border-color: #4f46e5 !important; box-shadow: 0 .5rem 1rem rgba(79, 70, 229, 0.15) !important; }
    .transition-all { transition: all 0.2s ease-in-out; }
</style>

@endsection