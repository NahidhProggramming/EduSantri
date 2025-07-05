<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $query = MataPelajaran::query();

        if ($request->filled('search')) {
            $query->where('nama_mapel', 'like', '%' . $request->search . '%');
        }

        $mapels = $query->orderBy('nama_mapel')->paginate(10);


      if ($request->ajax()) {
        return response()->json([
            'table' => view('mapel.partials.table', compact('mapels'))->render(),
            'pagination' => $mapels->links('pagination::bootstrap-5')->render(),
        ]);
    }

        return view('mapel.index', compact('mapels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:100',
        ]);

        MataPelajaran::create([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:100',
        ]);

        $mapel = MataPelajaran::findOrFail($id);
        $mapel->update($request->all());

        return redirect()->route('mapel.index')->with('success', 'Mapel berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->delete();

        return redirect()->route('mapel.index')->with('success', 'Mapel berhasil dihapus.');
    }
}
