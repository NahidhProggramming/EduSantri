<?php
namespace App\Exports;

use App\Models\Pelanggaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class PelanggaranExport implements FromCollection, WithHeadings
{
    protected $request;
    public function __construct(Request $req){ $this->request=$req; }

    public function collection()
    {
        $q = Pelanggaran::with('jenisPelanggaran','santri');

        // terapkan filter sama seperti di index()
        if ($this->request->jenis)   $q->where('jenis_pelanggaran_id',$this->request->jenis);
        if ($this->request->tingkat) $q->whereHas('jenisPelanggaran',
                                   fn($x)=>$x->where('tingkat',$this->request->tingkat));
        if ($this->request->dari)    $q->whereDate('tanggal','>=',$this->request->dari);
        if ($this->request->sampai)  $q->whereDate('tanggal','<=',$this->request->sampai);

        return $q->get()->map(fn($p)=>[
            $p->santri_nis,
            $p->santri->nama_santri,
            $p->jenisPelanggaran->nama_jenis,
            $p->jenisPelanggaran->tingkat,
            $p->tanggal,
            $p->deskripsi,
        ]);
    }

    public function headings():array
    {
        return ['NIS','Nama','Jenis','Tingkat','Tanggal','Deskripsi'];
    }
}
