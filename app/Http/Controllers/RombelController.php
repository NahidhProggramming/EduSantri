<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Santri;
use App\Models\Sekolah;
use App\Models\Kelas;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RombelController extends Controller
{
    public function index(Request $request)
    {
        $tahunList = TahunAkademik::where('semester_aktif', 'aktif')
            ->orderBy('tahun_akademik', 'desc')
            ->limit(1)
            ->get();

        $tahunA = TahunAkademik::where('semester_aktif', 'aktif')
            ->orderBy('tahun_akademik', 'desc')
            ->get();

        if ($request->has('tahun')) {
            $data = Detail::getPembagianKelas($request->tahun);
            return response()->json($data);
        }

        $tahunAktif = $tahunList->first(); // bisa null kalau tidak ada

        if ($tahunAktif) {
            $santriSudahAda = Detail::where('tahun_akademik_id', $tahunAktif->id_tahun_akademik)
                ->pluck('santri_id')
                ->toArray();

            $santri = Santri::whereNotIn('nis', $santriSudahAda)->get();
        } else {
            $santri = collect(); // kosongkan jika tidak ada tahun aktif
        }

        $sekolah = Sekolah::all();
        $kelas = Kelas::all();
        foreach ($kelas as $k) {
            \Log::info("Kelas: {$k->nama_kelas}, Tingkat: {$k->tingkat}");
        }


        return view('rombel.index', compact('tahunList', 'sekolah', 'santri', 'kelas', 'tahunA'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sekolah_id' => 'required',
            'kelas_id' => 'required',
            'santri_id' => 'required|array',
            'tahun_akademik_id' => 'required', // Tambahkan ini jika memilih tahun di form
        ]);

        $tahunDipilih = TahunAkademik::find($request->tahun_akademik_id);
        $tahunAktif = TahunAkademik::where('semester_aktif', 'aktif')->first();

        if (!$tahunDipilih) {
            return back()->with('error', 'Tahun akademik yang dipilih tidak ditemukan.');
        }

        // Cek apakah tahun yang dipilih adalah tahun yang sedang aktif
        if ($tahunAktif && $tahunDipilih->id_tahun_akademik == $tahunAktif->id_tahun_akademik) {
            return back()->with('error', 'Tahun akademik masih berjalan. Silakan pilih tahun lainnya.');
        }

        foreach ($request->santri_id as $santriId) {
            Detail::create([
                'tahun_akademik_id' => $tahunDipilih->id_tahun_akademik,
                'sekolah_id' => $request->sekolah_id,
                'kelas_id' => $request->kelas_id,
                'santri_id' => $santriId
            ]);
        }

        return redirect()->route('rombel.index')->with('success', 'Rombel berhasil ditambahkan!');
    }



    public function hapusSantri(Request $request)
    {
        $request->validate([
            'detail_id' => 'required|exists:detail,id_detail',
            'santri_id' => 'required|array|min:1',
        ]);

        // Ambil detail terkait
        $detail = Detail::findOrFail($request->detail_id);

        // Hapus berdasarkan kombinasi yang benar (kelas, sekolah, tahun + santri_id)
        Detail::where('kelas_id', $detail->kelas_id)
            ->where('sekolah_id', $detail->sekolah_id)
            ->where('tahun_akademik_id', $detail->tahun_akademik_id)
            ->whereIn('santri_id', $request->santri_id)
            ->delete();

        return redirect()->back()->with('success', 'Santri berhasil dihapus dari rombel.');
    }


    public function getSantriRombel($detailId)
    {
        $detail = DB::table('detail')->where('id_detail', $detailId)->first();

        if (!$detail) {
            return response()->json([]);
        }

        $santri = DB::table('detail')
            ->join('santri', 'detail.santri_id', '=', 'santri.nis')
            ->where('detail.kelas_id', $detail->kelas_id)
            ->where('detail.sekolah_id', $detail->sekolah_id)
            ->where('detail.tahun_akademik_id', $detail->tahun_akademik_id)
            ->select('santri.nis', 'santri.nama_santri')
            ->get();

        return response()->json($santri);
    }

    public function naikKelas(Request $request)
    {
        $request->validate([
            'detail_id' => 'required|exists:detail,id_detail',
            'kelas_tujuan_id' => 'required|exists:kelas,id_kelas',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id_tahun_akademik',
            'santri_id' => 'required|array|min:1',
        ]);

        foreach ($request->santri_id as $nis) {
            Detail::create([
                'kelas_id' => $request->kelas_tujuan_id,
                'tahun_akademik_id' => $request->tahun_akademik_id,
                'santri_id' => $nis,
                'sekolah_id' => optional(Detail::find($request->detail_id))->sekolah_id // opsional ambil dari detail asal
            ]);
        }

        return redirect()->back()->with('success', 'Santri berhasil dinaikkan ke kelas selanjutnya.');
    }
}
