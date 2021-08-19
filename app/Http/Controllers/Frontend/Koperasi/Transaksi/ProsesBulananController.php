<?php

namespace App\Http\Controllers\Frontend\Koperasi\Transaksi;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

class ProsesBulananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.koperasi.transaksi.proses_bulanan.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $status = $request->get('status');
            // $tgl_akhir = date('Y-m-d');
            $tgl_akhir = $request->get('tanggal_akhir');
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
            $tgl_awal = date("Y-m-d", strtotime('-1 month', strtotime($tgl_akhir)));
            DB::beginTransaction();

            try {

                $setting = Mssetting::where('Kode', 'SaldoMinusResetPerBulan')->where('aktif', 1)->first();
                $saldominusbunga = Mssetting::where('Kode', 'SaldoMinusBunga')->where('aktif', 1)->first();
                $saldominusmax = Mssetting::where('Kode', 'SaldoMinusMax')->where('aktif', 1)->first();
                $day = date('d');
                if ($status == 'cek') {
                    if ($day == $setting->Nilai) {
                        return response()->json([
                            'status' => true,
                            'data' => 'Data sudah ada, apakah akan diproses ulang ?'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false
                        ]);
                    }
                } elseif ($status == 'prosesulang') {
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
                                $saldoreset->UserUpdate = auth('web')->user()->UserLogin;
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
                                $saldoreset->UserUpdate = auth('web')->user()->UserLogin;
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

                    return response()->json(['status' => true]);
                }
            } catch (\Exception $th) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'data' => 'maaf ada yang error'
                ]);
            }
        }
    }


    public function store_simpan_pinjam(Request $request)
    {
        if ($request->ajax()) {
            $periode = $request->get('periode');
            $month = date('m', strtotime($periode));
            $year = date('Y', strtotime($periode));
            $periode = date('Ym', strtotime($periode));
            $status = $request->get('status');
            DB::beginTransaction();

            try {
                $trperiode = Trtransaksiperiode::where('Periode', $periode)->first();
                if ($trperiode && $status == 'cek') {
                    return response()->json([
                        'status' => true,
                        'message' => 'Data sudah ada',
                        'data' => $trperiode
                    ]);
                } elseif ($status == 'insert') {

                    $loopa = Msanggota::where('Pangkat', '!=', 'karyawan kontrak')->where('Pangkat', '!=', null)->whereYear('TglKeluar', '<=', $year)->whereMonth('TglKeluar', '<=', $month)->get();
                    $loopb = Mstransaksi::leftjoin('mstransaksidetail', 'mstransaksi.Kode', 'mstransaksidetail.Kode')->where('Aktif', 1)->where('tahun', $year)->get();

                    
                    foreach ($loopa as $key => $value) {
                        $tglmasuk = date('Ym', strtotime($value->TglMasuk));
                        foreach ($loopb as $key => $row) {
                            if ($row->CaraBayar == 'Tiap Bulan' || ($row->CaraBayar == 'Sekali Bayar' && $tglmasuk == $periode)) {
                                $formatNomor = $this->generateNomor($month, $year);
                                $trperiode = new Trtransaksiperiode();
                                $trperiode->Nomor = $formatNomor;
                                $trperiode->Periode = $periode;
                                $trperiode->KodeUser = $value->Kode;
                                $trperiode->KodeTransaksi = $row->Kode;
                                $trperiode->Nilai = $row->Nilai;
                                $trperiode->save();

                                if ($row->Kode == '00' || $row->Kode == '01') {
                                    $lastsimpan = Trsaldosimpanan::where('KodeUser', $value->Kode)->orderBy('Tanggal', 'DESC')->first();
                                    $saldo = 0;
                                    if ($lastsimpan) {
                                        $saldo = $lastsimpan->Saldo;
                                    }

                                    $newsimpan = new Trsaldosimpanan();
                                    $newsimpan->Tanggal = date('Y-m-d H:i:s');
                                    $newsimpan->KodeUser = $value->Kode;
                                    $newsimpan->Saldo = $saldo + $row->Nilai;
                                    $newsimpan->save();
                                }

                                if ($row->Kode == '20') {
                                    $trpinjaman = Trpinjaman::where('KodeAnggota', $value->Kode)->first();
                                    if ($trpinjaman) {
                                        $lastsaldo = Trsaldohutang::where('KodeAnggota', $value->Kode)->orderBy('Tanggal', 'DESC')->first();
                                        $berapakali = 0;
                                        if ($lastsaldo) {
                                            $berapakali = $lastsaldo->BayarBerapaKali;
                                        }
                                        $saldohutang = new Trsaldohutang();
                                        $saldohutang->Tanggal = date('Y-m-d H:i:s');
                                        $saldohutang->KodeAnggota = $value->Kode;
                                        $saldohutang->BayarBerapaKali = $berapakali + 1;
                                        $saldohutang->CicilanTotal = $trpinjaman->CicilanTotal;
                                        $saldohutang->Saldo = $saldohutang->BayarBerapaKali * $saldohutang->CicilanTotal;
                                        $saldohutang->save();
                                    }
                                }
                            }
                        }
                    }


                    DB::commit();
                    return response()->json(['status' => true]);
                }
            } catch (\Exception $th) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'data' => 'maaf ada yang error'
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function generateNomor($month, $year)
    {
        $trperiode = Trtransaksiperiode::whereYear('LastUpdate', $year)->whereMonth('LastUpdate', $month)->OrderBy('LastUpdate', 'DESC')->first();
        if ($trperiode) {
            $nomor = (int) substr($trperiode->Nomor, 14);
            if ($nomor != 0) {
                if ($nomor >= 9999) {
                    $nomor = $nomor + 1;
                    $formatNomor = "TB-" . date('Y-m-d') . "-" . $nomor;
                } else {
                    $nomor = $nomor + 1;
                    $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                    $formatNomor = "TB-" . date('Y-m-d') . "-" . $addzero;
                }
            }
        } else {
            $nomor = 1;
            $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = "TB-" . date('Y-m-d') . "-" . $addzero;
        }

        return $formatNomor;
    }
}
