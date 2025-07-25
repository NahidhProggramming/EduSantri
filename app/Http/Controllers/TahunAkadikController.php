<?php

namespace App\Http\Controllers;

use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TahunAkadikController extends Controller
{
    public function getAktif()
    {
        return TahunAkademik::where('semester_aktif', 'Aktif')->get();
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = TahunAkademik::query();

        if ($search) {
            $query->where('tahun_akademik', 'like', '%' . $search . '%');
        }
        $akademiks = $query->paginate(10);
        if ($request->ajax()) {
            return response()->json([
                'table' => view('tahun_akademik.partials.table', compact('akademiks'))->render(),
                'pagination' => view('tahun_akademik.partials.pagination', compact('akademiks'))->render(),
            ]);
        }

        return view('tahun_akademik.index', compact('akademiks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:100',
            'semester' => 'required|in:Ganjil,Genap',
            'semester_aktif' => 'required|in:Aktif,Tidak',
        ]);

        TahunAkademik::create([
            'tahun_akademik' => $request->tahun_akademik,
            'semester' => $request->semester,
            'semester_aktif' => $request->semester_aktif,
        ]);

        return redirect()->route('akademik.index')->with('success', 'Tahun Akademik berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'semester' => 'required|in:Ganjil,Genap',
            'semester_aktif' => 'required|in:Aktif,Tidak',
        ]);

        $tahunAkademik = TahunAkademik::findOrFail($id);

        $tahunAkademik->update([
            'semester' => $request->semester,
            'semester_aktif' => $request->semester_aktif,
        ]);

        return redirect()->route('akademik.index')->with('success', 'Tahun Akademik berhasil diperbarui.');
    }




    public function destroy($id)
    {
        $akademik = TahunAkademik::findOrFail($id);
        $akademik->delete();

        return redirect()->route('akademik.index')->with('success', 'Tahun Akademik berhasil dihapus.');
    }

    public function editTgl()
    {
        $akademikAktif = TahunAkademik::where('semester_aktif', 'Aktif')->firstOrFail();
        return view('tahun_akademik.tanggal-cetak', compact('akademikAktif'));
    }

    public function updateTgl(Request $request)
    {
        $request->validate([
            'tempat' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $akademikAktif = TahunAkademik::where('semester_aktif', 'Aktif')->firstOrFail();

        $akademikAktif->update([
            'tempat' => $request->tempat,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('tanggal-cetak')->with('success', 'Tanggal Cetak berhasil diperbarui.');
    }
}
