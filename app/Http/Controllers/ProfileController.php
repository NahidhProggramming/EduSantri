<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updatePassword(Request $r)
    {
        $r->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $r->user();
        $user->update(['password' => Hash::make($r->password)]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
