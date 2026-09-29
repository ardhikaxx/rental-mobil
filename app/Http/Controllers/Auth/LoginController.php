<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(Request $request): View
    {
        if ($request->user() !== null) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $username = trim($request->string('username')->toString());
        $password = (string) $request->input('password');
        $remember = $request->boolean('remember');

        if (! Auth::attempt(['username' => $username, 'password' => $password], $remember)) {
            AuditLogger::log('login_failed', 'auth', "Percobaan login gagal untuk username \"{$username}\".");

            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        $user = Auth::user();

        if (! $user->isActive()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            AuditLogger::log('login_failed', 'auth', "Akun nonaktif mencoba masuk: \"{$username}\".");

            throw ValidationException::withMessages([
                'username' => 'Akun Anda telah dinonaktifkan. Hubungi Super Admin untuk mengaktifkan kembali.',
            ]);
        }

        $request->session()->regenerate();

        AuditLogger::log('login', 'auth', "{$user->name} masuk ke sistem.", null, $user);

        return redirect()->intended(route('dashboard'))->with('success', "Selamat datang, {$user->name}.");
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user !== null) {
            AuditLogger::log('logout', 'auth', "{$user->name} keluar dari sistem.", null, $user);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar dari sistem.');
    }
}
