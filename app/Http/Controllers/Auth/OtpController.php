<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Mail\OtpMail;

class OtpController extends Controller
{
    public function show()
    {
        if (!session('otp_email') || !session('otp_user_id')) {
            return redirect('/login')
                ->withErrors(['email' => 'Silakan login atau register ulang']);
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required','digits:6']
        ]);

        $email  = session('otp_email');
        $userId = session('otp_user_id');

        if (!$email || !$userId) {
            return redirect('/login')
                ->withErrors(['email' => 'Session habis']);
        }

        $user = User::where('id', $userId)
            ->where('email', $email)
            ->first();

        if (!$user) {
            return redirect('/register');
        }

        $key = 'otp-verify-'.$request->ip().'-'.$email;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(429, 'Terlalu banyak percobaan OTP.');
        }

        if (!$user->otp_expired_at || now()->gt($user->otp_expired_at)) {
            RateLimiter::hit($key, 60);
            return back()->with('error', 'OTP expired');
        }

        if (!Hash::check($request->otp, $user->otp)) {
            RateLimiter::hit($key, 60);
            return back()->with('error', 'OTP salah');
        }

        RateLimiter::clear($key);

        $user->email_verified_at = now();
        $user->otp = null;
        $user->otp_expired_at = null;
        $user->save();

        session()->forget(['otp_email','otp_user_id']);

        return redirect('/login')->with('status', 'OTP berhasil diverifikasi');
    }

    public function resend(Request $request)
    {
        $email  = session('otp_email');
        $userId = session('otp_user_id');

        if (!$email || !$userId) {
            return redirect('/login');
        }

        $user = User::where('id', $userId)
            ->where('email', $email)
            ->first();

        if (!$user) {
            return redirect('/login');
        }

        $key = 'otp-resend-'.$request->ip().'-'.$email;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            abort(429, 'Terlalu sering meminta OTP.');
        }

        if (session()->has('otp_last_sent') &&
            now()->diffInSeconds(session('otp_last_sent')) < 60) {
            return back()->with('error', 'Tunggu 60 detik');
        }

        $otp = random_int(100000, 999999);

        $user->otp = Hash::make($otp);
        $user->otp_expired_at = now()->addMinutes(5);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otp));

        session(['otp_last_sent' => now()]);

        RateLimiter::hit($key, 60);

        return back()->with('status', 'OTP baru dikirim');
    }
}
