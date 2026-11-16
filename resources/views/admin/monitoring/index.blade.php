@extends('layouts.admin')

@section('title', 'Monitoring Ujian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Monitoring Sesi Ujian</h4>
        <p class="text-muted small mb-0">Pantau seluruh ujian yang aktif maupun yang telah selesai di setiap kelas.</p>
    </div>
    <div>
        <button type="button" onclick="window.location.reload()" class="btn btn-primary shadow-sm rounded-3">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
        </button>
    </div>
</div>

<div class="row g-4">
    @forelse($activeSessions as $session)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                {{-- Indikator Status (Garis Hijau jika Aktif, Abu-abu jika Tidak Aktif) --}}
                <div class="position-absolute top-0 start-0 bottom-0 {{ $session->is_open ? 'bg-success' : 'bg-secondary' }}" style="width: 4px;"></div>

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-semibold text-uppercase">
                            {{ $session->name ?? 'Sesi Ujian' }}
                        </span>
                        @if($session->is_open)
                            <span class="text-success small fw-semibold">
                                <i class="bi bi-record-circle-fill me-1"></i>Aktif
                            </span>
                        @else
                            <span class="text-secondary small fw-semibold">
                                <i class="bi bi-stop-circle me-1"></i>Tidak Aktif
                            </span>
                        @endif
                    </div>

                    <h5 class="fw-bold mb-1 text-truncate" title="{{ $session->exam->title ?? 'Judul Ujian' }}">
                        {{ $session->exam->title ?? 'Nama Ujian Tidak Diketahui' }}
                    </h5>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-building me-1"></i> {{ $session->cst->schoolClass->name ?? 'Kelas' }} &bull;
                        <i class="bi bi-book me-1"></i> {{ $session->cst->subject->name ?? 'Mata Pelajaran' }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-3 mb-3">
                        <div class="text-center">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Mulai</small>
                            <span class="fw-semibold text-dark">{{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : '-' }}</span>
                        </div>
                        <div class="text-center">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Selesai</small>
                            <span class="fw-semibold text-dark">{{ $session->end_time ? \Carbon\Carbon::parse($session->end_time)->format('H:i') : '-' }}</span>
                        </div>
                        <div class="text-center">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Peserta</small>
                            <span class="fw-semibold text-dark">{{ $session->participants_count ?? 0 }}</span>
                        </div>
                    </div>

                    <a href="{{ route('admin.monitoring.show', $session->id) }}" class="btn btn-outline-primary w-100 rounded-3 fw-medium">
                        Masuk Sesi <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <i class="bi bi-display text-muted" style="font-size: 4rem;"></i>
                <h5 class="fw-bold mt-3 text-dark">Belum ada data ujian</h5>
                <p class="text-muted mb-0">Saat ini belum ada sesi ujian yang tercatat di sistem.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection