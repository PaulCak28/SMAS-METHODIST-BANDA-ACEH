@extends('layouts.admin')
@section('title', 'Daftar Pengumuman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Kelola Pengumuman</h4>
        <p class="text-muted small mb-0">Publikasikan informasi dan pengumuman penting untuk warga sekolah.</p>
    </div>
    <div>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary shadow-sm rounded-3 fw-medium px-3">
            <i class="bi bi-plus-lg me-1"></i> Buat Pengumuman Baru
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0 bg-success text-white" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.announcements.index') }}" method="GET" class="row g-3">
            <div class="col-md-9">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0 text-muted px-3"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 fs-6" placeholder="Cari judul atau isi pengumuman..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-dark w-100 rounded-3 fw-medium btn-lg">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    @forelse($announcements as $item)
        @php
            // Isi pengumuman berupa HTML dari editor WYSIWYG (Quill).
            // Pratinjau dibangun ulang: teks di-escape, hanya tag <a> yang dipertahankan
            // (dengan href tervalidasi) supaya tulisan link bisa langsung diklik.
            $html = (string) $item->content;
            $hasImage = stripos($html, '<img') !== false;
            $limit = 120;

            $spaced = preg_replace('/<\/(p|h[1-6]|li|blockquote|pre|ul|ol)>|<br\s*\/?>/i', '$0 ', $html);
            $tokens = preg_split('/(<[^>]+>)/', strip_tags($spaced, '<a>'), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

            $excerpt = '';
            $length = 0;
            $anchorOpen = false;
            $truncated = false;

            foreach ($tokens as $token) {
                if ($token[0] === '<') {
                    if (preg_match('/^<a\s[^>]*href\s*=\s*"([^"]*)"/i', $token, $m)) {
                        $url = trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));

                        if ($anchorOpen) {
                            $excerpt .= '</a>';
                            $anchorOpen = false;
                        }

                        if (preg_match('/^(https?:\/\/|mailto:|tel:|\/|#)/i', $url)) {
                            $excerpt .= '<a href="'.e($url).'" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-underline">';
                            $anchorOpen = true;
                        }
                    } elseif (stripos($token, '</a') === 0 && $anchorOpen) {
                        $excerpt .= '</a>';
                        $anchorOpen = false;
                    }

                    continue;
                }

                $text = preg_replace('/\s+/u', ' ', html_entity_decode($token, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

                if ($length === 0) {
                    $text = ltrim($text);
                }

                $remaining = $limit - $length;

                if (mb_strlen($text) > $remaining) {
                    $text = rtrim(mb_substr($text, 0, $remaining));
                    $truncated = true;
                }

                $excerpt .= e($text);
                $length += mb_strlen($text);

                if ($truncated) {
                    break;
                }
            }

            if ($anchorOpen) {
                $excerpt .= '</a>';
            }

            if ($truncated) {
                $excerpt .= '&hellip;';
            }

            $hasText = $length > 0;

            // Thumbnail: ambil gambar pertama milik pengumuman. Yang dipakai hanya path-nya
            // (/storage/announcement-images/...) supaya tetap tampil walaupun host di APP_URL
            // berbeda dengan alamat yang dibuka di browser.
            $imageUrl = null;

            if (preg_match('/<img\s[^>]*src\s*=\s*"([^"]+)"/i', $html, $imageMatch)) {
                $imagePath = (string) parse_url(
                    trim(html_entity_decode($imageMatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8')),
                    PHP_URL_PATH
                );

                if (str_starts_with($imagePath, '/storage/announcement-images/')) {
                    $imageUrl = $imagePath;
                }
            }
        @endphp
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column justify-content-between">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            @foreach(array_unique($item->target_roles ?? []) as $role)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 text-uppercase small fw-bold me-1">
                                    {{ $role == 'teacher' ? 'Guru' : ($role == 'student' ? 'Siswa' : 'Semua') }}
                                </span>
                            @endforeach
                        </div>
                        <small class="text-muted"><i class="bi bi-clock me-1"></i> {{ $item->created_at->format('d M Y, H:i') }}</small>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">
                        <a href="{{ route('admin.announcements.show', $item->slug ?: $item->announcement_id) }}" class="text-dark text-decoration-none">
                            {{ $item->title }}
                        </a>
                    </h5>

                    @if($imageUrl)
                        <a href="{{ route('admin.announcements.show', $item->slug ?: $item->announcement_id) }}" class="d-block mb-3">
                            <img src="{{ $imageUrl }}" alt="" loading="lazy" class="w-100 rounded-3 border" style="height: 160px; object-fit: cover;">
                        </a>
                    @endif

                    @if($hasText)
                        <p class="text-muted small mb-3">{!! $excerpt !!}</p>
                    @elseif($hasImage && ! $imageUrl)
                        <p class="text-muted small fst-italic mb-3"><i class="bi bi-image me-1"></i> Pengumuman berisi gambar.</p>
                    @endif

                    @if($hasText && $hasImage && ! $imageUrl)
                        <div class="mb-3">
                            <span class="badge bg-light text-secondary border fw-normal">
                                <i class="bi bi-image me-1"></i> Berisi gambar
                            </span>
                        </div>
                    @endif

                </div>

                <div class="card-footer bg-white border-0 pt-0 pb-4 px-4 d-flex justify-content-between align-items-center">
                    <small class="text-muted">Oleh: <span class="fw-semibold text-dark">{{ $item->creator->name ?? 'Admin' }}</span></small>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.announcements.show', $item->slug ?: $item->announcement_id) }}" class="btn btn-sm btn-light border text-primary shadow-sm" title="Lihat"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.announcements.edit', $item->slug ?: $item->announcement_id) }}" class="btn btn-sm btn-light border text-warning shadow-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
                        <form action="{{ route('admin.announcements.destroy', $item->slug ?: $item->announcement_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengumuman ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light border text-danger shadow-sm" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <i class="bi bi-megaphone display-4 d-block mb-3 opacity-50 text-muted"></i>
                    <h6 class="fw-semibold text-dark">Belum ada pengumuman</h6>
                    <p class="small text-muted mb-0">Silakan buat pengumuman baru melalui tombol di atas.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($announcements->hasPages())
    <div class="mt-4">{{ $announcements->links() }}</div>
@endif
@endsection