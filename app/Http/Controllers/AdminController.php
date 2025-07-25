<?php

namespace App\Http\Controllers;

use App\Models\AktivitasGuru;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Santri; // Tambahkan impor model Santri
use App\Models\Nilai;
use App\Models\Pelanggaran;
use App\Models\Jadwal;
use App\Models\TahunAkademik;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $tahunAkademikAktif = 1;

        $tahunAkademikList = TahunAkademik::all();
        $sekolahList = Sekolah::all();

        $rataNilai = Nilai::join('jadwal', 'nilai.jadwal_id', '=', 'jadwal.id_jadwal')
            ->join('detail', 'nilai.detail_id', '=', 'detail.id_detail')
            ->join('kelas', 'detail.kelas_id', '=', 'kelas.id_kelas')
            ->where('nilai.tahun_akademik_id', $tahunAkademikAktif)
            ->whereNotNull('nilai.nilai_pas')
            ->selectRaw('kelas.tingkat, AVG(nilai.nilai_pas) as rata_rata')
            ->groupBy('kelas.tingkat')
            ->get();

        $rataNilaiPerTingkat = [];
        foreach ($rataNilai as $item) {
            $rataNilaiPerTingkat[$item->tingkat] = $item->rata_rata;
        }

        $pelanggaranPerBulan = Pelanggaran::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as jumlah')
            ->whereYear('tanggal', date('Y'))
            ->groupBy('bulan')
            ->get()
            ->pluck('jumlah', 'bulan')
            ->toArray();

        return view('admin.dashboard', [
            'jumlahSantri' => Santri::count(),
            'jumlahGuru' => Guru::count(),
            'jumlahKelas' => Kelas::count(),
            'jumlahJadwal' => Jadwal::count(),
            'guruBaru' => Guru::latest()->take(5)->get(),
            'aktivitas' => AktivitasGuru::with('guru')->latest()->take(5)->get(),
            'rataNilaiPerTingkat' => $rataNilaiPerTingkat,
            'pelanggaranPerBulan' => $pelanggaranPerBulan,
            'tahunAkademikList' => $tahunAkademikList,
            'sekolahList' => $sekolahList,
        ]);
    }

    public function getFilterData(Request $request)
    {
        $tahun = $request->tahun;
        $sekolah = $request->sekolah;
        $mapel = $request->mapel;

        $query = Nilai::join('jadwal', 'nilai.jadwal_id', '=', 'jadwal.id_jadwal')
            ->join('detail', 'nilai.detail_id', '=', 'detail.id_detail')
            ->join('kelas', 'detail.kelas_id', '=', 'kelas.id_kelas')
            ->whereNotNull('nilai.nilai_pas');

        if ($tahun) {
            $query->where('nilai.tahun_akademik_id', $tahun);
        }

        if ($sekolah) {
            $query->where('jadwal.sekolah_id', $sekolah);
        }

        if ($mapel) {
            $query->where('jadwal.mata_pelajaran_id', $mapel);
        }

        $rataNilai = $query->selectRaw('kelas.tingkat, AVG(nilai.nilai_pas) as rata_rata')
            ->groupBy('kelas.tingkat')
            ->get();

        $data = [];
        foreach ([7, 8, 9] as $tingkat) {
            $found = $rataNilai->firstWhere('tingkat', $tingkat);
            $data[] = $found ? round($found->rata_rata, 2) : 0;
        }

        return response()->json($data);
    }

    public function getMapelData(Request $request)
    {
        $tahun = $request->tahun;
        $sekolah = $request->sekolah;

        $query = Jadwal::where('tahun_akademik_id', $tahun);

        if ($sekolah) {
            $query->where('sekolah_id', $sekolah);
        }

        $data = $query->with('mataPelajaran')
            ->select('mata_pelajaran_id')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->mataPelajaran->id_mapel,
                    'nama' => $item->mataPelajaran->nama_mapel
                ];
            });

        return response()->json($data);
    }

    public function getPelanggaranData($tahun)
    {
        $pelanggaran = Pelanggaran::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as jumlah')
            ->whereYear('tanggal', $tahun)
            ->groupBy('bulan')
            ->get()
            ->pluck('jumlah', 'bulan')
            ->toArray();

        $data = array_fill(0, 12, 0);
        foreach ($pelanggaran as $bulan => $jumlah) {
            $data[$bulan - 1] = $jumlah;
        }

        return response()->json($data);
    }
}
