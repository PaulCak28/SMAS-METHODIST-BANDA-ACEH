<?php

use App\Models\Announcement;
use App\Models\User;

it('renders the rich text editor for an admin', function () {
    $admin = User::factory()->admin()->create();

    $response = $this
        ->actingAs($admin)
        ->get(route('admin.announcements.create'));

    $response
        ->assertOk()
        ->assertSee('quill@2.0.3')
        ->assertSee('admin\\/announcements\\/images', false);
});

it('redirects guests away from the announcement editor', function () {
    $response = $this->get(route('admin.announcements.create'));

    $response->assertRedirect(route('login'));
});

it('forbids non-admin users from the announcement editor', function () {
    $teacher = User::factory()->guru()->create();

    $response = $this
        ->actingAs($teacher)
        ->get(route('admin.announcements.create'));

    $response->assertForbidden();
});

it('stores supported rich content and removes unsafe markup', function () {
    $admin = User::factory()->admin()->create();
    $content = <<<'HTML'
<h2 onclick="alert('xss')">Agenda Sekolah</h2>
<p><strong>Harap hadir.</strong> <a href="https://example.com">Detail</a></p>
<p><a href="javascript:alert('xss')">Tautan tidak aman</a></p>
<img src="http://localhost/storage/announcement-images/2026/11/agenda.webp" onerror="alert('xss')">
<script>alert('xss')</script>
HTML;

    $response = $this
        ->actingAs($admin)
        ->post(route('admin.announcements.store'), [
            'title' => 'Agenda Sekolah',
            'link' => 'https://example.com/agenda',
            'content' => $content,
            'target_roles' => ['all'],
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.announcements.index'));

    $announcement = Announcement::query()->firstOrFail();

    expect($announcement->content)
        ->toContain('<h2>Agenda Sekolah</h2>')
        ->toContain('href="https://example.com"')
        ->toContain('/storage/announcement-images/2026/11/agenda.webp')
        ->not->toContain('onclick')
        ->not->toContain('javascript:')
        ->not->toContain('onerror')
        ->not->toContain('<script');
});

it('rejects rich content without text or a stored image', function () {
    $admin = User::factory()->admin()->create();

    $response = $this
        ->actingAs($admin)
        ->post(route('admin.announcements.store'), [
            'title' => 'Pengumuman Kosong',
            'content' => '<p><br></p>',
            'target_roles' => ['all'],
        ]);

    $response->assertInvalid(['content' => 'Isi pengumuman wajib diisi.']);
    $this->assertDatabaseEmpty('announcements');
});

it('renders sanitized rich content on the public announcement page', function () {
    $admin = User::factory()->admin()->create();
    $announcement = Announcement::query()->create([
        'title' => 'Informasi Publik',
        'content' => '<p><strong>Informasi aman</strong></p><script>alert("xss")</script>',
        'target_roles' => ['all'],
        'is_public' => true,
        'published_at' => now(),
        'created_by' => $admin->user_id,
    ]);

    $response = $this->get(route('announcements.show', $announcement));

    $response
        ->assertOk()
        ->assertSee('<strong>Informasi aman</strong>', false)
        ->assertDontSee('<script>alert', false)
        ->assertDontSee('alert("xss")', false);
});
