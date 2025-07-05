<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->role) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(10);

        return view('user.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $role = $request->input('role');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($role);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $role = $request->input('role');

        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,'.$user->id,
            'username' => 'required|string|unique:users,username,'.$user->id,
            'role' => 'required|string|exists:roles,name'
        ];

        if ($request->filled('password')) {
            $validationRules['password'] = 'string|min:8';
        }

        $validated = $request->validate($validationRules);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        $user->syncRoles([$role]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
