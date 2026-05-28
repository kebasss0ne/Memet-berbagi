<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        // bersihin OTP session biar gak nyangkut
        session()->forget('otp_email');
        session()->forget('otp_user_id');

        // CAPTCHA
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);

        session([
            'captcha_answer' => $num1 + $num2
        ]);

        return view('auth.login', compact('num1', 'num2'));
    }

    public function store(Request $request)
    {
        // VALIDASI CAPTCHA
        if ($request->captcha != session('captcha_answer')) {

            throw ValidationException::withMessages([
                'captcha' => 'Captcha salah.'
            ]);
        }

        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
            'captcha'  => ['required'],
        ]);

        $email    = strtolower(trim($request->email));
        $password = (string) $request->password;

        // 🔒 RATE LIMIT LOGIN
        $key = 'user-login-'.$request->ip().'-'.$email;

        if (RateLimiter::tooManyAttempts($key, 5)) {

            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."
            ]);
        }

        $user = User::where('email', $email)->first();

        // ❌ USER TIDAK ADA
        if (!$user) {

            RateLimiter::hit($key, 300);

            throw ValidationException::withMessages([
                'email' => 'Login gagal.'
            ]);
        }

        // ❌ PASSWORD SALAH
        if (!Hash::check($password, $user->password)) {

            RateLimiter::hit($key, 300);

            throw ValidationException::withMessages([
                'email' => 'Login gagal.'
            ]);
        }

        // ❌ ADMIN BLOCK
        if ($user->role === 'admin') {

            RateLimiter::hit($key, 300);

            throw ValidationException::withMessages([
                'email' => 'Login gagal.'
            ]);
        }

        // 🔥 BELUM VERIFIED -> OTP
        if (!$user->email_verified_at) {

            session([
                'otp_email' => $user->email,
                'otp_user_id' => $user->id
            ]);

            return redirect()->route('otp.form');
        }

        // ✅ LOGIN BERHASIL → RESET COUNTER
        RateLimiter::clear($key);

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function destroy(Request $request)
    {
        // reset admin mode
        session()->forget('admin_unlocked');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
