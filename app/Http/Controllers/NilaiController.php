<?php

namespace App\Http\Controllers;

use App\Models\AktivitasGuru;
use App\Models\Nilai;
use App\Models\Detail;
use App\Models\Sekolah;
use App\Models\TahunAkademik;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;

class NilaiController extends Controller
{
    public function index()
    {
        $tahunList = TahunAkademik::where('semester_aktif', 'aktif')->get();
        $sekolahList = Sekolah::all();
        return view('nilai.index', compact('tahunList', 'sekolahList'));
    }

    public function getKelas(Request $request)
    {
        $tahun = $request->tahun;
        $sekolah = $request->sekolah;

        // Ambil kelas dari jadwal berdasarkan tahun & sekolah
        $kelas = Jadwal::where('tahun_akademik_id', $tahun)
            ->where('sekolah_id', $sekolah)
            ->with('kelas')
            ->select('kelas_id') // ambil ID kelas
            ->distinct()
            ->get()
            ->map(function ($item) {
                return [
                    'id_kelas' => $item->kelas->id_kelas,
                    'nama_kelas' => $item->kelas->nama_kelas,
                ];
            });

        return response()->json($kelas);
    }

    public function getSantri(Request $request)
    {
        $kelasId = $request->query('kelas');
        $sekolahId = $request->query('sekolah');

        $santriList = Detail::with('santri')
            ->where('kelas_id', $kelasId)
            ->where('sekolah_id', $sekolahId)
            ->get()
            ->map(function ($detail) {
                return [
                    'detail_id' => $detail->id_detail,
                    'nama_santri' => $detail->santri->nama_santri ?? '-',
                ];
            });

        return response()->json($santriList);
    }

    public function getDetail($detailId)
    {
        $nilai = Nilai::where('detail_id', $detailId)
            ->with(['jadwal.mataPelajaran', 'tahunAkademik'])
            ->get()
            ->map(function ($item) {
                return [
                    'mapel' => $item->jadwal->mataPelajaran->nama_mapel ?? '-',
                    'sumatif' => $item->nilai_sumatif,
                    'pas' => $item->nilai_pas,
                    'pat' => $item->nilai_pat,
                    'tahun' => $item->tahunAkademik->tahun_akademik ?? '-',
                ];
            });

        return response()->json($nilai);
    }
    public function cetakRapor($id)
    {
        $detail = Detail::with([
            'santri',
            'kelas.waliKelas', // relasi ke guru sebagai wali kelas
            'nilai.jadwal.mataPelajaran'
        ])->findOrFail($id);

        $nilai = $detail->nilai;
        $kelas = $detail->kelas;
        $waliKelas = $kelas?->waliKelas?->nama_guru ?? '-';

        return view('nilai.cetak', compact('detail', 'nilai', 'kelas', 'waliKelas'));
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
        $guru = $user->guru;                         // relasi User → Guru

        if (!$guru) {
            abort(404, 'Akun ini tidak ter‑hubung dengan data Guru.');
        }

        $totalJadwalGuru = $guru->jadwal()->count();

        $totalKelasGuru  = $guru->jadwal()
            ->distinct('kelas_id')
            ->count('kelas_id');

        $kelasIDs        = $guru->jadwal()->pluck('kelas_id');
        $totalSiswaGuru  = Detail::whereIn('kelas_id', $kelasIDs)
            ->distinct('santri_id')
            ->count('santri_id');

        $jadwals   = $guru->jadwal()
            ->with(['mataPelajaran', 'kelas'])
            ->get();

        $tahunAktif   = TahunAkademik::where('semester_aktif', 'aktif')->first();
        $jumlahSantri = Santri::count();

        $aktivitas    = AktivitasGuru::where('guru_id', $guru->id_guru)
            ->latest()
            ->take(5)
            ->get();

        return view('nilai.guru-index', [
            'guru'             => $guru,
            'jadwals'          => $jadwals,
            'tahunAktif'       => $tahunAktif,
            'jumlahSantri'     => $jumlahSantri,
            'aktivitas'        => $aktivitas,
            'totalJadwalGuru'  => $totalJadwalGuru,
            'totalKelasGuru'   => $totalKelasGuru,
            'totalSiswaGuru'   => $totalSiswaGuru,
        ]);
    }

    public function cetak($detailId)
    {
        $detail = Detail::with([
            'santri',
            'kelas',
            'sekolah',
            'tahunAkademik'
        ])->findOrFail($detailId);

        $user = auth()->user();

        /* --- RULE AKSES -------------------------------------------------
       • admin & kesiswaan      => selalu boleh
       • wali_santri            => hanya jika wali_id = user.id
       • guru                   => (contoh) tidak diizinkan
    -----------------------------------------------------------------*/
        if ($user->hasRole('admin|kesiswaan')) {
            // pass
        } elseif ($user->hasRole('wali_santri')) {
            if ($detail->santri->wali_id !== $user->id) {
                abort(403, 'Anda tidak berhak mengunduh rapor ini.');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }

        /* -------- generate PDF -------- */
        $nilaiList = $detail->nilai()
            ->with('jadwal.mataPelajaran')
            ->get()
            ->map(fn($n) => (object)[
                'mapel'         => $n->jadwal->mataPelajaran->nama_mapel ?? '-',
                'nilai_sumatif' => $n->nilai_sumatif,
                'nilai_pas'     => $n->nilai_pas,
                'nilai_pat'     => $n->nilai_pat,
            ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('nilai.cetak', [
            'santri'    => $detail->santri,
            'kelas'     => $detail->kelas,
            'sekolah'   => $detail->sekolah,
            'tahun'     => $detail->tahunAkademik,
            'nilaiList' => $nilaiList,
        ]);

        return $pdf->stream('rapor-' . $detail->santri->nama_santri . '.pdf');
    }

    public function cetakSemua(Request $request)
    {
        $kelasId = $request->kelas;
        $sekolahId = $request->sekolah;

        $details = Detail::with(['santri', 'kelas', 'sekolah', 'tahunAkademik'])
            ->where('kelas_id', $kelasId)
            ->where('sekolah_id', $sekolahId)
            ->get();

        // Buat PDF banyak halaman per santri atau gabungkan
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('nilai.cetak-semua', ['details' => $details]);

        return $pdf->stream('rapor-semua.pdf');
    }
    public function nilaiWali()
    {
        $user = Auth::user();

        // relasi hasMany santri() sudah dibuat
        $santriList = $user->santri()
            ->with([
                'nilai.tahunAkademik',
                'nilai.jadwal.mataPelajaran',
                'kelasAktif.sekolah',
                'sekolahAktif'
            ])
            ->get();

        return view('nilai.santri-nilai', compact('santriList'));
    }
}
