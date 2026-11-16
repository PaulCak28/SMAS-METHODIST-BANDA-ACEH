<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

it('stores an admin image as a public webp file', function () {
    Storage::fake('public');
    $admin = User::factory()->admin()->create();
    $image = UploadedFile::fake()->image('kegiatan-sekolah.png', 1200, 800);

    $response = $this
        ->actingAs($admin)
        ->postJson(route('admin.announcements.images.store'), ['image' => $image]);

    $response
        ->assertOk()
        ->assertJsonStructure(['url']);

    $storedPath = Str::after((string) parse_url($response->json('url'), PHP_URL_PATH), '/storage/');

    expect($storedPath)->toEndWith('.webp');
    Storage::disk('public')->assertExists($storedPath);
});

it('rejects unsupported image uploads', function () {
    Storage::fake('public');
    $admin = User::factory()->admin()->create();
    $image = UploadedFile::fake()->create('vector.svg', 10, 'image/svg+xml');

    $response = $this
        ->actingAs($admin)
        ->postJson(route('admin.announcements.images.store'), ['image' => $image]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors('image');
    Storage::disk('public')->assertDirectoryEmpty('announcement-images');
});

it('returns 401 when an unauthenticated user uploads an image', function () {
    $image = UploadedFile::fake()->image('kegiatan.png');

    $response = $this->postJson(route('admin.announcements.images.store'), ['image' => $image]);

    $response->assertUnauthorized();
});

it('forbids non-admin users from uploading announcement images', function () {
    $teacher = User::factory()->guru()->create();
    $image = UploadedFile::fake()->image('kegiatan.png');

    $response = $this
        ->actingAs($teacher)
        ->postJson(route('admin.announcements.images.store'), ['image' => $image]);

    $response->assertForbidden();
});
