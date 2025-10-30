<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Pastikan user sudah login
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Cek apakah role user termasuk dalam roles yang diizinkan
        if (!in_array($user->role, $roles)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
