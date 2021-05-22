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

        foreach ($collection as $key => $value) {
            if ($key > 0) {
                $anggota = Msanggota::where("Kode", $value[0])->first();
                if ($anggota) {
                    $trsaldosimpanan = new Trsaldosimpanan();
                    $trsaldosimpanan->Tanggal = date('Y-m-d H:i:s');
                    $trsaldosimpanan->KodeUser = $value[0];
                    $trsaldosimpanan->Saldo = $value[1];
                    $trsaldosimpanan->save();

                    $trperiode = TestPeriode::where('KodeUser', $value[0])->whereMonth('LastUpdate', $month)->whereYear('LastUpdate', $year)->OrderBy('LastUpdate', 'DESC')->first();
                    if ($trperiode) {
                        $formatNomor = $trperiode->Nomor;
                    } else {
                        $trperiode = TestPeriode::whereMonth('LastUpdate', $month)->whereYear('LastUpdate', $year)->OrderBy('LastUpdate', 'DESC')->first();
                        if ($trperiode) {
                            $substr = substr($trperiode->Nomor, -5);
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


                    if ($value[2] != null || $value[2] != '') {
                        $trsaldohutang = new Trsaldohutang();
                        $trsaldohutang->Tanggal = date('Y-m-d H:i:s');
                        $trsaldohutang->KodeUser = $value[0];
                        $trsaldohutang->Saldo =  $value[2];
                        $trsaldohutang->BayarBerapaKali =  $value[3];
                        $trsaldohutang->CicilanTotal =  $value[4];
                        $trsaldohutang->TotalBerapaKali =  $value[5];
                        $trsaldohutang->save();

                        $trtransaksiperiode = new TestPeriode();
                        $trtransaksiperiode->Nomor = $formatNomor;
                        $trtransaksiperiode->Periode = date('Ym');
                        $trtransaksiperiode->KodeUser = $value[0];
                        $trtransaksiperiode->KodeTransaksi = "20";
                        $trtransaksiperiode->Nilai = $value[2];
                        $trtransaksiperiode->UserUpdate = Auth::guard('web')->user()->UserLogin;
                        $trtransaksiperiode->LastUpdate =  date('Y-m-d H:i:s');
                        $trtransaksiperiode->save();


                        $trpinjaman = Trpinjaman::whereMonth('LastUpdate', $month)->whereYear('LastUpdate', $year)->OrderBy('LastUpdate', 'DESC')->first();
                        if ($trpinjaman) {
                            $substr = substr($trpinjaman->Nomor, -5);
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
                        $datatrpinjaman->SubDept = $anggota->SubDept;
                        $datatrpinjaman->Pinjaman = $mscicilan->Nominal;
                        $datatrpinjaman->CicilanTotal = $mscicilan->CicilanTotal;
                        $datatrpinjaman->BerapaKaliBayar = $value[5];
                        $datatrpinjaman->CicilanPokok = $mscicilan->CicilanPokok;
                        $datatrpinjaman->CicilanBunga = $mscicilan->CicilanBunga;
                        $datatrpinjaman->Alasan = "Saldo Awal";
                        $datatrpinjaman->TanggalPengajuan = date('Y-m-d');
                        $datatrpinjaman->UserUpdate = Auth::guard('web')->user()->UserLogin;
                        $datatrpinjaman->ApprovalStatus = "DISETUJUI";
                        $datatrpinjaman->PengajuanPinjaman = $mscicilan->Nominal;
                        $datatrpinjaman->save();
                    }

                    // if ($value[2] == null || $value[2] == '') {
                    //     $trtransaksiperiode = new Trtransaksiperiode();
                    //     $trtransaksiperiode->Nomor = $formatNomor;
                    //     $trtransaksiperiode->Periode = date('Ym');
                    //     $trtransaksiperiode->KodeUser = $value[0];
                    //     $trtransaksiperiode->KodeTransaksi = "01";
                    //     $trtransaksiperiode->Nilai = $value[1];
                    //     $trtransaksiperiode->UserUpdate = Auth::guard('web')->user()->UserLogin;
                    //     $trtransaksiperiode->LastUpdate =  date('Y-m-d H:i:s');
                    //     $trtransaksiperiode->save();
                    // }
                }
            }
        }

        return true;
    }
}
