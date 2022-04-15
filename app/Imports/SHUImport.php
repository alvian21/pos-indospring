<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use App\Trshu;

class SHUImport implements ToCollection
{
    /**
     * @param Collection $collection
     */


    public function collection(Collection $collection)
    {
        DB::beginTransaction();

        try {
            $request = request()->all();
            $periode = $request['periode'];

            foreach ($collection as $key => $value) {
                if ($key > 5) {
                    $kode = $value[2];
                    $nama = $value[3];
                    $total_kontribusi = $value[4];
                    $poin = $value[5];
                    $nilai_poin = $value[6];
                    $anggota = Trshu::where('kode', $kode)->where('periode', $periode)->first();
                    if (!$anggota) {
                        $trshu = new Trshu();

                        if ($value[1] == 'GRAND TOTAL') {
                            $trshu->kode = "TOTSHU" . $periode;
                            $trshu->nama = "GRAND TOTAL " . $periode;
                        } else {
                            $trshu->kode = $kode;
                            $trshu->nama = $nama;
                        }

                        $trshu->total_kontribusi = $total_kontribusi;
                        $trshu->poin = $poin;
                        $trshu->nilai_poin = $nilai_poin;
                        $trshu->periode = $periode;
                        $trshu->save();
                    }
                }
            }
            DB::commit();
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
