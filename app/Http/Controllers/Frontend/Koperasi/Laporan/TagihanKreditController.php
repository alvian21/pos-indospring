<?php

namespace App\Http\Controllers\Frontend\Koperasi\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Trsaldoreset;
use App\Msanggota;
use PDF;


class TagihanKreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.koperasi.laporan.tagihankredit.index");
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
            'periode' => 'required',
            'cetak' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $periode = $request->get('periode');
            $bulan = date('m', strtotime($periode));
            $tahun = date('Y', strtotime($periode));


            $group = Trsaldoreset::select('SubDept')->join('msanggota', 'trsaldoreset.KodeUser', 'msanggota.Kode')->where('SaldoBelanjaKredit', '>', 0)->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->groupBy('SubDept')->get();

            $arr = [];

            foreach ($group as $key => $value) {
                $x['SubDept'] = $value->SubDept;
                $saldoreset = Trsaldoreset::join('msanggota', 'trsaldoreset.KodeUser', 'msanggota.Kode')->where('SaldoBelanjaKredit', '>', 0)->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('SubDept',$value->SubDept)->orderBy('SubDept')->orderBy('Kode')->get();
                $total = Trsaldoreset::join('msanggota', 'trsaldoreset.KodeUser', 'msanggota.Kode')->where('SaldoBelanjaKredit', '>', 0)->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('SubDept',$value->SubDept)->groupBy('SubDept')->sum('SaldoBelanjaKredit');
                $x['data'] = $saldoreset;
                $x['total'] = $total;
                array_push($arr, $x);
            }
            $periode = date(" F  Y", strtotime($periode));

            $pdf = PDF::loadview(
                "frontend.koperasi.laporan.tagihankredit.pdf",
                [
                    'data' => $arr, 'periode' => $periode
                ]
            )->setPaper('a4', 'potrait');
            return $pdf->stream('laporan-tagihankredit-pdf', array('Attachment' => 0));
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
}
