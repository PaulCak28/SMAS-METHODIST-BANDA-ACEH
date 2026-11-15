<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use App\Support\AnnouncementContentSanitizer;

use RuntimeException;

class AnnouncementImageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => [
                'required',
                File::image()
                    ->types(['jpg', 'jpeg', 'png', 'webp'])
                    ->max('5mb')
                    ->dimensions(Rule::dimensions()->maxWidth(5000)->maxHeight(5000)),
            ],
        ]);

        $uploadedImage = $request->file('image');
        $image = $uploadedImage === null ? false : imagecreatefromstring($uploadedImage->getContent());

        if ($image === false) {
            throw new RuntimeException('Gambar pengumuman gagal diproses.');
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        ob_start();
        $encoded = imagewebp($image, null, 82);
        $contents = ob_get_clean();
        imagedestroy($image);

        if (! $encoded || ! is_string($contents)) {
            throw new RuntimeException('Gambar pengumuman gagal dikonversi.');
        }

        $path = 'announcement-images/'.now()->format('Y/m').'/'.Str::random(40).'.webp';

        if (! Storage::disk('public')->put($path, $contents, 'public')) {
            throw new RuntimeException('Gambar pengumuman gagal disimpan.');
        }

        return response()->json([
            'url' => Storage::disk('public')->url($path),
        ]);
    }
}
