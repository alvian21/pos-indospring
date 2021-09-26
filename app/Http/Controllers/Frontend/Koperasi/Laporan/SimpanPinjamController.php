<?php

namespace App\Http\Controllers\Frontend\Koperasi\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Trtransaksiperiode;
use App\Exports\SimpanPinjam;
use App\Trpinjaman;
use PDF;

class SimpanPinjamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view("frontend.koperasi.laporan.simpanpinjam.index");
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
            'status' => 'required',
            'cetak' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {

            $periode = $request->get('periode');
            $cetak = $request->get('cetak');
            $bulan = date('m', strtotime($periode));
            $tahun = date('Y', strtotime($periode));
            $periode = date(" F  Y", strtotime($periode));
            $status = $request->get('status');
            if($status == 'Semua Status'){
                $data = Trpinjaman::join('msanggota', 'trpinjaman.KodeAnggota', 'msanggota.Kode')->whereMonth('TanggalPotongan', $bulan)->whereYear('TanggalPotongan', $tahun)->whereNotNull('Pinjaman')->orderBy('Pinjaman','DESC')->get();
            }else{
                $data = Trpinjaman::join('msanggota', 'trpinjaman.KodeAnggota', 'msanggota.Kode')->whereMonth('TanggalPotongan', $bulan)->whereYear('TanggalPotongan', $tahun)->whereNotNull('Pinjaman')->where('ApprovalStatus',$status)->orderBy('Pinjaman','DESC')->get();
            }
            if ($cetak == 'pdf') {
                // $group = Trtransaksiperiode::select('SubDept')->join('msanggota', 'trsaldoreset.KodeUser', 'msanggota.Kode')->where('SaldoBelanjaKredit', '>', 0)->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->groupBy('SubDept')->get();

                // $arr = [];

                // foreach ($group as $key => $value) {
                //     $x['SubDept'] = $value->SubDept;
                //     $saldoreset = Trsaldoreset::join('msanggota', 'trsaldoreset.KodeUser', 'msanggota.Kode')->where('SaldoBelanjaKredit', '>', 0)->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('SubDept', $value->SubDept)->orderBy('SubDept')->orderBy('Kode')->get();
                //     $total = Trsaldoreset::join('msanggota', 'trsaldoreset.KodeUser', 'msanggota.Kode')->where('SaldoBelanjaKredit', '>', 0)->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('SubDept', $value->SubDept)->groupBy('SubDept')->sum('SaldoBelanjaKredit');
                //     $x['data'] = $saldoreset;
                //     $x['total'] = $total;
                //     array_push($arr, $x);
                // }

                // $pdf = PDF::loadview(
                //     "frontend.koperasi.laporan.tagihankredit.pdf",
                //     [
                //         'data' => $arr, 'periode' => $periode
                //     ]
                // )->setPaper('a4', 'potrait');
                // return $pdf->stream('laporan-tagihankredit-pdf', array('Attachment' => 0));
            }else{

                if(isset($data[0])){
                    $data = json_decode(json_encode($data),true);
                    $data = collect($data);
                    $lastdate = date("Y-m-t", strtotime($periode));

                    return Excel::download(new SimpanPinjam($data, $periode,$lastdate), 'laporan-simpan-pinjam.xlsx');
                }else{
                    return redirect()->back()->withErrors(['maaf data pada periode '.$periode.' masih kosong']);
                }

            }
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
