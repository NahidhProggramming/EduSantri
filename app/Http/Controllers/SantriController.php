<?php

namespace App\Http\Controllers;

use App\Imports\SantriImport;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SantriController extends Controller
{
    public function downloadTemplate()
    {

        $file = public_path('templates/template_santri.xlsx');

        if (!file_exists($file)) {
            return abort(404, 'Template tidak ditemukan');
        }

        return response()->download($file, 'template_santri.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }


    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xls,xlsx'
        ]);

        Excel::import(new SantriImport, $request->file('excel_file'));

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil diimport.');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Santri::query();

        if (!empty($search)) {
            $query->where('nama_santri', 'like', '%' . $search . '%');
        }

        $santris = $query->paginate(10);

        $startNumber = ($santris->currentPage() - 1) * $santris->perPage();

        if ($request->ajax()) {
            return response()->json([
                'table' => view('santri.partials.table', [
                    'santris' => $santris,
                    'startNumber' => $startNumber
                ])->render(),
                'pagination' => view('santri.partials.pagination', [
                    'santris' => $santris
                ])->render(),
            ]);
        }

        return view('santri.index', compact('santris', 'startNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'nullable|numeric',
            'nama_santri' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'foto' => 'nullable|image',
            'ayah' => 'required|string',
            'ibu' => 'required|string',
            'no_hp' => 'required|string',
        ]);

        $tahunMasuk = 2022;
        $prefix = (string) $tahunMasuk;

        $lastSantri = Santri::where('nis', 'like', $prefix . '%')
            ->orderBy('nis', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastSantri) {
            $lastNumber = (int) substr($lastSantri->nis, strlen($prefix));
        }

        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $nis = $prefix . $newNumber; // Contoh: 20221155

        $user = User::create([
            'name'     => $request->nama_santri,
            'username' => $nis,                 // username = NIS
            'password' => Hash::make($nis),     // password default = NIS
        ]);
        $user->assignRole('wali_santri');

        Santri::create([
            'nis' => $nis,
            'nisn' => $request->nisn,
            'nama_santri' => $request->nama_santri,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'foto' => $request->foto ? $request->file('foto')->store('foto-santri') : null,
            'alamat' => $request->alamat,
            'ayah' => $request->ayah,
            'ibu' => $request->ibu,
            'no_hp' => $request->no_hp,
            'wali_id'       => $user->id,
        ]);

        return redirect()->route('santri.index')->with('success', 'Santri dan User berhasil ditambahkan.');
    }

    public function show($nis)
    {
        $santri = Santri::findOrFail($nis);
        return view('santri.show', compact('santri'));
    }

    public function update(Request $request, $nis)
    {
        $validatedData = $request->validate([
            'nisn' => 'nullable|numeric',
            'nama_santri' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alamat' => 'required|string',
            'ayah' => 'required|string|max:255',
            'ibu' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
        ]);

        $santri = Santri::where('nis', $nis)->firstOrFail();

        $santri->nisn = $validatedData['nisn'];
        $santri->nama_santri = $validatedData['nama_santri'];
        $santri->tempat_lahir = $validatedData['tempat_lahir'];
        $santri->tanggal_lahir = $validatedData['tanggal_lahir'];
        $santri->jenis_kelamin = $validatedData['jenis_kelamin'];
        $santri->alamat = $validatedData['alamat'];
        $santri->ayah = $validatedData['ayah'];
        $santri->ibu = $validatedData['ibu'];
        $santri->no_hp = $validatedData['no_hp'];

        if ($request->hasFile('foto')) {
            if ($santri->foto) {
                Storage::delete('public/' . $santri->foto); // Remove the old photo
            }

            $path = $request->file('foto')->store('santri_photos', 'public');
            $santri->foto = $path;
        }

        $santri->update();

        return redirect()->route('santri.index', $santri->nis)->with('success', 'Data santri berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nis)
    {
        // Cari data santri berdasarkan NIS
        $santri = Santri::findOrFail($nis);

        // Hapus user yang username-nya = NIS
        User::where('username', $nis)->delete();

        // Hapus data santri
        $santri->delete();

        return redirect()->route('santri.index')->with('success', 'Data santri dan user berhasil dihapus.');
    }


    public function WaliSantri(Request $request)
    {
        $user = auth()->user();

        // Ambil santri berdasarkan ID user yang login sebagai wali (jika ada)
        $santri = \App\Models\Santri::where('wali_id', $user->id)
            ->with([
                'detail.sekolah',
                'detail.kelas',
                'wali'
            ])
            ->first();

        return view('santri.wali', compact(
            'santri'
        ));
    }


    public function pelanggaranSantri()
    {
        $santri = auth()->user()->santri()->firstOrFail();   // pk = nis

        /* --- NILAI per Mapel --- */
        $nilaiPerMapel = Nilai::query()
            ->join('detail', 'detail.id_detail', '=', 'nilai.detail_id')
            ->join('jadwal', 'jadwal.id_jadwal', '=', 'nilai.jadwal_id')
            ->join('mata_pelajaran AS mp', 'mp.id_mapel', '=', 'jadwal.mata_pelajaran_id')
            ->where('detail.santri_id', $santri->nis)         // ← gunakan nis
            ->selectRaw("
            mp.nama_mapel,
            ROUND(AVG(
                COALESCE(nilai_sumatif,0) +
                COALESCE(nilai_pas,0) +
                COALESCE(nilai_pat,0)
            ) / 3, 2) AS rata
        ")
            ->groupBy('mp.nama_mapel')
            ->orderBy('mp.nama_mapel')
            ->get();

        /* --- PELANGGARAN per bulan --- */
        $tahun = now()->year;
        $pelanggaranBulanan = $santri->pelanggarans()
            ->whereYear('tanggal', $tahun)
            ->selectRaw('MONTH(tanggal) bulan, COUNT(*) jml')
            ->groupBy('bulan')
            ->pluck('jml', 'bulan')
            ->all();

        $pelChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $pelChart[] = $pelanggaranBulanan[$i] ?? 0;
        }

        return view('santri.grafik', [
            'nilaiMapel' => $nilaiPerMapel,
            'pelChart'   => $pelChart,
            'tahunChart' => $tahun
        ]);
    }
}
