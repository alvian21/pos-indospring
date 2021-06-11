<?php

namespace App\Imports;

use App\Trpinjaman;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Trsaldosimpanan;
use App\Trsaldohutang;
use App\Trtransaksiperiode;
use Illuminate\Support\Facades\Auth;
use App\Msanggota;
use App\Mscicilan;
use App\TestPeriode;
use App\Trperiode;

class CsvImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {

        $month = date('m');
        $year = date('Y');
        $user = auth('web')->user()->UserLogin;
        foreach ($collection as $key => $value) {
            if ($key > 0) {

                    $trsaldosimpanan = new Trsaldosimpanan();
                    $trsaldosimpanan->Tanggal = date('Y-m-d H:i:s');
                    $trsaldosimpanan->KodeUser = $value[0];
                    $trsaldosimpanan->Saldo = $value[1];
                    $trsaldosimpanan->save();

                    $trperiode = Trtransaksiperiode::where('KodeUser', $value[0])->whereMonth('LastUpdate', $month)->whereYear('LastUpdate', $year)->max('Nomor');
                    if (!is_null($trperiode)) {
                        $formatNomor = $trperiode;
                    } else {
                        $trperiode = Trtransaksiperiode::whereMonth('LastUpdate', $month)->whereYear('LastUpdate', $year)->max('Nomor');

                        if ($trperiode != null) {
                            $substr = substr($trperiode, -5);
                            $substr = (int) str_replace('-', '', $substr);
                            $nomor = $substr + 1;
                            $addzero =  str_pad($nomor, 4, '0', STR_PAD_LEFT);
                            $formatNomor = "TB-" . date('Y-m-d') . "-" . $addzero;
                        } else {
                            $nomor = 1;
                            $addzero =  str_pad($nomor, 4, '0', STR_PAD_LEFT);
                            $formatNomor = "TB-" . date('Y-m-d') . "-" . $addzero;
                        }
                    }


                    if ($value[2] != 0) {
                        $trsaldohutang = new Trsaldohutang();
                        $trsaldohutang->Tanggal = date('Y-m-d H:i:s');
                        $trsaldohutang->KodeUser = $value[0];
                        $trsaldohutang->Saldo =  $value[2];
                        $trsaldohutang->BayarBerapaKali =  $value[3];
                        $trsaldohutang->CicilanTotal =  $value[4];
                        $trsaldohutang->TotalBerapaKali =  $value[5];
                        $trsaldohutang->save();

                        $trtransaksiperiode = new Trtransaksiperiode();
                        $trtransaksiperiode->Nomor = $formatNomor;
                        $trtransaksiperiode->Periode = date('Ym');
                        $trtransaksiperiode->KodeUser = $value[0];
                        $trtransaksiperiode->KodeTransaksi = "20";
                        $trtransaksiperiode->Nilai = $value[2];
                        $trtransaksiperiode->UserUpdate = $user;
                        $trtransaksiperiode->LastUpdate =  date('Y-m-d H:i:s');
                        $trtransaksiperiode->save();

                        $trtransaksiperiode = new Trtransaksiperiode();
                        $trtransaksiperiode->Nomor = $formatNomor;
                        $trtransaksiperiode->Periode = date('Ym');
                        $trtransaksiperiode->KodeUser = $value[0];
                        $trtransaksiperiode->KodeTransaksi = "01";
                        $trtransaksiperiode->Nilai = $value[1];
                        $trtransaksiperiode->UserUpdate = $user;
                        $trtransaksiperiode->LastUpdate =  date('Y-m-d H:i:s');
                        $trtransaksiperiode->save();


                        $trpinjaman = Trpinjaman::whereMonth('LastUpdate', $month)->whereYear('LastUpdate', $year)->max('Nomor');
                        if ($trpinjaman != null) {
                            $substr = substr($trpinjaman, -5);
                            $substr = (int) str_replace('-', '', $substr);
                            $nomor = $substr + 1;
                            $addzero =  str_pad($nomor, 4, '0', STR_PAD_LEFT);
                            $formatNomorpinjaman = "PI-" . date('Y-m-d') . "-" . $addzero;
                        } else {
                            $nomor = 1;
                            $addzero =  str_pad($nomor, 4, '0', STR_PAD_LEFT);
                            $formatNomorpinjaman = "PI-" . date('Y-m-d') . "-" . $addzero;
                        }

                        $anggota = Msanggota::where("Kode", $value[0])->first();
                        $mscicilan = Mscicilan::where("Bulan", $value[5])->where("CicilanTotal", $value[4])->first();
                        $datatrpinjaman = new Trpinjaman();
                        $datatrpinjaman->Nomor = $formatNomorpinjaman;
                        $datatrpinjaman->KodeAnggota = $value[0];
                        if($anggota){
                            $datatrpinjaman->SubDept = $anggota->SubDept;
                        }else{
                            $datatrpinjaman->SubDept = "-";
                        }

                        if($mscicilan){
                            $datatrpinjaman->Pinjaman = $mscicilan->Nominal;
                            $datatrpinjaman->CicilanTotal = $mscicilan->CicilanTotal;
                            $datatrpinjaman->CicilanPokok = $mscicilan->CicilanPokok;
                            $datatrpinjaman->CicilanBunga = $mscicilan->CicilanBunga;
                            $datatrpinjaman->PengajuanPinjaman = $mscicilan->Nominal;
                        }
                        $datatrpinjaman->BerapaKaliBayar = $value[5];
                        $datatrpinjaman->Alasan = "Saldo Awal";
                        $datatrpinjaman->TanggalPengajuan = date('Y-m-d');
                        $datatrpinjaman->UserUpdate = $user;
                        $datatrpinjaman->ApprovalStatus = "DISETUJUI";
                        $datatrpinjaman->save();
                    }

                    if ($value[2] == null || $value[2] == '') {
                        $trtransaksiperiode = new Trtransaksiperiode();
                        $trtransaksiperiode->Nomor = $formatNomor;
                        $trtransaksiperiode->Periode = date('Ym');
                        $trtransaksiperiode->KodeUser = $value[0];
                        $trtransaksiperiode->KodeTransaksi = "01";
                        $trtransaksiperiode->Nilai = $value[1];
                        $trtransaksiperiode->UserUpdate = $user;
                        $trtransaksiperiode->LastUpdate =  date('Y-m-d H:i:s');
                        $trtransaksiperiode->save();
                    }

            }
        }

        return true;
    }
}
