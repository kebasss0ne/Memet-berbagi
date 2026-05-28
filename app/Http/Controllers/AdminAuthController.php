<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Cek apakah user ini punya role admin di database
            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended('/admin/dashboard');
            }

            // Kalau bukan admin, langsung logout-kan lagi
            Auth::logout();
            return back()->withErrors(['email' => 'Anda tidak memiliki akses admin!']);
        }

        return back()->withErrors(['email' => 'Email atau Password salah.']);
    }
}