<?php

namespace App\Http\Controllers\Frontend\Koperasi\Transaksi;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Imports\SHUImport;
use App\Trshu;

class SHUController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tahun = [];
        if ($request->ajax()) {

            if ($request->has('periode') && $request->get('periode') != 'Show All') {
                $shu = Trshu::where('periode', $request->get('periode'))->get();
            } else {
                $shu = Trshu::all();
            }

            return Datatables::of($shu)
                ->addIndexColumn()
                ->editColumn('nilai_poin', function ($data) {
                    return $this->rupiah($data->nilai_poin);
                })
                ->editColumn('total_kontribusi', function ($data) {
                    return $this->rupiah($data->total_kontribusi);
                })
                ->make(true);
        }
        for ($i = 2019; $i <= date('Y'); $i++) {
            array_push($tahun, $i);
        }
        return view('frontend.koperasi.transaksi.shu.index', ['tahun' => $tahun]);
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

    public function SHUImport(Request $request)
    {
        if ($request->file('file')) {
            Excel::import(new SHUImport, $request->file('file'));
            return back()->with('success', 'Data has been imported');
        } else {
            return back()->with('error', 'Please input the file');
        }
    }

    public function hapus_shu(Request $request)
    {
        $periode = $request->get('periode');
        Trshu::where('periode', $periode)->delete();
        return redirect()->back()->with('success', 'Data has been deleted');
    }

    public function rupiah($data)
    {
        $format = "Rp. " . number_format($data, 2, ',', '.');
        $format = str_replace(',00', '', $format);
        return $format;
    }
}
