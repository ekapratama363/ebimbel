<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (! Auth::attempt([$field => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi tidak valid.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $route = $user->firstAccessibleRoute();

        if (! $route) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda belum memiliki izin akses ke modul manapun.',
            ]);
        }

        return redirect()
            ->route($route)
            ->with('status', 'Selamat datang, '.$user->name.'.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda telah keluar.');
    }
}
