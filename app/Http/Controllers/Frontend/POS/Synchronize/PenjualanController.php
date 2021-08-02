<?php

namespace App\Http\Controllers\Frontend\POS\Synchronize;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Trmutasihd;
use App\Trmutasidt;
use App\Trsaldoekop;
use App\Trsaldobarang;
use App\Trsaldototalbelanja;
use App\Trsaldototalbelanjaekop;
use App\Trsaldototalbelanjatunai;
use App\Trsaldototalbelanjakredit;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.pos.synchronize.penjualan.index");
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
            $tanggal = $request->get('tanggal');
            $tanggal = date('Y-m-d', strtotime($tanggal));
            $koneksi = 'mysql2';
            DB::beginTransaction();
            try {

                $backuphd = Trmutasihd::where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', $tanggal)->get();
                foreach ($backuphd as $key => $value) {
                    $nomor = $this->generateNomor($tanggal);
                    $no = 1;
                    $cek = DB::connection($koneksi)->table('trmutasihd')->whereDate('Tanggal', $tanggal)->where('NomorLokal', $value->Kode)->where('NomorLokal', '!=', null)->first();

                    if (!$cek  && !empty($value->LokasiAwal)) {
                        DB::connection($koneksi)->table('trmutasihd')->insert([
                            'Transaksi' => 'PENJUALAN',
                            'Nomor' => $nomor,
                            'NomorLokal' => $value->Nomor,
                            'Tanggal' => $value->Tanggal,
                            'KodeSuppCust' => $value->KodeSuppCust,
                            'DiskonPersen' => $value->DiskonPersen,
                            'DiskonTunai' => $value->DiskonTunai,
                            'Pajak' => $value->Pajak,
                            'LokasiAwal' => $value->LokasiAwal,
                            'PembayaranTunai' => $value->PembayaranTunai,
                            'PembayaranKredit' => $value->PembayaranKredit,
                            'PembayaranEkop' => $value->PembayaranEkop,
                            'TotalHarga' => $value->TotalHarga,
                            'StatusPesanan' =>  $value->StatusPesanan,
                            'TotalHargaSetelahPajak' => $value->TotalHargaSetelahPajak,
                            'DueDate' => $value->DueDate,
                        ]);

                        // $this->saveSaldo();
                        $backupdt = Trmutasidt::where('Nomor', $value->Nomor)->get();


                        //belanja tunai
                        $tunai = $value->PembayaranTunai;
                        if ($tunai > 0) {
                            $cektunai = Trsaldototalbelanjatunai::on($koneksi)->where('KodeUser', $value->KodeSuppCust)->OrderBy('Tanggal', 'DESC')->first();
                            $trsaldobelanjatunai = new Trsaldototalbelanjatunai();

                            $trsaldobelanjatunai->Tanggal = date('Y-m-d H:i:s');
                            $trsaldobelanjatunai->KodeUser = $value->KodeSuppCust;
                            if ($cektunai) {
                                $trsaldobelanjatunai->Saldo = $tunai + $cektunai->Saldo;
                            } else {
                                $trsaldobelanjatunai->Saldo = $tunai;
                            }
                            $trsaldobelanjatunai->save();
                        }

                        //pembarayan ekop
                        $pembayaran_ekop = $value->PembayaranEkop;
                        if ($pembayaran_ekop > 0) {
                            $cek = DB::connection($koneksi)->select('call CEKSALDOEKOP(?)', [
                                $value->KodeSuppCust
                            ]);

                            $trsaldoekop = new Trsaldoekop();

                            $trsaldoekop->Tanggal = date('Y-m-d H:i:s');
                            $trsaldoekop->KodeUser = $value->KodeSuppCust;
                            $trsaldoekop->Saldo = $cek[0]->Saldo -  $pembayaran_ekop;
                            $trsaldoekop->save();
                        }

                        //pembayaran kredit
                        $pembayaran_kredit = $value->PembayaranKredit;
                        if ($pembayaran_kredit > 0) {
                            $cek = DB::connection($koneksi)->select('call CEKSALDOEKOP(?)', [
                                $value->KodeSuppCust
                            ]);

                            $trsaldoekop = new Trsaldoekop();

                            $trsaldoekop->Tanggal = date('Y-m-d H:i:s');
                            $trsaldoekop->KodeUser = $value->KodeSuppCust;


                            $trsaldokredit = new Trsaldototalbelanjakredit();

                            $trsaldokredit->Tanggal = date('Y-m-d H:i:s');
                            $trsaldokredit->KodeUser = $value->KodeSuppCust;


                            $trsaldoekop->Saldo = round($cek[0]->Saldo, 2) + $pembayaran_kredit;

                            $cekkredit = Trsaldototalbelanjakredit::on($koneksi)->where('KodeUser', $value->KodeSuppCust)->OrderBy('Tanggal', 'DESC')->first();
                            if ($cekkredit) {
                                $trsaldokredit->Saldo = $pembayaran_kredit + round($cekkredit->Saldo, 2);
                            } else {
                                $trsaldokredit->Saldo = $pembayaran_kredit;
                            }
                            $trsaldoekop->save();
                            $trsaldokredit->save();
                        }

                        //trsaldototalbelanja
                        $cektotalbelanja = Trsaldototalbelanja::on($koneksi)->where('KodeUser', $value->KodeSuppCust)->OrderBy('Tanggal', 'DESC')->first();
                        $trsaldototalbelanja = new Trsaldototalbelanja();

                        $trsaldototalbelanja->Tanggal = date('Y-m-d H:i:s');
                        $trsaldototalbelanja->KodeUser = $value->KodeSuppCust;
                        if ($cektotalbelanja) {
                            $trsaldototalbelanja->Saldo = $pembayaran_kredit + $tunai + $pembayaran_ekop + $cektotalbelanja->Saldo;
                        } else {
                            $trsaldototalbelanja->Saldo = $pembayaran_kredit + $tunai + $pembayaran_ekop;
                        }
                        $trsaldototalbelanja->save();


                        foreach ($backupdt as $key => $row) {
                            DB::connection($koneksi)->table('trmutasidt')->insert([
                                'Transaksi' => 'PENJUALAN',
                                'Nomor' => $nomor,
                                'Urut' => $row->Urut,
                                'KodeBarang' => $row->KodeBarang,
                                'DiskonPersen' => $row->DiskonPersen,
                                'DiskonTunai' => $row->DiskonTunai,
                                'UserUpdate' => $row->UserUpdate,
                                'LastUpdate' => $row->LastUpdate,
                                'Jumlah' => $row->Jumlah,
                                'Harga' => $row->Harga,
                                'Satuan' => $row->Satuan,
                                'HargaLama' => 0,
                            ]);

                            $getstok = Trsaldobarang::on($koneksi)->where('KodeBarang',  $row->KodeBarang)->where('KodeLokasi', $value->LokasiAwal)->OrderBy('Tanggal', 'DESC')->first();
                            $trsaldobarang = new Trsaldobarang();

                            $trsaldobarang->Tanggal = date('Y-m-d H:i:s');
                            $trsaldobarang->KodeBarang =  $row->KodeBarang;
                            if ($getstok) {
                                $trsaldobarang->Saldo = $getstok->Saldo -  $row->Jumlah;
                            } else {
                                $trsaldobarang->Saldo = 0;
                            }

                            $trsaldobarang->KodeLokasi = $value->LokasiAwal;
                            $trsaldobarang->save();
                        }
                    }
                }

                DB::commit();
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Penjualan Berhasil di Synchronize',
                        'code' => Response::HTTP_OK,
                        'data' => $backuphd
                    ]
                );
            } catch (\Exception $th) {
                //throw $th;
                DB::rollBack();
                return response()->json(
                    [
                        'status' => false,
                        'message' => $th,
                        'code' => Response::HTTP_BAD_REQUEST
                    ]
                );
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

    public function generateNomor($tanggal)
    {
        $nomor =  DB::connection('mysql2')->table('trmutasihd')->where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', $tanggal)->max('Nomor');

        if (!is_null($nomor)) {
            $substr = substr($nomor, -5);
            $substr = (int) str_replace('-', '', $substr);
            $nomor = $substr + 1;
            $addzero =  str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = "PE-" . $tanggal . "-" . $addzero;
        } else {
            $nomor = 1;
            $addzero =  str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = "PE-" . $tanggal . "-" . $addzero;
        }

        return $formatNomor;
    }

    public function saveSaldo($kode, $saldoMinus = null, $saldoPlus = null, $saldototal, $saldokredit, $pembayaran_kredit, $tunai)
    {

        $trsalodekop = new Trsaldoekop();
        if (is_null($saldoMinus)) {
            $trsalodekop->Saldo = $saldoPlus;
        } else {
            $trsalodekop->Saldo = $saldoMinus;
        }
        $trsalodekop->Tanggal = date('Y-m-d H:i:s');
        $trsalodekop->KodeUser = $kode;
        $trsalodekop->save();

        $totalbelanja = 0;
        $totalbelanjaekop = 0;
        $gettotalbelanja = TrSaldoTotalBelanja::where('KodeUser', $kode)->orderBy('Tanggal', 'DESC')->first();
        $gettotalbelanjaekop = Trsaldototalbelanjaekop::where('KodeUser', $kode)->orderBy('Tanggal', 'DESC')->first();
        if ($gettotalbelanja) {
            $totalbelanja = $gettotalbelanja->Saldo;
        }
        if ($gettotalbelanjaekop) {
            $totalbelanjaekop = $gettotalbelanjaekop->Saldo;
        }

        $trsaldototalbelanja = new TrSaldoTotalBelanja();
        $trsaldototalbelanja->Tanggal = date('Y-m-d H:i:s');
        $trsaldototalbelanja->KodeUser = $kode;
        $trsaldototalbelanja->Saldo = $saldototal + $totalbelanja;
        $trsaldototalbelanja->save();

        $trsaldototalbelanjaekop = new Trsaldototalbelanjaekop();
        $trsaldototalbelanjaekop->Tanggal = date('Y-m-d H:i:s');
        $trsaldototalbelanjaekop->KodeUser = $kode;
        $trsaldototalbelanjaekop->Saldo = $saldokredit + $totalbelanjaekop;
        $trsaldototalbelanjaekop->save();


        $trsaldokredit = new Trsaldototalbelanjakredit();
        $trsaldokredit->Tanggal = date('Y-m-d H:i:s');
        $trsaldokredit->KodeUser = $kode;
        $cekkredit = Trsaldototalbelanjakredit::where('KodeUser', $kode)->OrderBy('Tanggal', 'DESC')->first();
        if ($cekkredit) {
            $trsaldokredit->Saldo = $pembayaran_kredit + round($cekkredit->Saldo, 2);
        } else {
            $trsaldokredit->Saldo = $pembayaran_kredit;
        }

        $trsaldokredit->save();

        $cektunai = Trsaldototalbelanjatunai::where('KodeUser', $kode)->OrderBy('Tanggal', 'DESC')->first();
        $trsaldobelanjatunai = new Trsaldototalbelanjatunai();
        $trsaldobelanjatunai->Tanggal = date('Y-m-d H:i:s');
        $trsaldobelanjatunai->KodeUser = $kode;
        if ($cektunai) {
            $trsaldobelanjatunai->Saldo = $tunai + $cektunai->Saldo;
        } else {
            $trsaldobelanjatunai->Saldo = $tunai;
        }
        $trsaldobelanjatunai->save();
    }


    public function saveSaldoCloud($kode, $saldoMinus = null, $saldoPlus = null, $saldototal, $saldokredit, $pembayaran_kredit, $tunai)
    {
        $koneksi = 'mysql2';

        $trsalodekop = new Trsaldoekop();
        $trsalodekop->setConnection($koneksi);
        if (is_null($saldoMinus)) {
            $trsalodekop->Saldo = $saldoPlus;
        } else {
            $trsalodekop->Saldo = $saldoMinus;
        }
        $trsalodekop->Tanggal = date('Y-m-d H:i:s');
        $trsalodekop->KodeUser = $kode;
        $trsalodekop->save();

        $totalbelanja = 0;
        $totalbelanjaekop = 0;
        $gettotalbelanja = TrSaldoTotalBelanja::on($koneksi)->where('KodeUser', $kode)->orderBy('Tanggal', 'DESC')->first();
        $gettotalbelanjaekop = Trsaldototalbelanjaekop::on($koneksi)->where('KodeUser', $kode)->orderBy('Tanggal', 'DESC')->first();
        if ($gettotalbelanja) {
            $totalbelanja = $gettotalbelanja->Saldo;
        }
        if ($gettotalbelanjaekop) {
            $totalbelanjaekop = $gettotalbelanjaekop->Saldo;
        }

        $trsaldototalbelanja = new TrSaldoTotalBelanja();
        $trsaldototalbelanja->setConnection($koneksi);
        $trsaldototalbelanja->Tanggal = date('Y-m-d H:i:s');
        $trsaldototalbelanja->KodeUser = $kode;
        $trsaldototalbelanja->Saldo = $saldototal + $totalbelanja;
        $trsaldototalbelanja->save();

        $trsaldototalbelanjaekop = new Trsaldototalbelanjaekop();
        $trsaldototalbelanjaekop->setConnection($koneksi);
        $trsaldototalbelanjaekop->Tanggal = date('Y-m-d H:i:s');
        $trsaldototalbelanjaekop->KodeUser = $kode;
        $trsaldototalbelanjaekop->Saldo = $saldokredit + $totalbelanjaekop;
        $trsaldototalbelanjaekop->save();


        $trsaldokredit = new Trsaldototalbelanjakredit();
        $trsaldokredit->setConnection($koneksi);
        $trsaldokredit->Tanggal = date('Y-m-d H:i:s');
        $trsaldokredit->KodeUser = $kode;
        $cekkredit = Trsaldototalbelanjakredit::where('KodeUser', $kode)->OrderBy('Tanggal', 'DESC')->first();
        if ($cekkredit) {
            $trsaldokredit->Saldo = $pembayaran_kredit + round($cekkredit->Saldo, 2);
        } else {
            $trsaldokredit->Saldo = $pembayaran_kredit;
        }

        $trsaldokredit->save();

        $cektunai = Trsaldototalbelanjatunai::on($koneksi)->where('KodeUser', $kode)->OrderBy('Tanggal', 'DESC')->first();
        $trsaldobelanjatunai = new Trsaldototalbelanjatunai();
        $trsaldobelanjatunai->setConnection($koneksi);
        $trsaldobelanjatunai->Tanggal = date('Y-m-d H:i:s');
        $trsaldobelanjatunai->KodeUser = $kode;
        if ($cektunai) {
            $trsaldobelanjatunai->Saldo = $tunai + $cektunai->Saldo;
        } else {
            $trsaldobelanjatunai->Saldo = $tunai;
        }
        $trsaldobelanjatunai->save();
    }
}
