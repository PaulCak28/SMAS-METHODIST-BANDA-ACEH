@extends('layouts.admin')

@section('title', 'Monitoring Kelas - Portal Akademik')

@section('content')

@php
    $gradeGroups = [
        '10' => [
            'title' => 'Kelas 10 (X)',
            'items' => $classes->filter(fn ($c) => preg_match('/^(10|X)(?![I])(\b|\s|-|_)/i', trim($c->name))),
        ],
        '11' => [
            'title' => 'Kelas 11 (XI)',
            'items' => $classes->filter(fn ($c) => preg_match('/^(11|XI)(?![I])(\b|\s|-|_)/i', trim($c->name))),
        ],
        '12' => [
            'title' => 'Kelas 12 (XII)',
            'items' => $classes->filter(fn ($c) => preg_match('/^(12|XII)(\b|\s|-|_)/i', trim($c->name))),
        ],
    ];
@endphp

<div class="theme-portal rounded-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-4 border-bottom">
        <div>
            <div class="text-uppercase tracking-wide text-accent fw-bold small mb-1">Pengawasan Ujian</div>
            <h2 class="fw-bold mb-1 text-dark">Pilih Kelas untuk Monitoring</h2>
            <p class="text-muted-portal mb-0">Klik pada kartu kelas di bawah untuk melihat daftar ujian mata pelajaran yang tersedia.</p>
        </div>
    </div>

    <div class="p-4 pt-0">
        @if($classes->isEmpty())
            <div class="text-center py-5 text-muted-portal portal-card p-4 bg-white">
                Belum ada data kelas yang terdaftar.
            </div>
        @else
            <div class="row g-4 align-items-start">
                @foreach($gradeGroups as $gradeKey => $group)
                    <div class="col-12 col-lg-4">
                        {{-- Header Kolom Tingkat Kelas --}}
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <span class="grade-indicator"></span>
                                <h5 class="fw-bold text-dark mb-0">{{ $group['title'] }}</h5>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                                {{ $group['items']->count() }} Kelas
                            </span>
                        </div>

                        {{-- Daftar Kartu Kelas Menurun ke Bawah --}}
                        <div class="d-flex flex-column gap-3">
                            @forelse($group['items'] as $class)
                                <a href="{{ route('admin.monitoring.class.exams', $class->class_id) }}" class="text-decoration-none">
                                    <div class="card border shadow-sm rounded-4 p-4 bg-white class-box-card transition-all">
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

                                        <div class="d-flex align-items-center text-accent fw-semibold small pt-2 border-top">
                                            <span>Buka Daftar Ujian</span>
                                            <i class="bi bi-arrow-right ms-auto"></i>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-4 text-muted-portal portal-card p-3 bg-white small">
                                    Tidak ada kelas pada tingkat {{ $group['title'] }}.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .theme-portal { background: #ffffff; color: #1e293b; min-height: 80vh; border: 1px solid #e2e8f0; }
    .text-accent { color: #4f46e5 !important; }
    .text-muted-portal { color: #64748b !important; }
    .portal-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; }
    .grade-indicator { width: 4px; height: 18px; border-radius: 999px; background-color: #4f46e5; display: inline-block; }
    .class-box-card:hover { transform: translateY(-3px); border-color: #4f46e5 !important; box-shadow: 0 .5rem 1rem rgba(79, 70, 229, 0.15) !important; }
    .transition-all { transition: all 0.2s ease-in-out; }
</style>

@endsection