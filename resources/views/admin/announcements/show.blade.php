@extends('layouts.admin')
@section('title', 'Detail Pengumuman')

@section('content')
<div class="mb-4 d-flex align-items-center gap-3">
    <a href="{{ route('admin.announcements.index') }}" class="btn btn-white border-0 shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
        <i class="bi bi-arrow-left text-dark fs-5"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-1 text-dark">Rincian Pengumuman</h4>
    </div>
</div>

<div class="row">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    @foreach(array_unique($announcement->target_roles ?? []) as $role)
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 text-uppercase small fw-bold me-1">
                            {{ $role == 'teacher' ? 'Guru' : ($role == 'student' ? 'Siswa' : 'Semua') }}
                        </span>
                    @endforeach
                </div>
                <span class="text-muted small"><i class="bi bi-calendar-event me-1"></i> {{ $announcement->created_at->format('d M Y, H:i') }}</span>
            </div>

            <h3 class="fw-bold text-dark mb-3">{{ $announcement->title }}</h3>
            
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 pb-3 border-bottom">
                <span class="text-muted small mb-0">Dipublikasikan oleh: <span class="fw-semibold text-dark">{{ $announcement->creator->name ?? 'Admin' }}</span></span>
                
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-secondary border fw-normal py-1 px-2">
                        <i class="bi bi-link-45deg"></i> {{ route('admin.announcements.show', $announcement->slug ?: $announcement->announcement_id) }}
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="navigator.clipboard.writeText('{{ route('admin.announcements.show', $announcement->slug ?: $announcement->announcement_id) }}'); alert('Tautan berhasil disalin!')" title="Salin Tautan">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
            </div>

            <div class="announcement-content text-dark lh-lg">
                {!! $safeContent !!}
            </div>

            <div class="mt-5 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('admin.announcements.edit', $announcement->slug ?: $announcement->announcement_id) }}" class="btn btn-warning text-dark px-4 rounded-3 fw-medium">Edit</a>
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-light border px-4 rounded-3 fw-medium">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .announcement-content img {
            display: block;
            max-width: 100%;
            height: auto;
            margin: 1.5rem auto;
            border-radius: 0.75rem;
        }

        .announcement-content blockquote {
            padding-left: 1rem;
            border-left: 4px solid var(--bs-primary);
            color: var(--bs-secondary-color);
        }

        .announcement-content li[data-list="bullet"] {
            list-style-type: disc;
        }
    </style>
@endpush
