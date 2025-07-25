<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonitoringAkademikExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $tahunId;
    protected $sekolahId;
    protected $kelasId;

    public function __construct($tahunId, $sekolahId, $kelasId)
    {
        $this->tahunId = $tahunId;
        $this->sekolahId = $sekolahId;
        $this->kelasId = $kelasId;
    }

    public function collection()
    {
        $query = \DB::table('nilai')
            ->join('jadwal', 'nilai.jadwal_id', '=', 'jadwal.id_jadwal')
            ->join('mata_pelajaran', 'jadwal.mata_pelajaran_id', '=', 'mata_pelajaran.id_mapel')
            ->where('nilai.tahun_akademik_id', $this->tahunId);

        if ($this->kelasId) {
            $query->join('detail', 'nilai.detail_id', '=', 'detail.id_detail')
                  ->where('detail.kelas_id', $this->kelasId);
        } elseif ($this->sekolahId) {
            $query->join('detail', 'nilai.detail_id', '=', 'detail.id_detail')
                  ->join('kelas', 'detail.kelas_id', '=', 'kelas.id_kelas')
                  ->where('kelas.sekolah_id', $this->sekolahId);
        }

        return $query->select(
                'mata_pelajaran.nama_mapel as mata_pelajaran',
                \DB::raw('ROUND(AVG(COALESCE(nilai_sumatif,0) + COALESCE(nilai_pas,0) + COALESCE(nilai_pat,0)) / 3, 2) as rata')
            )
            ->groupBy('mata_pelajaran.nama_mapel')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Mata Pelajaran',
            'Rata-Rata Nilai'
        ];
    }

    public function title(): string
    {
        return 'Monitoring Akademik';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header style
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9E1F2']
                ]
            ],

            // Alignment for all cells
            'A:B' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],

            // Column widths
            'A' => ['width' => 40],
            'B' => ['width' => 20],
        ];
    }
}
