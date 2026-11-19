<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetDbUser
{
    public function handle(Request $request, Closure $next): Response
    {
        DB::statement('SET @app_user_id = ?', [optional($request->user())->getAuthIdentifier()]);

        return $next($request);
    }
}