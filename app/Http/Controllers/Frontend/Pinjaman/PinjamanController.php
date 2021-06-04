<?php

namespace App\Http\Controllers\Frontend\Pinjaman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Msanggota;
use App\Trpinjaman;
use App\User;

class PinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $level = auth()->user()->LevelApprovalPengajuan;
        if ($level != 0) {
            if ($level == 1) {
                $trpinjaman = Trpinjaman::where(function ($query) {
                    $query->OrWhere("ApprovalStatus", "PENGAJUAN")
                        ->OrWhere("ApprovalStatus", "VERIFIKASI")
                        ->OrWhere("ApprovalStatus", "TDK VERIFIKASI");
                })->get();
            } elseif ($level == 2) {
                $trpinjaman = Trpinjaman::where(function ($query) {
                    $query->OrWhere("ApprovalStatus", "VERIFIKASI")
                        ->OrWhere("ApprovalStatus", "DIPROSES")
                        ->OrWhere("ApprovalStatus", "TDK DIPROSES");
                })->get();
            } elseif ($level == 3) {
                $trpinjaman = Trpinjaman::where(function ($query) {
                    $query->OrWhere("ApprovalStatus", "DIPROSES")
                        ->OrWhere("ApprovalStatus", "DISETUJUI")
                        ->OrWhere("ApprovalStatus", "TDK DISETUJUI");
                })->get();
            }
        }
        $trpinjaman = Trpinjaman::InfoPinjamanFrontend($trpinjaman);
        $trpinjaman = json_decode(json_encode($trpinjaman), FALSE);

        return view('frontend.dashboard.pinjaman.index', ['trpinjaman' => $trpinjaman]);
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
}