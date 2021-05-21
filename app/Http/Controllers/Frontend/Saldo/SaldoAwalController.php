<?php

namespace App\Http\Controllers\Frontend\Saldo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\CsvImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Trsaldohutang;
use App\Trsaldosimpanan;
use App\Trtransaksiperiode;
use App\Trpinjaman;

class SaldoAwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trsaldohutang = Trsaldohutang::all();
        $trsaldosimpanan = Trsaldosimpanan::all();
        $trtransaksiperiode = Trtransaksiperiode::all();
        $trpinjaman = Trpinjaman::all();
        return view("frontend.saldo.saldo_awal.index", [
            'trsaldohutang' => $trsaldohutang,
            'trsaldosimpanan' => $trsaldosimpanan, 'trtransaksiperiode' => $trtransaksiperiode,
            'trpinjaman' => $trpinjaman
        ]);
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

    public function member_import(Request $request)
    {
        if ($request->file('file')) {
            Excel::import(new CsvImport, $request->file('file'));
            return back()->with('success', 'Data has been imported');
        } else {
            return back()->with('error', 'Please input the file');
        }
    }
}
