<?php

namespace App\Http\Controllers\Frontend\POS\Laporan;

use App\Exports\RealtimeStok;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mskategori;
use App\Mslokasi;
use Illuminate\Support\Facades\DB;
use App\Trsaldobarang;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class RealtimeStokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Mskategori::all()->where('Kode', '!=', '01')->where('Kode', '!=', null);
        return view("frontend.pos.laporan.realtimestok.index", ['kategori' => $kategori]);
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
        $kategori = $request->get('kategori');
        if ($kategori == 'semua') {
            $data = DB::table('mskategori')->leftJoin('msbarang', 'mskategori.Kode', 'msbarang.KodeKategori')->select('msbarang.Nama as NamaBarang', 'msbarang.Kode as KodeBarang', 'msbarang.*', 'mskategori.*', 'mskategori.Nama as Kategori')->get();
        } else {
            $data = DB::table('mskategori')->leftJoin('msbarang', 'mskategori.Kode', 'msbarang.KodeKategori')->select('msbarang.Nama as NamaBarang', 'msbarang.Kode as KodeBarang', 'msbarang.*', 'mskategori.*', 'mskategori.Nama as Kategori')->where('KodeKategori', $kategori)->get();
        }

        $arr = [];
        foreach ($data as $key => $value) {

            $p1 = TrSaldoBarang::where('KodeLokasi', 'P1')->where('KodeBarang', $value->KodeBarang)->orderBy('Tanggal', 'DESC')->first();
            $p2 = TrSaldoBarang::where('KodeLokasi', 'P2')->where('KodeBarang', $value->KodeBarang)->orderBy('Tanggal', 'DESC')->first();
            if ($p1) {
                $x['P1'] = $p1->Saldo;
            } else {
                $x['P1'] = 0;
            }

            if ($p2) {
                $x['P2'] = $p2->Saldo;
            } else {
                $x['P2'] = 0;
            }


            $res = array_merge($x, (array) $value);
            array_push($arr, $res);
        }
        $sort = array();
        foreach ($arr as $k => $v) {
            $sort['Kategori'][$k] = $v['Kategori'];
        }
        array_multisort($sort['Kategori'], SORT_DESC, $arr);
        $no = 1;
        $datares = [];
        foreach ($arr as $key => $value) {
            if (isset($arr[$key + 1])) {
                if ($value["Kategori"] == $arr[$key + 1]["Kategori"]) {
                    $no++;
                    $x['NomorGroup'] = $no;
                    $res = array_merge($x, $value);
                    array_push($datares, $res);
                } else {
                    $no = 1;
                    $x['NomorGroup'] = $no;
                    $res = array_merge($x, $value);
                    array_push($datares, $res);
                }
            } else {
                $no = 1;
                $x['NomorGroup'] = $no;
                $res = array_merge($x, $value);
                array_push($datares, $res);
            }
        }

        $sort = array();
        foreach ($datares as $k => $v) {
            $sort['Kategori'][$k] = $v['Kategori'];
            $sort['NomorGroup'][$k] = $v['NomorGroup'];
        }

        array_multisort($sort['Kategori'], SORT_DESC, $sort['NomorGroup'], SORT_ASC, $datares);


        $reslast = [];
        $no = 1;
        foreach ($datares as $key => $val) {
            $x['No'] = $no;
            $x['Kategori'] = $val['Kategori'];
            $x['NomorGroup'] = $val['NomorGroup'];
            $x['KodeBarang'] = $val['KodeBarang'];
            $x['NamaBarang'] = $val['NamaBarang'];
            $x['P1'] = $val['P1'];
            $x['P2'] = $val['P2'];
            array_push($reslast,$x);
            $no ++;
        }

        if ($request->get('cetak') == 'pdf') {

            $pdf = PDF::loadview(
                "frontend.pos.laporan.realtimestok.pdf",
                [
                    'data' => $reslast
                ]
            )->setPaper('a4', 'potrait');
            return $pdf->stream('laporan-realtime-pdf', array('Attachment' => 0));
        } else {
            $data = collect($reslast);
            return    Excel::download(new RealtimeStok($data), 'laporan-realtimestok.xlsx');
        }


    }
}
