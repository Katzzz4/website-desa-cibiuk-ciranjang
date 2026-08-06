<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Memastikan hanya pengguna berwenang yang dapat membuka dashboard.
 *
 * Bila peran akun tidak memiliki akses sama sekali (misalnya "Tanpa Akses"),
 * sesinya langsung diakhiri lalu dikembalikan ke halaman masuk. Tanpa ini,
 * pengguna akan terjebak: akun tetap dianggap masuk, sehingga halaman login
 * selalu dialihkan kembali ke dashboard dan ditolak berulang-ulang.
 */
class EnsureIsAdmin
{
    /** Peran yang diizinkan membuka dashboard */
    private const PERAN_BERWENANG = ['superadmin', 'admin', 'kadus'];

    public function handle(Request $request, Closure $next): Response
    {
        $pengguna = $request->user();

        if (!$pengguna) {
            return redirect()->route('login');
        }

        if (!in_array($pengguna->role, self::PERAN_BERWENANG, true)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun ini tidak memiliki akses ke dashboard desa. '
                    . 'Silakan masuk dengan akun yang berwenang, atau hubungi Super Admin desa.',
            ]);
        }

        return $next($request);
    }
}