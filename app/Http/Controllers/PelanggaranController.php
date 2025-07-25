<?php

namespace App\Http\Controllers;

use App\Exports\PelanggaranExport;
use App\Models\Pelanggaran;
use App\Models\Santri;
use App\Models\JenisPelanggaran;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use App\Services\FonteService;
use Illuminate\Support\Facades\Log;

class PelanggaranController extends Controller
{
    public function indexKesiswaan()
    {
        $jumlahSantri           = Santri::count();
        $jumlahPelanggaran      = Pelanggaran::count();
        $jumlahPelanggaranBerat = Pelanggaran::whereHas(
            'jenisPelanggaran',
            fn($q) => $q->where('tingkat', 'Berat')
        )->count();

        $tahun = request('tahun', now()->year);

        $perBulan = Pelanggaran::whereYear('tanggal', $tahun)
            ->selectRaw('MONTH(tanggal) bln, COUNT(*) jml')
            ->groupBy('bln')->pluck('jml', 'bln')->all();
        $dataBulan = collect(range(1, 12))->map(fn($i) => $perBulan[$i] ?? 0);

        $tingkat = ['Ringan', 'Sedang', 'Berat'];
        $perTingkat = Pelanggaran::whereYear('tanggal', $tahun)
            ->with('jenisPelanggaran')->get()
            ->groupBy(fn($p) => $p->jenisPelanggaran->tingkat)
            ->map->count();
        $dataTingkat = collect($tingkat)->map(fn($t) => $perTingkat[$t] ?? 0);

        $pelanggaranTerbaru = Pelanggaran::with(['santri', 'jenisPelanggaran'])
            ->latest('tanggal')->take(10)->get();

        $daftarTahun = collect(range(now()->year, now()->year - 4));

        return view('pelanggaran.dashboard', compact(
            'jumlahSantri',
            'jumlahPelanggaran',
            'jumlahPelanggaranBerat',
            'pelanggaranTerbaru',
            'dataBulan',
            'dataTingkat',
            'tahun',
            'daftarTahun'
        ));
    }

    public function chartData($tahun)
    {
        $perBulan = Pelanggaran::whereYear('tanggal', $tahun)
            ->selectRaw('MONTH(tanggal) bln, COUNT(*) jml')
            ->groupBy('bln')->pluck('jml', 'bln')->all();
        $dataBulan = collect(range(1, 12))->map(fn($i) => $perBulan[$i] ?? 0);

        $tingkat = ['Ringan', 'Sedang', 'Berat'];
        $perTingkat = Pelanggaran::whereYear('tanggal', $tahun)
            ->with('jenisPelanggaran')->get()
            ->groupBy(fn($p) => $p->jenisPelanggaran->tingkat)
            ->map->count();
        $dataTingkat = collect($tingkat)->map(fn($t) => $perTingkat[$t] ?? 0);

        return response()->json(['bulan' => $dataBulan, 'tingkat' => $dataTingkat]);
    }


    public function index()
    {
        $user = auth()->user();

        $guru = \App\Models\Guru::where('user_id', $user->id)->first();
        $kelas = $guru ? \App\Models\Kelas::where('wali_kelas_id', $guru->id_guru)->first() : null;

        $santris = collect();
        $jenisList = \App\Models\JenisPelanggaran::all();


        if ($kelas && $user->hasRole('guru')) {
            $santriIds = \App\Models\Detail::where('kelas_id', $kelas->id_kelas)->pluck('santri_id');
            $pelanggarans = \App\Models\Pelanggaran::whereIn('santri_nis', $santriIds)
                ->whereHas('jenisPelanggaran', fn($q) => $q->where('tingkat', 'Berat'))
                ->with(['santri.detail.kelas', 'jenisPelanggaran'])
                ->get();
        } else {
            $pelanggarans = \App\Models\Pelanggaran::with(['santri.detail.kelas', 'jenisPelanggaran'])->get();
            $santris = \App\Models\Santri::all();
        }

        return view('pelanggaran.index', compact('pelanggarans', 'santris', 'jenisList'));
    }

    public function store(Request $request, FonnteService $wa)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,nis',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggaran,id_jenis',
            'deskripsi' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $santri = Santri::select('nis', 'nama_santri', 'no_hp')->where('nis', $request->santri_id)->firstOrFail();
        $jenis = JenisPelanggaran::select('id_jenis', 'tingkat', 'nama_jenis', 'poin')->findOrFail($request->jenis_pelanggaran_id);

        $filePath = null;
        $verifikasi = null;

        if (strtolower($jenis->tingkat) === 'berat' && $request->hasFile('file_surat')) {
            $fileName = Str::uuid() . '.' . $request->file('file_surat')->getClientOriginalExtension();
            $request->file('file_surat')->move(public_path('surat_pelanggaran'), $fileName);
            $filePath = $fileName;
            $verifikasi = 'Belum Diverifikasi';
        }

