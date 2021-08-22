<?php

namespace App\Http\Controllers\Frontend\POS\Synchronize;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Mssetting;
use App\Traktifasi;


class AktivasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.pos.synchronize.aktivasi.index");
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
        if($request->ajax()){
            $koneksi = 'mysql2';
            DB::beginTransaction();
            try {
                $traktifasicloud = Traktifasi::on($koneksi)->get();

                foreach ($traktifasicloud as $key => $value) {
                    $lokalraktifasi = new Traktifasi();
                    $lokalraktifasi->Nomor = $value->Nomor;
                    $lokalraktifasi->Tanggal = $value->Tanggal;
                    $lokalraktifasi->Kode = $value->Kode;
                    $lokalraktifasi->NoEkop = $value->NoEkop;
                    $lokalraktifasi->Status = $value->Status;
                    $lokalraktifasi->UserUpdate = $value->UserUpdate;
                    $lokalraktifasi->save();
                }

                DB::commit();
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Aktivasi e-kop Berhasil di Synchronize',
                        'code' => Response::HTTP_OK,
                    ]
                );

            } catch (\Exception $th) {
                    //throw $th;
                    DB::rollBack();

                    return response()->json(
                        [
                            'status' => false,
                            'message' => $th,
                            'code' => Response::HTTP_BAD_REQUEST
                        ]
                    );
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
