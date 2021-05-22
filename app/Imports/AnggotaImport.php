<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Msanggota;

class AnggotaImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if ($key > 0) {
                $anggota = Msanggota::where('Kode', $value[0])->first();
                if (!$anggota) {
                    $msanggota = new Msanggota();
                    $msanggota->Kode = $value[0];
                    $msanggota->Nama = $value[1];
                    $msanggota->Aktif = $value[2];
                    $msanggota->Sex = $value[3];
                    $msanggota->Grp = $value[4];
                    $msanggota->Pangkat = $value[5];
                    $msanggota->SubDept = $value[6];
                    $msanggota->TglMasuk = date('Y-m-d', strtotime($value[7]));
                    if ($msanggota->TglKeluar != null) {
                        $msanggota->TglKeluar = date('Y-m-d', strtotime($value[8]));
                    }
                    $msanggota->UserPassword = '000000';
                    $msanggota->save();
                }
            }
        }
    }
}
