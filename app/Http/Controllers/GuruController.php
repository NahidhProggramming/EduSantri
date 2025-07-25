<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Guru::query();

        if ($search) {
            $query->where('nama_guru', 'like', '%' . $search . '%');
        }

        $gurus = $query->orderBy('nama_guru')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('guru.partials.table', compact('gurus'))->render(),
                // 'pagination' => view('guru.partials.pagination', compact('gurus'))->render(),
            ]);
        }

        return view('guru.index', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:guru,nip',
            'nama_guru' => 'required',
            'jenkel' => 'required|string|in:L,P',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required',
            'no_hp' => 'required',
        ]);

        $user = User::create([
            'name' => $request->nama_guru,
            'username' => $request->nip,
            'password' => Hash::make($request->nip),
        ]);

        $user->assignRole('guru');

        Guru::create([
            'nip' => $request->nip,
            'nama_guru' => $request->nama_guru,
            'jenkel' => $request->jenkel,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'user_id' => $user->id,
        ]);

        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_guru' => 'required',
            'nip' => 'required',
            'jenkel' => 'required|string|in:L,P',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required',
            'no_hp' => 'required',
        ]);


        $guru = Guru::findOrFail($id);
        $guru->update($request->all());

        return redirect()->route('guru.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);

        // Cek apakah guru masih digunakan di jadwal
        if ($guru->jadwal()->count() > 0) {
            return redirect()->back()->with('error', 'Guru masih digunakan dalam jadwal.');
        }

        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus.');
    }
    //     public function destroy($id)
    // {
    //     $guru = Guru::findOrFail($id);

    //     // Hapus semua jadwal terkait dulu
    //     $guru->jadwal()->delete();

    //     $guru->delete();

    //     return redirect()->route('guru.index')->with('success', 'Guru dan jadwal terkait berhasil dihapus.');
    // }

}
