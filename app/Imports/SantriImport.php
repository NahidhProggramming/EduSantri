<?php

namespace App\Imports;

use App\Models\Santri;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SantriImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Abaikan baris pertama jika berisi heading
        $rows->shift();

        foreach ($rows as $row) {
            // Validasi minimal data yang dibutuhkan
            if (count($row) < 9 || empty($row[1])) {
                continue; // Lewati jika data tidak lengkap
            }

            $tahunMasuk = 2023;
            $prefix = (string) $tahunMasuk;

            $lastSantri = Santri::where('nis', 'like', $prefix . '%')
                ->orderBy('nis', 'desc')
                ->first();

            $lastNumber = 0;
            if ($lastSantri) {
                $lastNumber = (int) substr($lastSantri->nis, strlen($prefix));
            }

            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $nis = $prefix . $newNumber;

            // Tanggal lahir bisa dalam format excel date number atau string
            $tanggalLahir = null;
            if (is_numeric($row[3])) {
                $tanggalLahir = Date::excelToDateTimeObject($row[3])->format('Y-m-d');
            } elseif (strtotime($row[3])) {
                $tanggalLahir = date('Y-m-d', strtotime($row[3]));
            }

            $santri = Santri::create([
                'nis' => $nis,
                'nisn' => is_numeric($row[0]) ? (string) $row[0] : null,
                'nama_santri' => $row[1],
                'tempat_lahir' => $row[2],
                'tanggal_lahir' => $tanggalLahir,
                'jenis_kelamin' => $row[4],
                'alamat' => $row[5],
                'ayah' => $row[6],
                'ibu' => $row[7],
                'no_hp' => $row[8],
                'foto' => null,
            ]);

            User::create([
                'name' => $santri->nama_santri,
                'username' => $nis,
                'password' => Hash::make($nis),
            ])->assignRole('wali_santri');
        }
    }
}
