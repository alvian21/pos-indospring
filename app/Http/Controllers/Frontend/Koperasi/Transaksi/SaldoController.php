<?php

namespace App\Http\Controllers\Frontend\Koperasi\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Msanggota;
use App\Traktifasi;
use App\Trsaldoekop;

class SaldoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $traktifasi = Traktifasi::all()->where('Status', 'aktif');
        return view("frontend.koperasi.transaksi.cek_saldo.index", ['traktifasi' => $traktifasi]);
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
        //
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
            $traktifasi = Traktifasi::findOrFail($id);
            $anggota = Msanggota::where('Kode', $traktifasi->Kode)->first();
            $ekop = Trsaldoekop::where('KodeUser', $traktifasi->Kode)->orderBy('Tanggal', 'DESC')->first();
            if ($ekop) {
                $saldo = $this->rupiah($ekop->Saldo);
            } else {
                $saldo = $this->rupiah(0);
            }
            $data = [
                'kode' => $anggota->Kode,
                'nama' => $anggota->Nama,
                'saldo' => $saldo
            ];

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        }
    }

    public function rupiah($expression)
    {
       return "Rp ". number_format($expression, 2, ',', '.');
    }
}
