<?php

namespace App\Http\Controllers;

use App\Exports\MonitoringAkademikExport;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Sekolah;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MonitoringAkademikController extends Controller
{
    public function index(Request $r)
    {
        /* --- parameter filter --- */
        $tahunId   = $r->input('tahun');
        $sekolahId = $r->input('sekolah');
        $kelasId   = $r->input('kelas');

        /* --- dropdown data --- */
        $daftarTahun   = TahunAkademik::orderByDesc('tahun_akademik')->get();
        $daftarSekolah = Sekolah::orderBy('nama_sekolah')->get();

        /* ------- dropdown Kelas menyesuaikan sekolah -------- */
        $daftarKelas = Kelas::query()
            ->with('details')
            ->when($sekolahId, function ($q) use ($sekolahId) {
                $q->whereHas('details', function ($sub) use ($sekolahId) {
                    $sub->where('sekolah_id', $sekolahId);
                });
            })
            ->orderBy('nama_kelas')
            ->get()
            ->map(function ($kelas) {
                $kelas->sekolah_id = optional($kelas->details->first())->sekolah_id;
                return $kelas;
            })
            ->filter(function ($kelas) {
                return $kelas->sekolah_id !== null;
            });

        /* --- inisialisasi variabel --- */
        $rataMapel = collect([]);
        $totalMapel = 0;
        $rataKeseluruhan = 0;

        /* --- data nilai (rata² per mapel) --- */
        if ($tahunId && $kelasId) { // HANYA JIKA TAHUN DAN KELAS DIPILIH
            try {
                $rataMapel = Nilai::join('jadwal', 'nilai.jadwal_id', '=', 'jadwal.id_jadwal')
                    ->join('mata_pelajaran', 'jadwal.mata_pelajaran_id', '=', 'mata_pelajaran.id_mapel')
                    ->where('nilai.tahun_akademik_id', $tahunId)
                    ->whereHas('detail', fn($q) => $q->where('kelas_id', $kelasId))
                    ->selectRaw('mata_pelajaran.nama_mapel, ROUND(AVG(COALESCE(nilai_sumatif,0) + COALESCE(nilai_pas,0) + COALESCE(nilai_pat,0)) / 3, 2) as rata')
                    ->groupBy('mata_pelajaran.nama_mapel')
                    ->get();

                $totalMapel        = $rataMapel->count();
                $rataKeseluruhan   = $rataMapel->avg('rata') ?? 0;
            } catch (\Exception $e) {
                \Log::error('Error fetching rataMapel: ' . $e->getMessage());
            }
        }

        /* --- kirim ke view --- */
        return view('monitoring.akademik-index', [
            'daftarTahun'   => $daftarTahun,
            'daftarSekolah' => $daftarSekolah,
            'daftarKelas'   => $daftarKelas,

            'tahunId'   => $tahunId,
            'sekolahId' => $sekolahId,
            'kelasId'   => $kelasId,

            'rataMapel'        => $rataMapel,
            'totalMapel'       => $totalMapel,
            'rataKeseluruhan'  => $rataKeseluruhan,
        ]);
    }

    public function fetch(Request $request)
    {
        $tahun = $request->tahun;
        $kelas = $request->kelas;

        $rataMapel = Nilai::join('jadwal', 'nilai.jadwal_id', '=', 'jadwal.id_jadwal')
            ->leftJoin('mata_pelajaran', 'jadwal.mata_pelajaran_id', '=', 'mata_pelajaran.id_mapel')
            ->when($tahun, fn($q) => $q->where('nilai.tahun_akademik_id', $tahun))
            ->when($kelas, function ($q) use ($kelas) {
                $q->whereHas('detail', fn($query) => $query->where('kelas_id', $kelas));
            })
            ->selectRaw('COALESCE(mata_pelajaran.nama_mapel, "Mata Pelajaran Tidak Diketahui") as nama_mapel,
                     ROUND(AVG(COALESCE(nilai_sumatif,0) + COALESCE(nilai_pas,0) + COALESCE(nilai_pat,0)) / 3, 2) as rata')
            ->groupBy('mata_pelajaran.nama_mapel')
            ->get()
            ->map(fn($n) => [
                'mataPelajaran' => $n->nama_mapel,
                'rata' => $n->rata
            ]);

        return response()->json($rataMapel);
    }
    public function exportExcel(Request $request)
    {
        // Validasi request
        $request->validate([
            'tahun' => 'required|exists:tahun_akademik,id_tahun_akademik',
            'sekolah' => 'nullable|exists:sekolah,id_sekolah',
            'kelas' => 'nullable|exists:kelas,id_kelas',
        ]);

        // Ambil parameter filter
        $tahunId = $request->input('tahun');
        $sekolahId = $request->input('sekolah');
        $kelasId = $request->input('kelas');

        // Generate nama file dengan timestamp
        $fileName = 'monitoring-akademik-' . now()->format('Ymd-His') . '.xlsx';

        // Download file Excel
        return Excel::download(new MonitoringAkademikExport($tahunId, $sekolahId, $kelasId), $fileName);
    }
}
