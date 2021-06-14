<?php

namespace App\Http\Controllers\Frontend\POS\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mslokasi;
use App\Trmutasidt;
use App\Trmutasihd;
use PDF;
use Illuminate\Support\Facades\DB;

class OpnameHilangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mslokasi = Mslokasi::all();

        return view("frontend.pos.laporan.opnamehilang.index", ['mslokasi' => $mslokasi]);
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

    public function cetakDetail(Request $request)
    {
        $periode1 = $request->get('periode1');
        $periode2 = $request->get('periode2');
        $status = $request->get('transaksi');
        $lokasi = $request->get('lokasi');
        $trmutasidt = DB::table('trmutasidt')->leftJoin('msbarang', 'trmutasidt.KodeBarang', 'msbarang.Kode')->select('trmutasidt.*','msbarang.*','trmutasidt.UserUpdate as UpdateUser')->where('Transaksi', $status)->orderBy('Nomor')->get();
        $arr = [];
        foreach ($trmutasidt as $key => $value) {
            $trmutasihd = DB::table('trmutasihd')->where('Nomor', $value->Nomor)->where('LokasiAwal', $lokasi)->where('trmutasihd.Transaksi', $status)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->orderBy('Nomor')->first();
            if ($trmutasihd) {
                $x['Tanggal'] = $trmutasihd->Tanggal;
                $data = array_merge($x, (array) $value);
                array_push($arr, $data);
            }
        }

        if($status=='OPNAME'){
            $resstatus = 'Laporan Stok Opname';
        }else{
            $resstatus = 'Laporan Stok Hilang / Rusak';
        }

        $periode1 = date("l, F j, Y", strtotime($periode1));
        $periode2 = date("l, F j, Y", strtotime($periode2));

        $pdf = PDF::loadview(
            "frontend.pos.laporan.opnamehilang.pdf",
            ['data' => $arr, 'periode1' => $periode1, 'periode2' => $periode2, 'status' => $resstatus]
        )->setPaper('a4', 'potrait');
        return $pdf->stream('laporan-opnamehilang-pdf', array('Attachment' => 0));

    }
}
