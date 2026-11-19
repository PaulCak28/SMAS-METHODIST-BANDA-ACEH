<?php

use App\Http\Middleware\PreventAuthenticatedPageCaching;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

test('authenticated responses cannot be restored from browser cache', function () {
    $request = Request::create('/siswa');
    $request->setUserResolver(fn () => new User(['user_id' => '90001', 'role' => 'siswa']));

    $response = (new PreventAuthenticatedPageCaching)->handle(
        $request,
        fn () => new Response('private page')
    );

    expect($response->headers->get('Cache-Control'))->toContain('no-store')
        ->and($response->headers->get('Pragma'))->toBe('no-cache')
        ->and($response->headers->get('Expires'))->toBe('0');
});
