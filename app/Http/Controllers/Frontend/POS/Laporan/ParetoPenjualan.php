<?php

namespace App\Http\Controllers\Frontend\POS\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mslokasi;
use App\Msanggota;
use App\Trmutasihd;
use Maatwebsite\Excel\Facades\Excel;
use App\Trmutasidt;
use Illuminate\Support\Facades\DB;
use App\Msbarang;
use App\Trsaldobarang;
use PDF;

class ParetoPenjualan extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mslokasi = Mslokasi::all();
        $msanggota = Msanggota::all();
        $smbarang = Msbarang::all();
        return view("frontend.pos.laporan.paretopenjualan.index", ['mslokasi' => $mslokasi, 'msanggota' => $msanggota, 'msbarang' => $smbarang]);
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

    public function cetakPdf(Request $request)
    {
        $periode1 = $request->get('periode1');
        $periode2 = $request->get('periode2');
        $jumlah = $request->get('jumlah');
        if ($request->get('transaksi') == 'online') {
            $status = 'CHECKOUT';
        } elseif ($request->get('transaksi') == 'offline') {
            $status = 'PENJUALAN';
        }
        $lokasi = $request->get('lokasi');

        if ($request->get('transaksi') == 'semua') {
            $trmutasihd =  Trmutasihd::select('KodeBarang', DB::raw('count(*) as Total'))->join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where(function ($query) {
                $query->where('trmutasihd.Transaksi', 'PENJUALAN')
                    ->orWhere('trmutasihd.Transaksi', 'CHECKOUT');
            })->orderBy('Total', 'DESC')->groupBy('KodeBarang')->get();
        } else {
            $trmutasihd =  Trmutasihd::select('KodeBarang', DB::raw('count(*) as Total'))->join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('trmutasihd.Transaksi', $status)->orderBy('Total', 'DESC')->groupBy('KodeBarang')->get();
        }


        $arr = [];


        foreach ($trmutasihd as $key => $value) {
            $barang = Msbarang::select('mskategori.*', 'msbarang.*','mskategori.Nama as Kategori')->join('mskategori', 'msbarang.KodeKategori', 'mskategori.Kode')->where('msbarang.Kode', $value->KodeBarang)->first()->toArray();
            $value = json_decode(json_encode($value), true);
            $res = array_merge($value, $barang);
            array_push($arr, $res);
        }

        // $no = 1;
        $count = array_count_values(array_column($arr, "KodeKategori"));
        $lastres = [];
        foreach ($count as $kode => $value) {
            $no = 1;
            foreach ($arr as $key => $row) {
               if($kode == $row['KodeKategori']){
                    $x['NomorGroup'] = $no;
                    $no++;
                    $data = array_merge($x, $row);
                    array_push($lastres, $data);
               }
            }
        }

        $cekjumlah = [];
        for ($i=0; $i < $jumlah ; $i++) {
            if(isset($lastres[$i])){
                array_push($cekjumlah, $lastres[$i]);
            }

        }

        $periode1 = date("l, F j, Y", strtotime($periode1));
        $periode2 = date("l, F j, Y", strtotime($periode2));

        if ($request->get('cetak') == 'pdf') {
            $pdf = PDF::loadview(
                "frontend.pos.laporan.paretopenjualan.pdf",
                [
                    'laporan' => $cekjumlah, 'periode1' => $periode1, 'periode2' => $periode2,
                ]
            )->setPaper('a4', 'potrait');
            return $pdf->stream('laporan-paretopenjualan-pdf', array('Attachment' => 0));
        } else {
            $penjualan = collect($arr);



            // return Excel::download(new PenjualanExport($penjualan, $sumdiskon, $sumpajak, $sumtotal, $sumtunai, $sumkredit, $sumekop), 'laporan-penjualan.xlsx');
        }
    }


}
