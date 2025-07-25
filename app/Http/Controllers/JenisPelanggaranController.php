<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPelanggaran;

class JenisPelanggaranController extends Controller
{
    public function index(Request $req)
    {
        $q = JenisPelanggaran::query();
        if ($s = $req->search) {
            $q->where('nama_jenis', 'like', "%$s%")
                ->orWhere('tingkat', 'like', "%$s%");
        }
        $jenisList = $q->orderBy('nama_jenis')->paginate(10);

        if ($req->ajax()) {
            return response()->json([
                'table'      => view('jenis.partials.table', compact('jenisList'))->render(),
                'pagination' => $jenisList->links('pagination::bootstrap-5')->render(),
            ]);
        }
        return view('jenis.index', compact('jenisList'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'nama_jenis' => 'required|max:100',
            'tingkat' => 'required|in:Ringan,Sedang,Berat',
            'poin' => 'required|numeric'
        ]);
        JenisPelanggaran::create($r->only('nama_jenis', 'tingkat', 'poin'));
        return back()->with('success', 'Jenis pelanggaran ditambahkan');
    }

    public function update(Request $r, $id)
    {
        $jp = JenisPelanggaran::findOrFail($id);
        $r->validate([
            'nama_jenis' => 'required|max:100',
            'tingkat' => 'required|in:Ringan,Sedang,Berat',
            'poin' => 'required|numeric'
        ]);
        $jp->update($r->only('nama_jenis', 'tingkat', 'poin'));
        return back()->with('success', 'Jenis pelanggaran diperbarui');
    }

    public function destroy($id)
    {
        JenisPelanggaran::findOrFail($id)->delete();
        return back()->with('success', 'Jenis pelanggaran dihapus');
    }
}
