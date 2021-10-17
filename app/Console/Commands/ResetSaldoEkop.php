<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Trsaldoreset;
use App\Mssetting;
use App\Msanggota;
use App\Mstransaksi;
use App\Trmutasihd;
use App\Trpinjaman;
use App\Trsaldoekop;
use App\Trsaldohutang;
use App\Trsaldosimpanan;
use App\Trtransaksiperiode;

class ResetSaldoEkop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saldo:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset saldo ekop';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tgl_akhir = date('Y-m') . '-15';
        $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        $tgl_awal = date("Y-m-d", strtotime('-1 month', strtotime($tgl_akhir)));
        DB::beginTransaction();

        try {

            $setting = Mssetting::where('Kode', 'SaldoMinusResetPerBulan')->where('aktif', 1)->first();
            $saldominusbunga = Mssetting::where('Kode', 'SaldoMinusBunga')->where('aktif', 1)->first();
            $saldominusmax = Mssetting::where('Kode', 'SaldoMinusMax')->where('aktif', 1)->first();
            $day = date('d');

            $anggota = Msanggota::all();

            foreach ($anggota as $key => $value) {
                $saldoekop = Trsaldoekop::where('KodeUser', $value->Kode)->orderBy('Tanggal', 'DESC')->first();

                if ($saldoekop) {
                    $grupbycustomer = Trmutasihd::selectRaw('sum(TotalHarga)+(sum(TotalHarga)* ? /100) as totalhitung', [$saldominusbunga->Nilai])->where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', '>=', $tgl_awal)->whereDate('Tanggal', '<=', $tgl_akhir)->where('KodeSuppCust', $value->Kode)->first();

                    if ($grupbycustomer->totalhitung == null) {
                        $grupbycustomer->totalhitung = 0;
                    }

                    if ($saldoekop->Saldo < 0) {
                        $saldoreset = new Trsaldoreset();
                        $saldoreset->SaldoSisaEkop = $saldominusmax->Nilai + $saldoekop->Saldo;
                        $saldoreset->SaldoBelanjaKredit  = $grupbycustomer->totalhitung;
                        $saldoreset->Tanggal = date('Y-m-d H:i:s');
                        $saldoreset->KodeUser = $value->Kode;
                        $saldoreset->UserUpdate = "";
                        $saldoreset->save();

                        $trsaldoekop = new Trsaldoekop();
                        $trsaldoekop->Tanggal = date('Y-m-d H:i:s');
                        $trsaldoekop->KodeUser = $value->Kode;
                        $trsaldoekop->SaldoMinus = 0;
                        $trsaldoekop->Saldo = -1 * $saldominusmax->Nilai;
                        $trsaldoekop->save();
                    } else {

                        if ($saldoekop->SaldoMinus == null) {
                            $saldoekop->SaldoMinus = 0;
                        }

                        $saldoreset = new Trsaldoreset();
                        $saldoreset->SaldoSisaEkop = $saldominusmax->Nilai + $saldoekop->SaldoMinus;
                        $saldoreset->SaldoBelanjaKredit  = $grupbycustomer->totalhitung;
                        $saldoreset->Tanggal = date('Y-m-d H:i:s');
                        $saldoreset->KodeUser = $value->Kode;
                        $saldoreset->UserUpdate = "";
                        $saldoreset->save();

                        $trsaldoekop = new Trsaldoekop();
                        $trsaldoekop->Tanggal = date('Y-m-d H:i:s');
                        $trsaldoekop->KodeUser = $value->Kode;
                        $trsaldoekop->SaldoMinus =  -1 * $saldominusmax->Nilai;
                        $trsaldoekop->Saldo = $saldoekop->Saldo;
                        $trsaldoekop->save();
                    }
                }
            }

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th);
        }
    }
}
