<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check if user status is inactive
            if ($user->status === 'inactive') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['Akun Anda belum diaktifkan. Silakan hubungi admin untuk aktivasi akun.'],
                ]);
            }

            $request->session()->regenerate();

            // Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin');
            }

            if ($user->hasRole('karyawan')) {
                return redirect()->intended(route('karyawan.dashboard'));
            }

            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