        DB::beginTransaction();
        try {
            Pelanggaran::create([
                'santri_nis' => $santri->nis,
                'jenis_pelanggaran_id' => $request->jenis_pelanggaran_id,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'file_surat' => $filePath,
                'verifikasi_surat' => $verifikasi,
            ]);

            $totalPoin = DB::table('pelanggaran')
                ->join('jenis_pelanggaran', 'pelanggaran.jenis_pelanggaran_id', '=', 'jenis_pelanggaran.id_jenis')
                ->where('santri_nis', $santri->nis)
                ->sum('poin');

            $peringatan = '';
            if ($totalPoin >= 100) {
                $peringatan = "\n⚠️ *PERHATIAN:* Poin pelanggaran santri telah mencapai 100. Mohon kepada orang tua yang bersangkutan untuk datang ke sekolah.";
            } elseif ($totalPoin >= 75) {
                $peringatan = "\n⚠️ *PERINGATAN:* Santri telah melewati 75 poin. Mohon untuk orang tua meningkatkan perhatian.";
            } elseif ($totalPoin >= 50) {
                $peringatan = "\n⚠️ *Teguran:* Santri telah melewati 50 poin.";
            }

            $phone = preg_replace('/[^0-9]/', '', $santri->no_hp);
            if (str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            }

            $tingkat = strtolower($jenis->tingkat);
            $penutup = match ($tingkat) {
                'ringan' => "Mohon menjadi perhatian dan pembelajaran bagi santri.",
                'sedang' => "Perlu adanya evaluasi dan bimbingan terhadap santri.",
                'berat' => "Mohon koordinasi segera dengan pihak pembina dan orang tua.",
                default => "Mohon perhatian dan koordinasi dengan pembina.",
            };

            $msg = "*Assalaamu'alaikum wr. wb.*\n\n"
                . "Kami dari pihak pondok pesantren ingin menyampaikan informasi berikut:\n\n"
                . "*Nama:* {$santri->nama_santri}\n"
                . "*Pelanggaran:* {$jenis->nama_jenis}\n"
                . "*Tingkat:* {$jenis->tingkat}\n"
                . "*Tanggal:* " . date('d M Y', strtotime($request->tanggal)) . "\n"
                . "*Poin Pelanggaran Ini:* {$jenis->poin}\n"
                . "*Total Akumulasi Poin:* {$totalPoin}\n"
                . ($request->deskripsi ? "*Keterangan:* {$request->deskripsi}\n" : '')
                . "\n{$penutup}{$peringatan}";

            // Kirim WA untuk SEMUA jenis pelanggaran
            $res = $wa->sendText($phone, $msg);
            if (!$res['status']) {
                Log::error("Gagal kirim WA ke: {$santri->no_hp}", ['response' => $res]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Pelanggaran disimpan' . ($res['status'] ? ' & WA berhasil dikirim.' : ', tapi WA gagal dikirim.'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal simpan pelanggaran: ' . $e->getMessage());
            return back()->withErrors('Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggaran,id_jenis',
            'tanggal'              => 'required|date',
            'deskripsi'            => 'nullable|string|max:255',
            'file_surat'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $pel = Pelanggaran::findOrFail($id);
        $oldFile = $pel->file_surat;

        // Cek apakah jenis baru adalah BERAT
        $jenis = JenisPelanggaran::find($request->jenis_pelanggaran_id);
        $isBerat = $jenis && strtolower($jenis->tingkat) === 'berat';

        // Handle upload file baru
        $newFileName = null;
        if ($request->hasFile('file_surat')) {
            // Hapus file lama jika ada
            if ($oldFile) {
                $oldFilePath = public_path('surat_pelanggaran/' . $oldFile);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Generate nama file baru dengan UUID
            $fileName = Str::uuid() . '.' . $request->file('file_surat')->getClientOriginalExtension();

            // Simpan file baru
            $request->file('file_surat')->move(public_path('surat_pelanggaran'), $fileName);
            $newFileName = $fileName;
        } else {
            // Jika tidak ada file baru diupload, pertahankan file lama hanya jika jenis berat
            $newFileName = $isBerat ? $oldFile : null;

            // Jika jenis bukan berat tapi ada file lama, hapus file lama
            if (!$isBerat && $oldFile) {
                Storage::delete('public/surat_pelanggaran/' . $oldFile);
            }
        }

        $pel->update([
            'jenis_pelanggaran_id' => $request->jenis_pelanggaran_id,
            'deskripsi'            => $request->deskripsi,
            'tanggal'              => $request->tanggal,
            'file_surat'           => $newFileName,
            // Reset verifikasi jika file diubah
            'verifikasi_surat'     => $newFileName && $newFileName !== $oldFile ? 'Belum Diverifikasi' : $pel->verifikasi_surat,
        ]);

        return back()->with('success', 'Data pelanggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);

        if ($pelanggaran->file_surat) {
            // PERUBAHAN: Hapus dari public
            $filePath = public_path('surat_pelanggaran/' . $pelanggaran->file_surat);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $pelanggaran->delete();
        return redirect()->route('pelanggaran.index')->with('success', 'Data pelanggaran berhasil dihapus.');
    }

    public function verifikasiSubmit(Request $request, $id, FonnteService $wa)
    {
        $request->validate([
            'verifikasi_surat' => 'required|in:Terverifikasi,Ditolak',
        ]);

        // Ambil data pelanggaran dengan relasi yang diperlukan
        $pelanggaran = Pelanggaran::with([
            'santri',
            'jenisPelanggaran' => function ($query) {
                $query->select('id_jenis', 'nama_jenis');
            }
        ])->findOrFail($id);

        $user = auth()->user();
        $guru = $user->guru;

        // Verifikasi wali kelas
        $waliKelasId = $pelanggaran->santri->detail->first()?->kelas?->wali_kelas_id;

        if (!$guru || $guru->id_guru !== $waliKelasId) {
            abort(403, 'Unauthorized action. Hanya wali kelas yang dapat memverifikasi.');
        }

        // Simpan status verifikasi
        $pelanggaran->verifikasi_surat = $request->verifikasi_surat;
        $pelanggaran->save();

        // Hanya proses jika status Terverifikasi dan ada file surat
        if ($request->verifikasi_surat === 'Terverifikasi' && $pelanggaran->file_surat) {
            $santri = $pelanggaran->santri;

            // Format nomor HP
            $phone = preg_replace('/[^0-9]/', '', $santri->no_hp);
            if (str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            } elseif (!str_starts_with($phone, '62')) {
                $phone = '62' . ltrim($phone, '0');
            }

            // Validasi format nomor WA
            if (!preg_match('/^62[0-9]{9,15}$/', $phone)) {
                Log::error("Nomor WhatsApp tidak valid", [
                    'phone' => $phone,
                    'santri' => $santri->nama_santri,
                    'original' => $santri->no_hp
                ]);
                return redirect()->route('pelanggaran.index')
                    ->with('error', 'Verifikasi berhasil, tapi nomor WA tidak valid!');
            }

            try {
                // Buat pesan WA
                $msg = "*Assalaamu'alaikum wr. wb.*\n\n"
                    . "Surat pelanggaran untuk santri *{$santri->nama_santri}* telah diverifikasi.\n\n"
                    . "Anda dapat melihat surat pelanggaran tersebut di website Pondok Pesantren.\n\n"
                    . "Silakan login ke akun Anda untuk melihat detailnya: "
                    . route('home') . "/pelanggaran";

                // Kirim pesan WA
                $wa->sendText($phone, $msg);
                Log::info("Pesan WA terkirim ke $phone", ['santri' => $santri->nama_santri]);
            } catch (\Exception $e) {
                Log::error("Gagal mengirim WA: " . $e->getMessage(), [
                    'phone' => $phone,
                    'santri' => $santri->nama_santri,
                    'exception' => $e
                ]);
            }
        }

        return redirect()->route('pelanggaran.index')
            ->with('success', 'Verifikasi berhasil disimpan.');
    }

    public function pelanggaranWali()
    {
        $user = auth()->user();
        $pelanggarans = \App\Models\Pelanggaran::whereHas(
            'santri',
            fn($q) => $q->where('wali_id', $user->id)
        )
            ->with(['santri', 'jenisPelanggaran'])
            ->latest()->get();
        return view('pelanggaran.santri-pelanggaran', compact('pelanggarans'));
    }

    public function laporanPelanggaran(Request $request)
    {
        // query dasar + relasi yang dibutuhkan
        $query = Pelanggaran::with(['santri', 'jenisPelanggaran']);

        /* ===== FILTER DINAMIS ===== */
        if ($request->filled('jenis')) {
            $query->where('jenis_pelanggaran_id', $request->jenis);
        }
        if ($request->filled('tingkat')) {
            $query->whereHas('jenisPelanggaran', fn($q) => $q->where('tingkat', $request->tingkat));
        }
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

        // urutkan terbaru
        $pelanggarans = $query->latest('tanggal')->paginate(15)->withQueryString();

        // data dropdown filter
        $jenisList   = JenisPelanggaran::orderBy('nama_jenis')->get();
        $tingkatList = ['Ringan', 'Sedang', 'Berat'];

        return view('laporan.laporan-pelanggaran', compact(
            'pelanggarans',
            'jenisList',
            'tingkatList'
        ));
    }
    public function exportExcel(Request $req)
    {
        $fileName = 'laporan_pelanggaran_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new PelanggaranExport($req), $fileName);
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/template_surat_pelanggaran.docx');
        return response()->download($filePath, 'Template_Surat_Pelanggaran.docx');
    }
}
