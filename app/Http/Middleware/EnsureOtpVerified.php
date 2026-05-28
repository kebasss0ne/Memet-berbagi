<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureOtpVerified
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user && !$user->email_verified_at) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // 🔥 bersihin semua kemungkinan session OTP/login nyangkut
            session()->forget('otp_email');
            session()->forget('otp_user_id');

            return redirect('/login')->withErrors([
                'email' => 'Silakan verifikasi OTP terlebih dahulu.'
            ]);
        }

        return $next($request);
    }
}
