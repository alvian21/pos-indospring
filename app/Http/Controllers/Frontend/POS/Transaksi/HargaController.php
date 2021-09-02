<?php

namespace App\Http\Controllers\Frontend\POS\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Msbarang;
use App\Trsaldobarang;

class HargaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $barang = Msbarang::all();

        if ($request->ajax()) {
            $lokasi = auth('web')->user()->KodeLokasi;
            $cari = Msbarang::findOrFail($request->get('barang'));
            $stok = Trsaldobarang::where('KodeBarang', $request->get('barang'))->where('KodeLokasi', $lokasi)->orderBy('Tanggal', 'DESC')->first();

            $saldo = 0;
            if ($stok) {
                $saldo = $stok->Saldo;
            }

            $data = [
                'stok' => $saldo,
                'harga' => $this->rupiah($cari->HargaJual)
            ];

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        }
        return view("frontend.pos.transaksi.harga.index", ['barang' => $barang]);
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

    public function rupiah($expression)
    {
        return "Rp " . number_format($expression, 2, ',', '.');
    }
}
