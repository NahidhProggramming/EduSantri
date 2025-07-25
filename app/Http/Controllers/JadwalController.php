<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwalList = \App\Models\Jadwal::with(['guru', 'mataPelajaran', 'kelas', 'sekolah', 'tahunAkademik'])->get();
        $guruList = \App\Models\Guru::all();
        $mapelList = \App\Models\MataPelajaran::all();
        $tahunList = \App\Models\TahunAkademik::all();
        $sekolahList = \App\Models\Sekolah::all();
        $kelasList = \App\Models\Kelas::all();

        return view('jadwal.index', compact(
            'jadwalList',
            'guruList',
            'mapelList',
            'tahunList',
            'sekolahList',
            'kelasList'
        ));
    }

    public function getByTahun($id)
    {
        $jadwals = Jadwal::with(['guru', 'mataPelajaran', 'kelas', 'sekolah'])
            ->where('tahun_akademik_id', $id)
            ->get()
            ->groupBy(fn($item) => $item->sekolah->nama_sekolah ?? 'Tanpa Sekolah')
            ->map(function ($group, $nama_sekolah) {
                return [
                    'sekolah' => $nama_sekolah,
                    'jadwals' => $group->map(function ($item) {
                        return [
                            'id_jadwal' => $item->id_jadwal,
                            'guru' => $item->guru->nama_guru ?? '-',
                            'mapel' => $item->mataPelajaran->nama_mapel ?? '-',
                            'kelas' => $item->kelas->nama_kelas ?? '-',
                            'hari' => $item->hari,
                            'jam_mulai' => $item->jam_mulai,
                            'jam_selesai' => $item->jam_selesai,
                            'status' => $item->status,
                        ];
                    })->values()
                ];
            })
            ->values(); // reset key agar JSON array valid

        return response()->json($jadwals);
    }

    // public function create()
    // {
    //     return view('jadwal.create', [
    //         'guruList'    => Guru::all(),
    //         'mapelList'   => MataPelajaran::all(),
    //         'tahunList'   => TahunAkademik::all(),
    //         'sekolahList' => Sekolah::all(),
    //         'kelasList'   => Kelas::all(),
    //     ]);
    // }


    public function store(Request $request)
    {
        $jamOptions = [
            '09:20',
            '09:50',
            '10:20',
            '10:50',
            '12:00',
            '12:30',
            '13:00',
            '13:30'
        ];

        $request->validate([
            'guru_id'           => 'required|exists:guru,id_guru',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id_mapel',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id_tahun_akademik',
            'kelas_id'          => 'required|exists:kelas,id_kelas',
            'sekolah_id'        => 'required|exists:sekolah,id_sekolah',
            'hari'              => 'required|string',
            'jam_mulai'         => ['required', 'in:' . implode(',', $jamOptions)],
            'jam_selesai'       => ['required', 'in:' . implode(',', $jamOptions), 'after:jam_mulai'],
            'status'            => 'nullable|string',
        ]);


        // Cek jadwal bentrok
        $bentrok = Jadwal::where('kelas_id', $request->kelas_id)
            ->where('sekolah_id', $request->sekolah_id)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                    });
            })
            ->exists();

        if ($bentrok) {
            return back()->with('error', 'Jadwal bentrok dengan kelas dan sekolah yang sama di jam tersebut.');
        }

        // Simpan jadwal jika tidak bentrok
        Jadwal::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    // public function edit($id)
    // {
    //     $jadwal = Jadwal::findOrFail($id);

    //     return response()->json([
    //         'id' => $jadwal->id_jadwal,
    //         'guru_id' => $jadwal->guru_id,
    //         'mata_pelajaran_id' => $jadwal->mata_pelajaran_id,
    //         'tahun_akademik_id' => $jadwal->tahun_akademik_id,
    //         'sekolah_id' => $jadwal->sekolah_id,
    //         'kelas_id' => $jadwal->kelas_id,
    //         'hari' => $jadwal->hari,
    //         'jam_mulai' => $jadwal->jam_mulai,
    //         'jam_selesai' => $jadwal->jam_selesai,
    //         'status' => $jadwal->status,
    //     ]);
    // }

    public function update(Request $request, $id)
    {
        $jamOptions = [
            '09:20',
            '09:50',
            '10:20',
            '10:50',
            '12:00',
            '12:30',
            '13:00',
            '13:30'
        ];

        $request->validate([
            'guru_id'           => 'required|exists:guru,id_guru',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id_mapel',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id_tahun_akademik',
            'kelas_id'          => 'required|exists:kelas,id_kelas',
            'sekolah_id'        => 'required|exists:sekolah,id_sekolah',
            'hari'              => 'required|string',
            'jam_mulai'         => ['required', 'in:' . implode(',', $jamOptions)],
            'jam_selesai'       => ['required', 'in:' . implode(',', $jamOptions), 'after:jam_mulai'],
            'status'            => 'nullable|string',
        ]);

        // Cek bentrok kecuali jadwal dengan ID ini sendiri
        $bentrok = Jadwal::where('id_jadwal', '!=', $id)
            ->where('kelas_id', $request->kelas_id)
            ->where('sekolah_id', $request->sekolah_id)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                    });
            })
            ->exists();

        if ($bentrok) {
            return back()->with('error', 'Jadwal bentrok dengan jadwal lain di kelas dan sekolah yang sama.');
        }

        // Update data jika tidak bentrok
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
