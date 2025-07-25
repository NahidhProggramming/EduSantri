<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Sekolah;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $tahunList = TahunAkademik::orderBy('id_tahun_akademik', 'desc')->get();
        $sekolahList = Sekolah::all();
        $kelasList = Kelas::all();

        // Ambil filter dari form (opsional)
        $tahunId = $request->tahun_akademik_id;
        $sekolahId = $request->sekolah_id;
        $kelasId = $request->kelas_id;

        $query = Detail::with([
            'santri',
            'kelas',
            'nilai.jadwal.mataPelajaran'
        ]);

        if ($tahunId) {
            $query->where('tahun_akademik_id', $tahunId);
        }

        if ($sekolahId) {
            $query->where('sekolah_id', $sekolahId);
        }

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $santriList = $query->get();

        return view('monitoring.index', compact(
            'tahunList',
            'sekolahList',
            'kelasList',
            'santriList',
            'tahunId',
            'sekolahId',
            'kelasId'
        ));
    }
    public function show(Santri $santri)
    {
        $nilai = Nilai::with('mapel')
            ->where('santri_id', $santri->id)
            ->get();

        $labels = [];
        $rata2 = [];

        foreach ($nilai->groupBy('mapel.nama_mapel') as $mapel => $items) {
            $total = 0;
            $count = 0;

            foreach ($items as $item) {
                $sumatif = (float) $item->nilai_sumatif;
                $pas     = (float) $item->nilai_pas;
                $pat     = (float) $item->nilai_pat;

                if ($sumatif && $pas && $pat) {
                    $rata = ($sumatif + $pas + $pat) / 3;
                    $total += $rata;
                    $count++;
                }
            }

            $labels[] = $mapel ?? '-';
            $rata2[] = $count > 0 ? round($total / $count, 2) : 0;
        }

        return response()->json([
            'labels' => $labels,
            'rata2' => $rata2,
        ]);
    }
}
