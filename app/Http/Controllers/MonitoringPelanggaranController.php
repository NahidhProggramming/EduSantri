<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Santri;
use Illuminate\Http\Request;

class MonitoringPelanggaranController extends Controller
{
    /**  Halaman ringkas: kartu & rekap tabel **/
    public function index(Request $req)
    {
        // ── filter (opsional)
        $tahun = $req->get('tahun', now()->year);

        // ── rekap global
        $jumlah   = Pelanggaran::whereYear('tanggal', $tahun)->count();
        $ringan   = Pelanggaran::whereYear('tanggal', $tahun)->whereHas('jenisPelanggaran',
                      fn($q)=>$q->where('tingkat','Ringan'))->count();
        $sedang   = Pelanggaran::whereYear('tanggal', $tahun)->whereHas('jenisPelanggaran',
                      fn($q)=>$q->where('tingkat','Sedang'))->count();
        $berat    = Pelanggaran::whereYear('tanggal', $tahun)->whereHas('jenisPelanggaran',
                      fn($q)=>$q->where('tingkat','Berat'))->count();

        // ── grafik: jumlah per bulan
        $perBulan = Pelanggaran::whereYear('tanggal', $tahun)
                    ->selectRaw('MONTH(tanggal) bln, COUNT(*) jml')
                    ->groupBy('bln')->pluck('jml','bln')->all();
        $dataBulan = [];
        for($i=1;$i<=12;$i++){ $dataBulan[] = $perBulan[$i] ?? 0; }

        // ── tabel ringkas tiap santri (top 10)
        $topSantri = Pelanggaran::with('santri')
                    ->selectRaw('santri_nis, COUNT(*) jml')
                    ->whereYear('tanggal',$tahun)
                    ->groupBy('santri_nis')
                    ->orderByDesc('jml')
                    ->take(10)
                    ->get();

        return view('monitoring.pelanggaran-index', compact(
            'tahun','dataBulan','jumlah','ringan','sedang','berat','topSantri'
        ));
    }

    /**  Detail per santri **/
    public function show(Santri $santri)
    {
        $pelanggarans = $santri->pelanggarans()->latest('tanggal')->get();

        return view('monitoring.pelanggaran-show', compact('santri','pelanggarans'));
    }
}
