<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(Request $request): View
    {
        return view('login', [
            'lastEmail' => $request->cookie('del_last_user_email'),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // WAJIB: ganti Session ID setelah login (anti Session Fixation)
            $request->session()->regenerate();

            session(['login_timestamp' => now()->format('d M Y, H:i:s') . ' WIB']);

            // Cookie client-side, 10080 menit = 7 hari
            Cookie::queue('del_last_user_email', $request->email, 10080);

            return redirect()->intended(route('buku.index'))
                ->with('success', 'Sesi Anda telah aktif.');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Kredensial login tidak valid.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Sesi server telah dihapus dengan aman.');
    }
}