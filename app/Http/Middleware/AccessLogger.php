<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AccessLog;
use Illuminate\Support\Facades\Auth;

class AccessLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $failed = false;

        if (
            $request->is('login') &&
            $request->method() === 'POST' &&
            !Auth::check()
        ) {
            $failed = true;
        }

        AccessLog::create([
            'ip' => $request->server->get('REMOTE_ADDR'),

            'user_email' => Auth::check()
                ? Auth::user()->email
                : $request->email,

            'method' => $request->method(),

            'path' => $request->path(),

            'status' => method_exists($response, 'getStatusCode')
                ? $response->getStatusCode()
                : 200,

            'failed_login' => $failed,

            'access_time' => now()
        ]);

        return $response;
    }
}
