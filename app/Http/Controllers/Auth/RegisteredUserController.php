<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules;
use App\Mail\OtpMail;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $key = 'register-'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            abort(429, 'Terlalu banyak percobaan register');
        }

        RateLimiter::hit($key, 60);

        $validated = $request->validate([
            'name' => ['required','string','max:255'],

 // 🔥 HANYA GMAIL
        'email' => [
            'required',
            'email',
            'max:255',
            'unique:users,email',
            'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
        ],

        // 🔥 PASSWORD STRONG
        'password' => [
            'required',
            'confirmed',
            Rules\Password::min(8)
                ->letters()      // huruf
                ->mixedCase()   // besar + kecil
                ->numbers()     // angka
                ->symbols()     // simbol
        ],

        ]);

        // 🔐 NORMALISASI INPUT
        $email = strtolower(trim($validated['email']));
        $name  = strip_tags(trim($validated['name']));

        // 🔥 SECURE OTP
        $otp = random_int(100000, 999999);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($validated['password']),
        ]);

        $user->role = 'user';
        $user->email_verified_at = null;
        $user->otp = Hash::make($otp);
        $user->otp_expired_at = Carbon::now()->addMinutes(5);
        $user->save();

        session([
            'otp_email' => $user->email,
	    'otp_user_id' => $user->id
        ]);

        Mail::to($user->email)->send(new OtpMail($otp));

        return redirect('/verify-otp');
    }
}
