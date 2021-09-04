<?php

namespace App\Http\Controllers\Frontend\Koperasi\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Trsaldohutang;
use App\Msanggota;
use App\Traktifasi;
use App\Trpembayaran;
use App\Trsaldoekop;
use App\TrTopUp;

class PembayaranController extends Controller
{
    public function index()
    {

        return view("frontend.koperasi.transaksi.pembayaran.index");
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
        $validator = Validator::make($request->all(), [
            'barcode' => 'required',
            'jumlah_topup' => 'required',
            'tanggal' => 'required'
        ]);

        if ($validator->fails()) {
            $error = '<div class="alert alert-danger" role="alert">
            ' . $validator->errors()->first() . '
           </div>';
            return response()->json([
                'status' => false,
                'data' => $error
            ]);
        } else {
            $barcode = $request->get('barcode');
            $topup = $request->get('jumlah_topup');

            DB::beginTransaction();
            try {
                $traktifasi = Traktifasi::where('Status', 'aktif')->where(function ($q) use ($barcode) {
                    $q->where('Kode', $barcode)->orWhere('NoEkop', $barcode);
                })->first();
                $anggota = Msanggota::where('Kode', $barcode)->first();

                $kodeuser = 0;

                if($traktifasi){
                    $kodeuser = $traktifasi->Kode;
                }elseif($anggota){
                    $kodeuser =$anggota->Kode;
                }
                $saldohutang = Trsaldohutang::where('KodeUser', $kodeuser)->orderBy('Tanggal', 'DESC')->first();
                $day = date('d');
                $month = date('m');
                $year = date('Y');
                $cek = Trpembayaran::whereYear('Tanggal', $year)->whereMonth('Tanggal', $month)->whereDay('Tanggal', $day)->OrderBy('Tanggal', 'DESC')->first();
                if ($cek) {
                    $nomor = (int) substr($cek->Nomor, 14);
                    if ($nomor != 0) {
                        if ($nomor >= 9999) {
                            $nomor = $nomor + 1;
                            $formatNomor = "TP-" . date('Y-m-d') . "-" . $nomor;
                        } else {
                            $nomor = $nomor + 1;
                            $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                            $formatNomor = "TP-" . date('Y-m-d') . "-" . $addzero;
                        }
                    }
                } else {
                    $nomor = 1;
                    $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                    $formatNomor = "TP-" . date('Y-m-d') . "-" . $addzero;
                }

                $trpembayaran = new Trpembayaran();
                $trpembayaran->Nomor = $formatNomor;
                $trpembayaran->Tanggal = date('Y-m-d',strtotime($request->get('tanggal')));
                $trpembayaran->Kode = $kodeuser;
                $trpembayaran->Nilai = $topup;
                $trpembayaran->KodeTransaksi = 20;
                $trpembayaran->UserUpdate = auth('web')->user()->UserLogin;
                $trpembayaran->LastUpdate = date('Y-m-d H:i:s');
                $trpembayaran->save();

                $hutang = new Trsaldohutang();
                $hutang->Tanggal = date('Y-m-d H:i:s');
                $hutang->KodeUser = $kodeuser;
                $hutang->Saldo = $saldohutang->Saldo + $topup;
                $hutang->BayarBerapaKali = round($saldohutang->BayarBerapaKali + ($topup / $saldohutang->CicilanTotal));
                $hutang->CicilanTotal = $saldohutang->CicilanTotal;
                $hutang->TotalBerapaKali = $saldohutang->TotalBerapaKali;

                $hutang->save();
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'success'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                //throw $th;
             return   response()->json($th);
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


    public function CekSaldo(Request $request)
    {
        if ($request->ajax()) {
            $cari = $request->get('cari');
            $cek = Traktifasi::where('Status', 'aktif')->where(function ($q) use ($cari) {
                $q->where('Kode', $cari)->orWhere('NoEkop', $cari);
            })->first();
            $anggota = Msanggota::where('Kode', $cari)->first();
            if ($cek) {
                $anggota = Msanggota::where('Kode', $cek->Kode)->first();
                $saldohutang = Trsaldohutang::where('KodeUser', $cek->Kode)->orderBy('Tanggal', 'DESC')->first();
                if ($saldohutang) {
                    $saldoawal = ($saldohutang->TotalBerapaKali * $saldohutang->CicilanTotal) - ($saldohutang->BayarBerapaKali * $saldohutang->CicilanTotal);
                    $saldo = $this->rupiah($saldoawal);
                    $normalsaldo = $saldoawal;
                } else {
                    $saldo = $this->rupiah(0);
                    $normalsaldo = 0;
                }

                $data = [
                    'kode' => $anggota->Kode,
                    'nama' => $anggota->Nama,
                    'saldo' => $saldo,
                    'normalsaldo' => $normalsaldo
                ];

                return response()->json([
                    'status' => true,
                    'data' => $data
                ]);
            }elseif($anggota){
                $saldohutang = Trsaldohutang::where('KodeUser', $anggota->Kode)->orderBy('Tanggal', 'DESC')->first();
                if ($saldohutang) {
                    $saldoawal = ($saldohutang->TotalBerapaKali * $saldohutang->CicilanTotal) - ($saldohutang->BayarBerapaKali * $saldohutang->CicilanTotal);
                    $saldo = $this->rupiah($saldoawal);
                    $normalsaldo = $saldoawal;
                } else {
                    $saldo = $this->rupiah(0);
                    $normalsaldo = 0;
                }

                $data = [
                    'kode' => $anggota->Kode,
                    'nama' => $anggota->Nama,
                    'saldo' => $saldo,
                    'normalsaldo' => $normalsaldo
                ];

                return response()->json([
                    'status' => true,
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'status' => false
                ]);
            }
        }
    }

    public function rupiah($expression)
    {
        return number_format($expression, 2, ',', '.');
    }
}
