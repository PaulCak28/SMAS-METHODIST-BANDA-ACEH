@extends('layouts.admin')

@section('title', 'Detail Monitoring Sesi')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.monitoring.index') }}" class="btn btn-white border-0 shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="bi bi-arrow-left text-dark fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-1 text-dark">Detail Sesi Ujian</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item text-muted">Monitoring</li>
                    <li class="breadcrumb-item active fw-semibold" aria-current="page">{{ $session->cst->schoolClass->name ?? 'Sesi Kelas' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <button type="button" onclick="window.location.reload()" class="btn btn-outline-primary btn-sm rounded-3">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Peserta
    </button>
</div>

{{-- Info Sesi --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h5 class="fw-bold text-dark mb-1">{{ $session->exam->title ?? 'Detail Ujian' }}</h5>
            <div class="text-muted small">
                <span class="me-3"><i class="bi bi-building me-1"></i> Kelas: {{ $session->cst->schoolClass->name ?? '-' }}</span>
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

{{-- Tabel Peserta --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom p-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h6 class="fw-bold mb-0 text-dark">Daftar Peserta Ujian ({{ $participants->count() }} Siswa)</h6>
        <div class="input-group" style="width: 260px;">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" id="searchParticipant" class="form-control bg-light border-start-0" placeholder="Cari nama atau NIS...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="participantsTable">
            <thead class="table-light text-muted small">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Nama Siswa</th>
                    <th>NIS / NISN</th>
                    <th>Mulai Mengerjakan</th>
                    <th>Pelanggaran</th>
                    <th>Nilai</th>
                    <th class="pe-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants as $index => $p)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $p->student->user->name ?? 'Nama Siswa' }}</div>
                        </td>
                        <td class="text-muted">{{ $p->student->nisn ?? '-' }}</td>
                        <td class="text-muted small">
                            <i class="bi bi-clock me-1"></i> {{ $p->start_time ?? '-' }}
                        </td>
                        <td>
                            @if(($p->violation_count ?? 0) > 0)
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2">
                                    {{ $p->violation_count }}x
                                </span>
                            @else
                                <span class="text-muted small">0</span>
                            @endif
                        </td>
                        <td class="fw-semibold">
                            {{ $p->total_score !== null ? number_format($p->total_score, 1) : '-' }}
                        </td>
                        <td class="pe-4">
                            @if($p->session_status === 'selesai')
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">Selesai</span>
                            @elseif($p->session_status === 'berlangsung')
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Mengerjakan</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Belum Mulai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            Belum ada peserta yang terdaftar di kelas ujian ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Rincian Log Pelanggaran (dari View v_violation_summary) --}}
@if($violations->isNotEmpty())
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom p-4">
        <h6 class="fw-bold mb-0 text-danger">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> Rincian Pelanggaran Siswa
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted small">
                <tr>
                    <th class="ps-4">NIS</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Pelanggaran</th>
                    <th>Jumlah</th>
                    <th class="pe-4">Terakhir Terjadi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($violations as $v)
                    <tr>
                        <td class="ps-4 text-muted">{{ $v->nis }}</td>
                        <td class="fw-semibold text-dark">{{ $v->student_name }}</td>
                        <td><span class="badge bg-warning-subtle text-warning-emphasis">{{ $v->violation_type }}</span></td>
                        <td class="fw-bold text-danger">{{ $v->total }}x</td>
                        <td class="pe-4 text-muted small">
                            {{ $v->last_occurred_at ? \Carbon\Carbon::parse($v->last_occurred_at)->format('d M Y H:i:s') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<script>
document.getElementById('searchParticipant')?.addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll('#participantsTable tbody tr').forEach(function (row) {
        row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
    });
});
</script>
@endsection