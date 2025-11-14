<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware(['auth', 'role:operator,admin'])
     */
    public function handle(Request $request, Closure $next, $roles = null)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Superadmin always allowed
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // If no roles specified, deny
        if (! $roles) {
            abort(403);
        }

        $allowed = array_map('trim', explode(',', $roles));

        if (in_array($user->role, $allowed)) {
            return $next($request);
        }

        abort(403);
    }
}
