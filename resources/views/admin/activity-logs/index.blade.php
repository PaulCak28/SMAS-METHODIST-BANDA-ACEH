@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">Log Aktivitas Perubahan Nilai</h4>
            <p class="text-muted small mb-0">
                Riwayat audit otomatis dari trigger <code>trg_grades_ai</code>, <code>trg_grades_au</code>, dan <code>trg_grades_ad</code>.
            </p>
        </div>
        <span class="badge bg-secondary">Read-Only Audit</span>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-2">
                <div class="col-md-3">
                    <select name="action" class="form-select">
                        <option value="">-- Semua Aksi --</option>
                        <option value="insert" {{ request('action') === 'insert' ? 'selected' : '' }}>Insert</option>
                        <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Delete</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari NIS Siswa, CST ID, Grade ID, atau DB User..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Log ID</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                        <th>Siswa (NIS)</th>
                        <th>CST ID</th>
                        <th>Tipe</th>
                        <th>Skor Lama</th>
                        <th>Skor Baru</th>
                        <th>Akun MySQL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>#{{ $log->log_id }}</td>
                            <td class="small">{{ $log->changed_at?->format('d M Y H:i:s') }}</td>
                            <td>
                                @php
                                    $badge = match($log->action) {
                                        'insert' => 'bg-success',
                                        'update' => 'bg-warning text-dark',
                                        'delete' => 'bg-danger',
                                        default  => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badge }} text-uppercase">{{ $log->action }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $log->student->name ?? $log->students_id }}</div>
                                <small class="text-muted">NIS: {{ $log->students_id }} · Grade #{{ $log->grade_id }}</small>
                            </td>
                            <td><code>{{ $log->cst_id }}</code></td>
                            <td>{{ $log->grade_type }}</td>
                            <td>{{ $log->old_score !== null ? number_format($log->old_score, 2) : '-' }}</td>
                            <td class="fw-bold">{{ $log->new_score !== null ? number_format($log->new_score, 2) : '-' }}</td>
                            <td><code class="small">{{ $log->db_user }}</code></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada data log aktivitas nilai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection