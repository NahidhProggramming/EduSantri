<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Kelas::with('sekolah');

        if ($search) {
            $query->where('nama_kelas', 'like', "%{$search}%")
                ->orWhere('tingkat', 'like', "%{$search}%");
        }

        $kelass = $query->orderBy('nama_kelas')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('kelas.partials.table', compact('kelass'))->render(),
                'pagination' => $kelass->links('pagination::bootstrap-5')->render(),
            ]);
        }
        $guruList = \App\Models\Guru::all();

        return view('kelas.index', compact('kelass', 'guruList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'tingkat' => 'required|string|max:10',
            'wali_kelas_id' => 'nullable|exists:guru,id_guru',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
            'wali_kelas_id' => $request->wali_kelas_id,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'tingkat' => 'required|string|max:10',
            'wali_kelas_id' => 'nullable|exists:guru,id_guru',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);
            $kelas->delete();

            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->route('kelas.index')->with('error', 'Kelas tidak dapat dihapus karena masih digunakan dalam data lain.');
            }

            return redirect()->route('kelas.index')->with('error', 'Terjadi kesalahan saat menghapus kelas.');
        }
    }
}
