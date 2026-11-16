<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SwitchRoleDatabaseConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->user()?->role;

        $connection = match ($role) {
            'admin' => 'mysql_admin',
            'guru'  => 'mysql_guru',
            'siswa' => 'mysql_siswa',
            default => config('database.default'),
        };

        if (DB::getDefaultConnection() !== $connection) {
            DB::setDefaultConnection($connection);
        }

        return $next($request);
    }
}