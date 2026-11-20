@extends('layouts.public')

@section('title', $announcement->title.' | SMAS Methodist Banda Aceh')

@section('content')
@php
    $published = $announcement->published_at ?? $announcement->created_at;
    $isHtml = $announcement->content !== strip_tags($announcement->content);
@endphp

<article class="mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-16">
    <a href="{{ route('announcements.index') }}" class="text-sm font-semibold text-navy hover:text-softblue">&larr; Semua pengumuman</a>

    <div class="mt-6 flex flex-wrap items-center gap-3 text-xs">
        <span class="rounded-full bg-softblue/30 px-3 py-1 font-semibold text-navy">{{ $announcement->category_label ?? 'Pengumuman' }}</span>
        @if ($published)
            <time datetime="{{ $published->toDateString() }}" class="text-navy/60">
                {{ $published->translatedFormat('d F Y') }}
            </time>
        @endif
    </div>

    <h1 class="mt-4 font-serif text-3xl font-bold leading-tight text-navy sm:text-4xl">{{ $announcement->title }}</h1>

    @if (! empty($announcement->image_url))
        <img src="{{ $announcement->image_url }}" alt="" class="mt-8 w-full rounded-xl object-cover">
    @endif

    <div class="mt-8 break-words leading-8 text-navy/90
                [&_p]:mb-4 [&_a]:font-semibold [&_a]:text-softblue [&_a]:underline
                [&_ul]:mb-4 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:mb-4 [&_ol]:list-decimal [&_ol]:pl-6
                [&_h2]:mb-3 [&_h2]:mt-6 [&_h2]:font-serif [&_h2]:text-2xl [&_h2]:font-bold
                [&_h3]:mb-2 [&_h3]:mt-5 [&_h3]:font-serif [&_h3]:text-xl [&_h3]:font-bold
                [&_blockquote]:border-l-4 [&_blockquote]:border-softblue [&_blockquote]:pl-4 [&_blockquote]:italic
                [&_img]:my-4 [&_img]:h-auto [&_img]:max-w-full [&_img]:rounded-xl">
        @if ($isHtml)
            {!! $announcement->content !!}
        @else
            {!! nl2br(e($announcement->content)) !!}
        @endif
    </div>
</article>

@if (isset($related) && $related->isNotEmpty())
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <h2 class="font-serif text-2xl font-bold text-navy">Info lainnya</h2>
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($related as $item)
                    @include('partials.announcement-card', ['announcement' => $item])
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection