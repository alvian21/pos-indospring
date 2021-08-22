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
            DB::connection($koneksi)->beginTransaction();
            try {
                $backuphd = Trmutasihd::where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', $tanggal)->get();
                $jumlahhd = Trmutasihd::where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', $tanggal)->count();
                $jumlahhdcloud = Trmutasihd::on($koneksi)->where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', $tanggal)->where('NomorLokal', '!=', null)->count();

                if ($request->get('status') == 'progress') {

                    $persen = ($jumlahhdcloud / $jumlahhd) * 100;
                    $persen = round($persen);
                    $html = '<div class="progress-bar" role="progressbar" style="width: ' . $persen . '%;" aria-valuenow="' . $persen . '"
                    aria-valuemin="0" aria-valuemax="100">' . $persen . '%</div>';


                    return response()->json([
                        'status' => true,
                        'data' => $html,
                        'jumlah' => $jumlahhd

                    ]);
                } else {
                    foreach ($backuphd as $key => $value) {
                        $nomor = $this->generateNomor($tanggal);
                        $no = 1;
                        $cek = DB::connection($koneksi)->table('trmutasihd')->whereDate('Tanggal', $tanggal)->where('NomorLokal', $value->Nomor)->where('NomorLokal', '!=', null)->first();

                        if (!$cek) {
                            DB::connection($koneksi)->table('trmutasihd')->insert([
                                'Transaksi' => 'PENJUALAN',
                                'Nomor' => $nomor,
                                'NomorLokal' => $value->Nomor,
                                'Tanggal' => $value->Tanggal,
                                'KodeSuppCust' => $value->KodeSuppCust,
                                'DiskonPersen' => $value->DiskonPersen,
                                'DiskonTunai' => $value->DiskonTunai,
                                'Pajak' => $value->Pajak,
                                'LokasiAwal' => auth()->user()->KodeLokasi,
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
                            $tunai = intval($tunai);
                            if ($tunai > 0 && $tunai != 0) {
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


                                $trsaldobelanjatunai = new Trsaldototalbelanjatunai();
                                $trsaldobelanjatunai->setConnection($koneksi);
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

                                if (isset($cek[0])) {
                                    $trsaldoekop = new Trsaldoekop();

                                    $trsaldoekop->Tanggal = date('Y-m-d H:i:s');
                                    $trsaldoekop->KodeUser = $value->KodeSuppCust;
                                    $trsaldoekop->Saldo = $cek[0]->Saldo -  $pembayaran_ekop;
                                    $trsaldoekop->save();


                                    $trsaldoekop = new Trsaldoekop();
                                    $trsaldoekop->setConnection($koneksi);
                                    $trsaldoekop->Tanggal = date('Y-m-d H:i:s');
                                    $trsaldoekop->KodeUser = $value->KodeSuppCust;
                                    $trsaldoekop->Saldo = $cek[0]->Saldo -  $pembayaran_ekop;
                                    $trsaldoekop->save();

                                    $gettotalbelanjaekop = Trsaldototalbelanjaekop::on($koneksi)->where('KodeUser', $value->KodeSuppCust)->orderBy('Tanggal', 'DESC')->first();
                                    $totalbelanjaekop = 0;
                                    if ($gettotalbelanjaekop) {
                                        $totalbelanjaekop = $gettotalbelanjaekop->Saldo;
                                    }

                                    $trsaldototalbelanjaekop = new Trsaldototalbelanjaekop();
                                    $trsaldototalbelanjaekop->Tanggal = date('Y-m-d H:i:s');
                                    $trsaldototalbelanjaekop->KodeUser = $value->KodeSuppCust;
                                    $trsaldototalbelanjaekop->Saldo = $totalbelanjaekop + $pembayaran_ekop;
                                    $trsaldototalbelanjaekop->save();

                                    $trsaldototalbelanjaekop = new Trsaldototalbelanjaekop();
                                    $trsaldototalbelanjaekop->setConnection($koneksi);
                                    $trsaldototalbelanjaekop->Tanggal = date('Y-m-d H:i:s');
                                    $trsaldototalbelanjaekop->KodeUser = $value->KodeSuppCust;
                                    $trsaldototalbelanjaekop->Saldo = $totalbelanjaekop + $pembayaran_ekop;
                                    $trsaldototalbelanjaekop->save();
                                }
                            }

                            //pembayaran kredit
                            $pembayaran_kredit = $value->PembayaranKredit;
                            if ($pembayaran_kredit > 0) {
                                $cek = DB::connection($koneksi)->select('call CEKSALDOEKOP(?)', [
                                    $value->KodeSuppCust
                                ]);

                                if (isset($cek[0])) {
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



                                    $trsaldoekop = new Trsaldoekop();
                                    $trsaldoekop->setConnection($koneksi);
                                    $trsaldoekop->Tanggal = date('Y-m-d H:i:s');
                                    $trsaldoekop->KodeUser = $value->KodeSuppCust;


                                    $trsaldokredit = new Trsaldototalbelanjakredit();
                                    $trsaldokredit->setConnection($koneksi);
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


                            $trsaldototalbelanja = new Trsaldototalbelanja();
                            $trsaldototalbelanja->setConnection($koneksi);
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

                                $getstok = Trsaldobarang::on($koneksi)->where('KodeBarang',  $row->KodeBarang)->where('KodeLokasi', auth()->user()->KodeLokasi)->OrderBy('Tanggal', 'DESC')->first();
                                $trsaldobarang = new Trsaldobarang();
                                $saldobarang = $getstok->Saldo -  $row->Jumlah;
                                $trsaldobarang->Tanggal = date('Y-m-d H:i:s');
                                $trsaldobarang->KodeBarang =  $row->KodeBarang;
                                if ($getstok) {
                                    $trsaldobarang->Saldo = $saldobarang;
                                } else {
                                    $trsaldobarang->Saldo = 0;
                                }

                                $trsaldobarang->KodeLokasi = auth()->user()->KodeLokasi;
                                $trsaldobarang->save();


                                $trsaldobaranglokal = new Trsaldobarang();
                                $trsaldobaranglokal->setConnection($koneksi);
                                $trsaldobaranglokal->Tanggal = date('Y-m-d H:i:s');
                                $trsaldobaranglokal->KodeBarang =  $row->KodeBarang;
                                if ($getstok) {
                                    $trsaldobaranglokal->Saldo = $saldobarang;
                                } else {
                                    $trsaldobaranglokal->Saldo = 0;
                                }

                                $trsaldobaranglokal->KodeLokasi = auth()->user()->KodeLokasi;
                                $trsaldobaranglokal->save();
                            }
                        }
                    }

                    DB::commit();
                    DB::connection($koneksi)->commit();
                    return response()->json(
                        [
                            'status' => true,
                            'message' => 'Penjualan Berhasil di Synchronize',
                            'code' => Response::HTTP_OK,
                            'data' => $backuphd
                        ]
                    );
                }
            } catch (\Exception $th) {
                //throw $th;
                DB::rollBack();
                DB::connection($koneksi)->rollBack();
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

    public function hapus()
    {
        $koneksi = 'mysql2';
        $cek = DB::connection($koneksi)->table('trmutasihd')->where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', "2021-07-30")->where('NomorLokal', '!=', null)->get();

        foreach ($cek as $key => $value) {
            $cekdt = DB::connection($koneksi)->table('trmutasidt')->where('Transaksi', 'PENJUALAN')->where('Nomor', $value->Nomor)->delete();
        }

        $cek = DB::connection($koneksi)->table('trmutasihd')->where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', "2021-07-30")->where('NomorLokal', '!=', null)->delete();

        return response()->json([
            'message' => 'berhasil'
        ]);
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


}
