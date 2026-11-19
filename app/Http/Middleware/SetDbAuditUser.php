<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetDbAuditUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userId = Auth::id();

            foreach (['mysql', 'mysql_admin', 'mysql_guru', 'mysql_siswa'] as $connName) {
                if (config("database.connections.{$connName}")) {
                    try {
                        DB::connection($connName)->statement('SET @app_user_id = ?', [$userId]);
                    } catch (\Throwable $e) {
                        // Abaikan jika koneksi tidak dipakai pada role ini
                    }
                }
            }
        }

        return $next($request);
    }
}