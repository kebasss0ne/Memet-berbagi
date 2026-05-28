<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class HidePrivilegeMap
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            abort(404);
        }

        if (!Auth::user()->isAdmin()) {
            abort(404);
        }

        // ❌ MATIKAN DULU SEMUA CHECK TAMBAHAN
        // biar sistem stabil

        return $next($request);
    }
}
