<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trpinjaman extends Model
{
    protected $table = "trpinjaman";
    public $timestamps = false;

    public static function InfoPinjamanFrontend($trpinjaman)
    {

        $mssetting = Mssetting::where('Kode', 'MobileInfoPinjamanMulaiDari')->first();
        $level = auth()->user()->LevelApprovalPengajuan;
        $arr = [];
        $date = date('M Y');
        foreach ($trpinjaman as $key => $value) {
            $datavalue = json_decode(json_encode($value), true);
            if ($value->Pinjaman > $mssetting->Nilai) {
                $x["uploadJaminanResmi"] = 1;
            } else {
                $x["uploadJaminanResmi"] = 0;
            }
            $x["periode"] = $date;
            $x["level"] = $level;
            $datamerge = array_merge($datavalue, $x);
            $arr[] = $datamerge;
        }

        return $arr;
    }

}
