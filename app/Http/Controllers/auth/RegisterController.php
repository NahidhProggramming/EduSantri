<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CaptchaHelper;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:guru,wali_santri,wali_asuh',
            'captcha_answer' => 'required|numeric'
        ], [
            'captcha_answer.required' => 'Harap jawab pertanyaan verifikasi',
            'captcha_answer.numeric' => 'Jawaban harus berupa angka',
            'role.required' => 'Harap pilih role',
            'role.in' => 'Role yang dipilih tidak valid'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!validate_captcha($request->captcha_answer)) {
            return redirect()->back()
                ->withErrors(['captcha_answer' => 'Jawaban verifikasi salah'])
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // Assign role sesuai pilihan
        $user->assignRole($request->role);

        auth()->login($user);

        return redirect()->route('user.index');
    }
}
