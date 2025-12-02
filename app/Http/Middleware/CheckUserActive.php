<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // 1. Logic untuk Pengguna NONAKTIF (is_active = 0)
            if ($user->is_active == 0) {
                // Jika user NONAKTIF, biarkan mereka di halaman 'account.deactivated' 
                // untuk mencegah redirect loop.
                if ($request->routeIs('account.deactivated')) {
                    return $next($request);
                }
                
                // Jika mereka mencoba ke rute lain (misal /dashboard), paksa redirect ke halaman nonaktif.
                return redirect()->route('account.deactivated');
            }
            
            // 2. Logic untuk Pengguna AKTIF (is_active = 1)
            // Jika user AKTIF, dan mereka mencoba mengakses halaman nonaktif, 
            // kita redirect kembali ke dashboard.
            if ($user->is_active == 1 && $request->routeIs('account.deactivated')) {
                return back();
            }
        }
        
        // Lanjutkan request untuk user yang belum login atau user yang aktif
        return $next($request);
    }
}