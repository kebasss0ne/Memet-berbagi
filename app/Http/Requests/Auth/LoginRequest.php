<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * 🔥 Progressive delay (anti brute force)
     */
    protected function applyDelay(): void
    {
        $attempts = RateLimiter::attempts($this->throttleKey());

        // max 5 detik
        $delay = min($attempts, 5);

        sleep($delay);
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');

        // 🔐 LOGIN NORMAL DULU (STEALTH)
        if (!Auth::attempt($credentials, $this->boolean('remember'))) {

            $this->applyDelay();

            RateLimiter::hit($this->throttleKey());

            // ❌ SEMUA ERROR DISAMAKAN
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        // 🔍 CEK ROLE SETELAH LOGIN
        $user = Auth::user();

        // ❌ BLOK ADMIN MASUK VIA /login
        if ($user && $user->role === 'admin') {

            Auth::logout();

            $this->applyDelay();

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        // ✅ LOGIN BERHASIL
        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Terlalu banyak percobaan login. Coba lagi dalam '.$seconds.' detik.',
        ]);
    }

    public function throttleKey(): string
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
