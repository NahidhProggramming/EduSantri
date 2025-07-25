<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // email vs username
        $login  = $request->input('login');
        $field  = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // validasi extra utk username
        if ($field === 'username' && !preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
            return back()->withErrors(['login' => 'Format username tidak valid.']);
        }

        $credentials = [$field => $login, 'password' => $request->password];

        // ======== AUTH ATTEMPT ========
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // ---------- pastikan Spatie Permission tersedia ----------
            if (!method_exists($user, 'hasRole')) {
                Auth::logout();
                return back()->withErrors(['login' => 'Sistem role belum dikonfigurasi.']);
            }

            // ---------- user harus punya role terdaftar ----------
            $allowedRoles = ['admin', 'guru', 'kesiswaan', 'wali_santri'];
            $userRole     = $user->roles->pluck('name')->first();   // ambil satu role (atau cek multi)

            if (!$userRole || !in_array($userRole, $allowedRoles)) {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Akun Anda tidak memiliki izin akses aplikasi.'
                ]);
            }

            // ---------- validasi metode login untuk admin ----------
            if ($userRole === 'admin' && $field !== 'email') {
                Auth::logout();
                return back()->withErrors(['login' => 'Admin harus login memakai email.']);
            }

            // ---------- redirect berdasarkan role ----------
            $redirectTo = match ($userRole) {
                'admin'      => route('admin.dashboard'),
                'guru'       => route('nilai.dashboard'),
                'kesiswaan'  => route('kesiswaan.dashboard'),
                'wali_santri'=> route('home'),
            };

            return redirect()->intended($redirectTo);
        }

        // ======== GAGAL AUTH ========
        return back()->withErrors([
            'login' => 'Login gagal! Periksa email/username dan password Anda.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
