<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // ❌ BELUM LOGIN
        if (!Auth::check()) {
            abort(401);
        }

        // ❌ BUKAN ADMIN
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // 🔒 AMBIL REAL IP LANGSUNG DARI SERVER
        $realIp = $request->server->get('REMOTE_ADDR');

        // 🔒 WHITELIST IP DARI .ENV
        $allowedIps = config('admin.allowed_ips', []);

        if (!in_array($realIp, $allowedIps, true)) {
            abort(404);
        }

        return $next($request);
    }
}
