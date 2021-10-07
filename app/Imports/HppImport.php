<?php

namespace App\Imports;

use App\Mshpp;
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
        $periode = ['202109', '202110'];
        $lokasi = ['P1', 'P2'];
        DB::beginTransaction();
        try {
            foreach ($lokasi as $key => $row) {
                foreach ($periode as $key => $pe) {
                    foreach ($collection as $keydata => $value) {
                        if ($keydata > 0) {
                            $cek = Trhpp::where('KodeBarang', $value[0])->where('KodeLokasi', $row)->where('Periode', $pe)->first();

                            if (!$cek) {
                                $hpp = new Trhpp();
                                $hpp->Periode = $pe;
                                $hpp->KodeBarang = $value[0];
                                $hpp->KodeLokasi = $row;
                                if ($value[3] > 0) {
                                    $hpp->Hpp = $value[3];
                                } else {
                                    $hpp->Hpp = $value[2];
                                }
                                $hpp->save();
                            }
                        }
                    }
                }
            }

            // foreach ($collection as $keydata => $value) {
            //     if ($keydata > 0) {

            //         $cek = Mshpp::where('KodeBarang', $value[0])->first();

            //         if (!$cek) {
            //             array_push($arr, $value[0]);
            //             $hpp = new Mshpp();
            //             $hpp->KodeBarang = $value[0];
            //             if ($value[3] > 0) {
            //                 $hpp->Hpp = $value[3];
            //             } else {
            //                 $hpp->Hpp = $value[2];
            //             }
            //             $hpp->save();
            //         }
            //     }
            // }
            DB::commit();
        } catch (\Exception $th) {
            dd($th);
            DB::rollBack();
        }
    }
}
