<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Support\AnnouncementContentSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $announcements = Announcement::with('creator')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            })
            ->latest('announcement_id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.announcements.index', compact('announcements', 'search'));
    }

    public function create(): View
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request, AnnouncementContentSanitizer $sanitizer): RedirectResponse
    {
        $v = $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'nullable|url|max:255',
            'content' => 'required|string|max:50000',
            'target_roles' => 'required|array',
            'target_roles.*' => Rule::in(['all', 'teacher', 'student']),
        ]);

        $content = $sanitizer->sanitize($v['content']);

        if (! $sanitizer->hasMeaningfulContent($content)) {
            throw ValidationException::withMessages([
                'content' => 'Isi pengumuman wajib diisi.',
            ]);
        }

        Announcement::create([
            'title' => $v['title'],
            'link' => $v['link'] ?? null,
            'content' => $content,
            'target_roles' => $v['target_roles'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    public function show(Announcement $announcement, AnnouncementContentSanitizer $sanitizer): View
    {
        $announcement->load('creator');
        $safeContent = $sanitizer->sanitize($announcement->content);

        return view('admin.announcements.show', compact('announcement', 'safeContent'));
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(
        Request $request,
        Announcement $announcement,
        AnnouncementContentSanitizer $sanitizer,
    ): RedirectResponse {
        $v = $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'nullable|url|max:255',
            'content' => 'required|string|max:50000',
            'target_roles' => 'required|array',
            'target_roles.*' => Rule::in(['all', 'teacher', 'student']),
        ]);

        $content = $sanitizer->sanitize($v['content']);

        if (! $sanitizer->hasMeaningfulContent($content)) {
            throw ValidationException::withMessages([
                'content' => 'Isi pengumuman wajib diisi.',
            ]);
        }

        if ($announcement->title !== $v['title']) {
            $announcement->slug = null;
        }

        $announcement->update([
            'title' => $v['title'],
            'link' => $v['link'] ?? null,
            'content' => $content,
            'target_roles' => $v['target_roles'],
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
