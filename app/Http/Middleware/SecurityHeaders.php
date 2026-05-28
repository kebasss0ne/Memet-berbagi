<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        return $response->header('X-Frame-Options', 'SAMEORIGIN')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-XSS-Protection', '1; mode=block')
            ->header('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains')
            ->header(
                'Content-Security-Policy',
                "default-src 'self'; " .
                "script-src 'self' https://cdn.tailwindcss.com; " .
                "style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; " .
                "img-src 'self' data:; " .
                "font-src 'self' data:; " .
                "connect-src 'self'; " .
                "frame-ancestors 'self'; " .
                "form-action 'self';"
            );
    }
}
