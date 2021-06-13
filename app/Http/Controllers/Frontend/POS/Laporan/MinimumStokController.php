<?php

namespace App\Http\Controllers\Frontend\POS\Laporan;

use App\Exports\MinimumStokExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mskategori;
use App\Mslokasi;
use App\Trsaldobarang;
use App\Msbarang;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

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
        return view("frontend.pos.laporan.minimumstok.index", ['lokasi' => $lokasi]);
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
        $data = DB::table('msbarang')->select('msbarang.*', 'mskategori.Nama as Kategori')->leftJoin('mskategori', 'mskategori.Kode', 'msbarang.KodeKategori')->orderBy('mskategori.Nama', 'DESC')->get()->toArray();
        $arr = [];
        foreach ($data as $key => $value) {
            $trsaldobarang = Trsaldobarang::where('KodeBarang', $value->Kode)->where('KodeLokasi', $lokasi)->orderBy('Tanggal', 'DESC')->first();
            if ($trsaldobarang) {
                if ($value->MinimumStok >= $trsaldobarang->Saldo) {
                    $x['Saldo'] = $trsaldobarang->Saldo;
                    $res = array_merge($x, (array) $value);
                    array_push($arr, $res);
                }
            }
        }
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
            $sort['Saldo'][$k] = $v['Saldo'];
        }

        array_multisort($sort['Kategori'], SORT_DESC, $sort['NomorGroup'], SORT_ASC, $sort['Saldo'], SORT_DESC, $datares);

        $reslast = [];
        $no = 1;
        foreach ($datares as $key => $val) {
            $x['No'] = $no;
            $x['Kategori'] = $val['Kategori'];
            $x['NomorGroup'] = $val['NomorGroup'];
            $x['Kode'] = $val['Kode'];
            $x['Nama'] = $val['Nama'];
            $x['MinimumStok'] = $val['MinimumStok'];
            $x['Saldo'] = $val['Saldo'];
            array_push($reslast,$x);
            $no ++;
        }
        if ($request->get('cetak') == 'pdf') {

            $pdf = PDF::loadview(
                "frontend.pos.laporan.minimumstok.pdf",
                [
                    'minimumstok' => $reslast
                ]
            )->setPaper('a3', 'landscape');
            return $pdf->stream('laporan-minimumstok-pdf',array('Attachment' => 0));
        } else {
            $data = collect($reslast);
            return    Excel::download(new MinimumStokExport($data), 'laporan-minimumstok.xlsx');
        }
    }
}
