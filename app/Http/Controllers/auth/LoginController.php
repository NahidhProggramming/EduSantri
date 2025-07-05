<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Validasi format username
        if ($field === 'username' && !preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
            return back()->withErrors([
                'login' => 'Format username tidak valid. Hanya boleh mengandung huruf, angka dan underscore.'
            ]);
        }

        $credentials = [
            $field => $login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Verify Spatie Permission is properly loaded
            if (!method_exists($user, 'hasRole')) {
                Auth::logout();
                return back()->withErrors(['login' => 'Sistem autentikasi tidak berfungsi dengan baik.']);
            }

            // Check if user has any roles assigned
            if ($user->roles->isEmpty()) {
                Auth::logout();
                return back()->withErrors(['login' => 'Akun Anda tidak memiliki role yang valid.']);
            }

            // Validate login method based on role
            $isAdmin = $user->hasRole('admin');

            if ($field === 'email' && !$isAdmin) {
                Auth::logout();
                return back()->withErrors(['login' => 'Akses hanya untuk admin.']);
            }

            if ($field === 'username' && $isAdmin) {
                Auth::logout();
                return back()->withErrors(['login' => 'Admin harus login menggunakan email.']);
            }

            // Redirect berdasarkan role
            $redirectTo = match (true) {
                $user->hasRole('admin') => route('user.index'),
                $user->hasRole('guru') => route('nilai.index'),
                default => route('home') // Redirect to new home page for regular users
            };

            return $request->remember
                ? redirect($redirectTo)->with('status', 'Anda akan tetap login selama 120 menit')
                : redirect($redirectTo);
        }

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
