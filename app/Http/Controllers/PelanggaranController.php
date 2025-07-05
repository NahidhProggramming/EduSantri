<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Santri;
use App\Models\JenisPelanggaran;
use App\Models\TingkatPelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelanggaranController extends Controller
{
    public function index()
    {
        $pelanggarans = Pelanggaran::with(['santri', 'jenisPelanggaran', 'tingkatPelanggaran'])->get();
        $santris = Santri::all();
        $jenisList = JenisPelanggaran::all();
        $tingkatList = TingkatPelanggaran::all();

        return view('pelanggaran.index', compact('pelanggarans', 'santris', 'jenisList', 'tingkatList'));
    }


    public function create()
    {
        $santris = Santri::all();
        $jenis = JenisPelanggaran::all();
        $tingkat = TingkatPelanggaran::all();
        return view('pelanggaran.create', compact('santris', 'jenis', 'tingkat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|array|min:1',
            'santri_id.*' => 'exists:santri,nis',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggaran,id_jenis',
            'tingkat_pelanggaran_id' => 'required|exists:tingkat_pelanggaran,id_tingkat',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;
        $tingkat = TingkatPelanggaran::find($request->tingkat_pelanggaran_id);

        // Simpan file hanya jika tingkat "Berat"
        if ($tingkat && $tingkat->nama_tingkat === 'Berat' && $request->hasFile('file_surat')) {
            $filePath = $request->file('file_surat')->store('surat_pelanggaran', 'public');
        }

        // Loop untuk menyimpan pelanggaran ke semua santri
        foreach ($request->santri_id as $nis) {
            Pelanggaran::create([
                'santri_nis' => $nis,
                'jenis_pelanggaran_id' => $request->jenis_pelanggaran_id,
                'tingkat_pelanggaran_id' => $request->tingkat_pelanggaran_id,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'file_surat' => $filePath,
            ]);
        }

        return redirect()->route('pelanggaran.index')->with('success', 'Data pelanggaran berhasil ditambahkan untuk semua santri yang dipilih.');
    }

    public function edit($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $santris = Santri::all();
        $jenis = JenisPelanggaran::all();
        $tingkat = TingkatPelanggaran::all();

        return view('pelanggaran.edit', compact('pelanggaran', 'santris', 'jenis', 'tingkat'));
    }

    public function update(Request $request, $id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);

        $request->validate([
            'santri_nis' => 'required|exists:santri,nis',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggaran,id_jenis',
            'tingkat_pelanggaran_id' => 'required|exists:tingkat_pelanggaran,id_tingkat',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = $pelanggaran->file_surat;
        $tingkat = TingkatPelanggaran::find($request->tingkat_pelanggaran_id);

        if ($tingkat->nama_tingkat === 'Berat' && $request->hasFile('file_surat')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_surat')->store('surat_pelanggaran', 'public');
        }

        $pelanggaran->update([
            'santri_nis' => $request->santri_nis,
            'jenis_pelanggaran_id' => $request->jenis_pelanggaran_id,
            'tingkat_pelanggaran_id' => $request->tingkat_pelanggaran_id,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'file_surat' => $filePath,
        ]);

        return redirect()->route('pelanggaran.index')->with('success', 'Data pelanggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);

        if ($pelanggaran->file_surat) {
            Storage::disk('public')->delete($pelanggaran->file_surat);
        }

        $pelanggaran->delete();
        return redirect()->route('pelanggaran.index')->with('success', 'Data pelanggaran berhasil dihapus.');
    }

    public function verifikasiForm($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        return view('pelanggaran.verifikasi', compact('pelanggaran'));
    }

    public function verifikasiSubmit(Request $request, $id)
    {
        $request->validate([
            'verifikasi_surat' => 'required|in:Terverifikasi,Ditolak',
        ]);

        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->verifikasi_surat = $request->verifikasi_surat;
        $pelanggaran->save();

        return redirect()->route('pelanggaran.index')->with('success', 'Surat berhasil diverifikasi.');
    }
}
