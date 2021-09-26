<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Trhpp;

class HppImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $periode = date('Ym');
        $lokasi = ['P1','P2'];
        DB::beginTransaction();
        try {
            foreach ($lokasi as $key => $row) {
                foreach ($collection as $keydata => $value) {
                    if ($keydata > 0) {
                            $hpp = new Trhpp();
                            $hpp->Periode = $periode;
                            $hpp->KodeBarang = $value[0];
                            $hpp->KodeLokasi = $row;
                            $hpp->Hpp = round($value[3]/$value[2]);
                            $hpp->save();
                    }
                }
            }

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
        }
    }
}
