<?php

namespace App\Http\Controllers\Frontend\POS\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mskategori;
use App\Mslokasi;
use App\Trsaldobarang;
use App\Msbarang;
use Illuminate\Support\Facades\DB;

class MinimumStokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lokasi = Mslokasi::all();
        return view("frontend.pos.laporan.minimumstok.index",['lokasi'=>$lokasi]);
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

        $lokasi = $request->get('lokasi');
        $data = DB::table('msbarang')->leftJoin('mskategori','mskategori.Kode','msbarang.KodeKategori')->leftJoin('trsaldobarang','msbarang.Kode','trsaldobarang.KodeBarang')->where('KodeLokasi',$lokasi)->orderBy('mskategori.Nama','DESC')->get();
    }
}
