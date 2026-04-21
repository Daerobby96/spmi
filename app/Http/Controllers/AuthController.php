<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user && !$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Log activity
            Auth::user()->logActivity('login', 'User logged in to the system');

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Log activity before session is destroyed
        if (Auth::check()) {
            Auth::user()->logActivity('logout', 'User logged out from the system');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}
