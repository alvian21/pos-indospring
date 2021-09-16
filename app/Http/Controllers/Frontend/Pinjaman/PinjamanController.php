<?php

namespace App\Http\Controllers\Frontend\Pinjaman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Msanggota;
use App\Trpinjaman;
use App\User;
use Illuminate\Support\Facades\DB;

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
        $arr = [];
        foreach ($trpinjaman as $key => $value) {
            $anggota = Msanggota::where('Kode',$value["KodeAnggota"])->first();
            if($anggota){
                $anggota = json_decode(json_encode($anggota),true);
                $arr[] = array_merge($anggota,$value);
            }else{
                $x["Nama"] = "";
                $arr[] = array_merge($value,$x);
            }
        }
        $trpinjaman = json_decode(json_encode($arr), FALSE);
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
        $user =  auth()->user();
        $level = auth()->user()->LevelApprovalPengajuan;
        $msanggota = Msanggota::find($user->KodeAnggota);
        $nama = $msanggota->Nama;

        if ($level != 0) {
            $update = Trpinjaman::where("Nomor", $id);
            if ($level == 1) {
                if ($request->get('status') == 1) {
                    $status = "VERIFIKASI";
                    $petugasNote = null;
                } elseif ($request->get('status') == 0) {
                    $status = "TDK VERIFIKASI";
                    $petugasNote = $request->get("note");
                }
                $update = $update->update([
                    'ApprovalStatus' => $status,
                    'PetugasNama' => $nama,
                    'PetugasDate' => date('Y-m-d H:i'),
                    'PetugasNote' => $petugasNote
                ]);
            } elseif ($level == 2) {
                if ($request->get('status') == 1) {
                    $status = "DIPROSES";
                    $diketahuiNote = null;
                } elseif ($request->get('status') == 0) {
                    $status = "TDK DIPROSES";
                    $diketahuiNote = $request->get("note");
                }
                $update = $update->update([
                    'ApprovalStatus' => $status,
                    'DiketahuiNama' => $nama,
                    'DiketahuiNote' => $diketahuiNote,
                    'DiketahuiDate' => date('Y-m-d H:i')
                ]);
            } elseif ($level == 3) {
                if ($request->get('status') == 1) {
                    $status = "DISETUJUI";
                    $note = null;
                } elseif ($request->get('status') == 0) {
                    $status = "TDK DISETUJUI";
                    $note = $request->get("note");
                }
                $update = $update->update([
                    'ApprovalStatus' => $status,
                    'ApprovalNote' => $note,
                    'ApprovalNama' => $nama,
                    'ApprovalDate' => date('Y-m-d H:i')
                ]);
            }
            return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil di update');
        } else {
            return response()->json([
                'message' => 'you dont have level to update this data'
            ]);
        }
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
