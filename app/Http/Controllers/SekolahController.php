<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Sekolah::query();

        if ($search) {
            $query->where('nama_sekolah', 'like', '%' . $search . '%');
        }

        $sekolahs = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('sekolah.partials.table', compact('sekolahs'))->render(),
                'pagination' => view('sekolah.partials.pagination', compact('sekolahs'))->render(),
            ]);
        }

        return view('sekolah.index', compact('sekolahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:100',
        ]);

        Sekolah::create([
            'nama_sekolah' => $request->nama_sekolah,
        ]);

        return redirect()->route('sekolah.index')->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:100',
        ]);

        $sekolah = Sekolah::findOrFail($id);
        $sekolah->update($request->all());

        return redirect()->route('sekolah.index')->with('success', 'Sekolah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $sekolah->delete();

        return redirect()->route('sekolah.index')->with('success', 'Sekolah berhasil dihapus.');
    }
}
