<?php

namespace App\Http\Controllers\Frontend\POS\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trsaldobarang;
use App\Trmutasidt;
use App\Msbarang;
use PDF;

class TraceStokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $barang = Msbarang::all();
        return view("frontend.pos.laporan.tracestok.index", ['barang' => $barang]);
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

    public function cetak(Request $request)
    {
        $barang = $request->get('barang');
        $lokasi = auth()->user()->KodeLokasi;
        $trsaldobarang = Trsaldobarang::where('KodeBarang', $barang)->where('KodeLokasi', $lokasi)->OrderBy('Tanggal')->get();
        $trmutasidt = Trmutasidt::where('KodeBarang', $barang)->OrderBy('LastUpdate')->get();

      
        $arr = [];
        foreach ($trsaldobarang as $key => $value) {
            $tanggal = date('Y-m-d', strtotime($value->Tanggal));
            $trmutasidt = Trmutasidt::where('KodeBarang', $barang)->whereDate('LastUpdate', $tanggal)->first();

            if ($trmutasidt) {
                if ($trmutasidt->Transaksi == "OPNAME") {
                    $x['Saldo'] = $value->Saldo;
                    $x['Masuk'] = null;
                    $x['Keluar'] = null;
                } elseif ($trmutasidt->Transaksi == "PENJUALAN" || $trmutasidt->Transaksi == "RUSAK HILANG") {
                    $x['Saldo'] = $value->Saldo;
                    $x['Masuk'] = null;
                    $x['Keluar'] = $trmutasidt->Jumlah;
                } elseif ($trmutasidt->Transaksi == "PEMBELIAN") {
                    $x['Saldo'] = null;
                    $x['Masuk'] = $trmutasidt->Jumlah;
                    $x['Keluar'] = null;
                }
                $x["Transaksi"]= $trmutasidt->Transaksi;
                $x["Nomor"]= $trmutasidt->Nomor;
                $x["UserUpdate"]= $trmutasidt->UserUpdate;
                $x["LastUpdate"]= $trmutasidt->LastUpdate;
                array_push($arr, $x);

            }
        }


        $pdf = PDF::loadview(
            "frontend.pos.laporan.tracestok.pdf",
            [
                'data' => $arr
            ]
        )->setPaper('a4', 'potrait');
        return $pdf->stream('laporan-tracestok-pdf', array('Attachment' => 0));
    }
}
