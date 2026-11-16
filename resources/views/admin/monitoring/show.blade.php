@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Informasi Ujian --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <a href="{{ route('admin.monitoring.index') }}" class="text-decoration-none text-muted small d-inline-block mb-2">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Ujian
                </a>
                <h4 class="fw-bold mb-1">{{ $session->exam->title }}</h4>
                <div class="text-muted small">
                    <span class="me-3"><i class="bi bi-door-open me-1"></i> Kelas: {{ $session->cst->schoolClass->name ?? '-' }}</span>
                    <span class="me-3"><i class="bi bi-book me-1"></i> Mapel: {{ $session->cst->subject->name ?? '-' }}</span>
                    <span><i class="bi bi-key me-1"></i> Kode Kelas: <strong class="text-dark">{{ $session->token ?? '-' }}</strong></span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if($session->is_open)
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-3 border border-success border-opacity-25">
                        <i class="bi bi-record-circle-fill me-1"></i> Berlangsung
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-3 border border-secondary border-opacity-25">
                        <i class="bi bi-stop-circle me-1"></i> Tidak Aktif
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Ringkasan Statistik Ujian (dari View v_exam_statistics) --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="text-muted small">Sesi Selesai / Total Sesi</div>
                    <h4 class="fw-bold mb-0 mt-1">{{ $session->finished_sessions ?? 0 }} / {{ $session->total_sessions ?? 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="text-muted small">Rata-rata Nilai</div>
                    <h4 class="fw-bold mb-0 mt-1">{{ $session->avg_score !== null ? number_format($session->avg_score, 1) : '-' }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="text-muted small">Tingkat Kelulusan (KKM: {{ $session->pass_score ?? '-' }})</div>
                    <h4 class="fw-bold mb-0 mt-1">{{ $session->pass_rate !== null ? number_format($session->pass_rate, 1) . '%' : '-' }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="text-muted small">Durasi Ujian</div>
                    <h4 class="fw-bold mb-0 mt-1">{{ $session->duration_minutes ?? '-' }} Menit</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Peserta Ujian (dari Stored Procedure sp_exam_participants) --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h6 class="fw-bold mb-0">Daftar Peserta Ujian</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">NIS</th>
                        <th>Nama Siswa</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Pelanggaran</th>
                        <th>Nilai</th>
                        <th class="pe-4">Status Sesi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $p)
                        <tr>
                            <td class="ps-4 font-monospace">{{ $p->student->nisn ?? $p->nis }}</td>
                            <td class="fw-medium">{{ $p->student->user->name ?? $p->student_name }}</td>
                            <td>{{ $p->start_time }}</td>
                            <td>{{ $p->finish_time }}</td>
                            <td>
                                @if(($p->violation_count ?? 0) > 0)
                                    <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                        {{ $p->violation_count }}x
                                    </span>
                                @else
                                    <span class="text-muted small">0</span>
                                @endif
                            </td>
                            <td class="fw-semibold">
                                {{ $p->score !== null ? number_format($p->score, 1) : '-' }}
                            </td>
                            <td class="pe-4">
                                @if($p->session_status === 'selesai')
                                    <span class="badge bg-success-subtle text-success">Selesai</span>
                                @elseif($p->session_status === 'berlangsung')
                                    <span class="badge bg-warning-subtle text-warning-emphasis">Berlangsung</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Belum Mulai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada siswa terdaftar di kelas ujian ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Rincian Pelanggaran (dari View v_violation_summary) --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h6 class="fw-bold mb-0">Rincian Pelanggaran Ujian</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">NIS</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Total</th>
                        <th class="pe-4">Terakhir Terjadi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($violations as $v)
                        <tr>
                            <td class="ps-4 font-monospace">{{ $v->nis }}</td>
                            <td class="fw-medium">{{ $v->student_name }}</td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger text-uppercase">
                                    {{ str_replace('_', ' ', $v->violation_type) }}
                                </span>
                            </td>
                            <td class="fw-bold text-danger">{{ $v->total }}x</td>
                            <td class="pe-4 text-muted small">
                                {{ $v->last_occurred_at ? \Carbon\Carbon::parse($v->last_occurred_at)->format('d M Y H:i:s') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Tidak ada catatan pelanggaran pada ujian ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection