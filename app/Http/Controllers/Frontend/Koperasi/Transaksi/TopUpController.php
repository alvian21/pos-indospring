<?php

namespace App\Http\Controllers\Frontend\Koperasi\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Msanggota;
use App\Traktifasi;
use App\Trsaldoekop;
use App\TrTopUp;

class TopUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $traktifasi = Traktifasi::leftjoin('msanggota', 'traktifasi.Kode', 'msanggota.Kode')->where('Status','aktif')->get();
        return view("frontend.koperasi.transaksi.top_up.index", ['traktifasi' => $traktifasi]);
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
            'jumlah_topup' => 'required'
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
            $traktifasi = Traktifasi::where('Nomor', $barcode)->first();
            $ekop = Trsaldoekop::where('KodeUser', $traktifasi->Kode)->orderBy('Tanggal', 'DESC')->first();
            $saldoawal = 0;
            if($ekop){
                $saldoawal =  $ekop->Saldo;
                if($ekop->Saldo > 0){
                    $saldo = $ekop->Saldo + $topup;
                }else{
                    $saldo = $topup;
                }
            }else{
                $saldo = $topup;
            }

            $trtopup = new TrTopUp();
            $trtopup->Nomor = $traktifasi->Nomor;
            $trtopup->Tanggal = date('Y-m-d H:i:s');
            $trtopup->Kode = $traktifasi->Kode;
            $trtopup->NoEkop = $traktifasi->NoEkop;
            $trtopup->Nilai = $topup;
            $trtopup->SaldoAwal = $saldoawal;
            $trtopup->UserUpdate = auth('web')->user()->UserLogin;
            $trtopup->LastUpdate = date('Y-m-d H:i:s');
            $trtopup->save();

            $newekop = new Trsaldoekop();
            $newekop->Tanggal = date('Y-m-d H:i:s');
            $newekop->KodeUser = $traktifasi->Kode;
            $newekop->Saldo = $saldo;
            if($ekop){
                if($ekop->Saldo < 0){
                    $newekop->SaldoMinus = $saldoawal;
                }
            }
            $newekop->save();


            return response()->json([
                'status' => true,
                'message' => 'success'
            ]);
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
            $id = $request->get('id');
            $traktifasi = Traktifasi::where('Nomor', $id)->where('Status', 'aktif')->first();
            $anggota = Msanggota::where('Kode', $traktifasi->Kode)->first();
            $ekop = Trsaldoekop::where('KodeUser', $traktifasi->Kode)->orderBy('Tanggal', 'DESC')->first();
            if ($ekop) {
                $saldo = $this->rupiah($ekop->Saldo);
                $normalsaldo = $ekop->Saldo;
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
        }
    }

    public function rupiah($expression)
    {
        return number_format($expression, 2, ',', '.');
    }
}
