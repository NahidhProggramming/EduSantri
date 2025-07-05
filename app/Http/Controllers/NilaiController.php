<?php

namespace App\Http\Controllers;

use App\Models\AktivitasGuru;
use App\Models\Nilai;
use App\Models\Detail;
use App\Models\Sekolah;
use App\Models\TahunAkademik;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index()
    {
        $tahunList = TahunAkademik::all();
        $sekolahList = Sekolah::all();
        return view('nilai.index', compact('tahunList', 'sekolahList'));
    }

    public function getKelasBySekolahTahun(Request $request)
    {
        $kelasUnik = Detail::with('kelas')
            ->where('tahun_akademik_id', $request->tahun)
            ->where('sekolah_id', $request->sekolah)
            ->select('kelas_id')
            ->distinct()
            ->get()
            ->map(function ($d) {
                return [
                    'id_kelas' => $d->kelas->id_kelas ?? null,
                    'nama_kelas' => $d->kelas->nama_kelas ?? 'Tidak diketahui',
                ];
            })
            ->filter(fn($d) => $d['id_kelas'] !== null);

        return response()->json($kelasUnik->values());
    }

    public function getSantriByDetail(Request $request)
    {
        $santriList = Detail::with('santri')
            ->where('tahun_akademik_id', $request->tahun)
            ->where('sekolah_id', $request->sekolah)
            ->where('kelas_id', $request->kelas)
            ->get()
            ->map(function ($d) {
                return [
                    'detail_id' => $d->id_detail,
                    'nama_santri' => optional($d->santri)->nama_santri ?? 'Tidak Diketahui',
                ];
            });

        return response()->json($santriList);
    }

    public function getNilaiDetail($detail_id)
    {
        $nilai = Nilai::with(['jadwal.mataPelajaran', 'jadwal.guru'])
            ->where('detail_id', $detail_id)
            ->get()
            ->map(function ($n) {
                return [
                    'nama_mapel' => $n->jadwal->mataPelajaran->nama_mapel ?? '-',
                    'nama_guru' => $n->jadwal->guru->nama_guru ?? '-',
                    'nilai_sumatif' => $n->nilai_sumatif,
                    'nilai_pas' => $n->nilai_pas,
                    'nilai_pat' => $n->nilai_pat
                ];
            });

        return response()->json($nilai);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'detail_id' => 'required|exists:detail,id_detail',
    //         'jadwal_id' => 'required|exists:jadwal,id_jadwal',
    //         'nilai_sumatif' => 'nullable|numeric|min:0|max:100',
    //         'nilai_pas' => 'nullable|numeric|min:0|max:100',
    //         'nilai_pat' => 'nullable|numeric|min:0|max:100',
    //     ]);

    //     Nilai::updateOrCreate(
    //         [
    //             'detail_id' => $request->detail_id,
    //             'jadwal_id' => $request->jadwal_id,
    //         ],
    //         [
    //             'nilai_sumatif' => $request->nilai_sumatif,
    //             'nilai_pas' => $request->nilai_pas,
    //             'nilai_pat' => $request->nilai_pat,
    //         ]
    //     );

    //     return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
    // }

    public function input($jadwal_id)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Akun ini belum terhubung dengan data guru.');
        }

        $jadwal = Jadwal::with(['mataPelajaran', 'kelas', 'tahunAkademik'])
            ->where('id_jadwal', $jadwal_id)
            ->where('guru_id', $guru->id_guru)
            ->firstOrFail();

        // Ambil semua santri sesuai kelas, tahun, dan sekolah dari detail
        $santriDetails = Detail::with(['santri', 'nilai' => function ($q) use ($jadwal_id) {
            $q->where('jadwal_id', $jadwal_id);
        }])
            ->where('kelas_id', $jadwal->kelas_id)
            ->where('tahun_akademik_id', $jadwal->tahun_akademik_id)
            ->where('sekolah_id', $jadwal->sekolah_id)
            ->get();

        return view('nilai.input', compact('jadwal', 'santriDetails'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id_jadwal',
            'nilai.*.detail_id' => 'required|exists:detail,id_detail',
            'nilai.*.nilai_sumatif' => 'nullable|numeric|min:0|max:100',
            'nilai.*.nilai_pas' => 'nullable|numeric|min:0|max:100',
            'nilai.*.nilai_pat' => 'nullable|numeric|min:0|max:100',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $tahunAkademikId = $jadwal->tahun_akademik_id; // Ambil dari relasi jadwal

        foreach ($request->nilai as $n) {
            Nilai::updateOrCreate(
                [
                    'detail_id' => $n['detail_id'],
                    'jadwal_id' => $request->jadwal_id,
                ],
                [
                    'nilai_sumatif' => $n['nilai_sumatif'],
                    'nilai_pas' => $n['nilai_pas'],
                    'nilai_pat' => $n['nilai_pat'],
                    'tahun_akademik_id' => $tahunAkademikId,
                ]
            );
        }
        AktivitasGuru::create([
            'guru_id' => auth()->user()->guru->id_guru,
            'deskripsi' => 'Menginput nilai untuk kelas ' . $jadwal->kelas->nama_kelas,
        ]);

        return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
    }


    public function daftarJadwalGuru()
    {
        $user = auth()->user();
        $guru = $user->guru; // pastikan relasi user->guru ada

        $jadwals = \App\Models\Jadwal::with(['mataPelajaran', 'kelas'])
            ->where('guru_id', $guru->id_guru)
            ->get();

        $tahunAktif = \App\Models\TahunAkademik::where('semester_aktif', 'aktif')->first();
        $jumlahSantri = \App\Models\Santri::count();
        $aktivitas = AktivitasGuru::where('guru_id', $guru->id_guru)->latest()->take(5)->get();


        return view('nilai.guru-index', compact('guru', 'jadwals', 'tahunAktif', 'jumlahSantri', 'aktivitas'));
    }
}
