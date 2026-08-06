<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdminDesa
{
    public function handle(Request $request, Closure $next): Response
    {
        $pengguna = $request->user();

        if (!$pengguna || !in_array($pengguna->role, ['superadmin', 'admin'], true)) {
            abort(403, 'Menu ini hanya dapat diakses oleh Admin Desa. '
                . 'Sebagai Kepala Dusun, Anda dapat menangani laporan warga di dusun Anda.');
        }

        return $next($request);
    }
}