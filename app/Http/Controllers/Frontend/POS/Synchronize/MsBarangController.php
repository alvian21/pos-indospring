<?php

namespace App\Http\Controllers\Frontend\POS\Synchronize;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Msbarang;
use App\Trsaldobarang;
use Illuminate\Http\Response;

class MsBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.pos.synchronize.msbarang.index");
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

        if ($request->ajax()) {
            DB::beginTransaction();
            try {

                $cloudbarang = DB::connection('mysql2')->table('msbarang')->get();
                $msbarang = Msbarang::all();
                foreach ($cloudbarang as $key => $value) {

                    $updateOrCreate  = [
                        'Kode' => $value->Kode,
                        'KodeBarcode' => $value->KodeBarcode,
                        'Nama' => $value->Nama,
                        'HargaJual' => $value->HargaJual,
                        'KodeKategori' => $value->KodeKategori,
                        'UserUpdate' => $value->UserUpdate,
                        'LastUpdate' => $value->LastUpdate,
                        'LokasiGambar' => $value->LokasiGambar,
                        'Satuan' => $value->Satuan,
                        'TampilDiMobile' => $value->TampilDiMobile,
                        'MinimumStok' => $value->MinimumStok,
                        'HargaCaffe' => $value->HargaCaffe,
                        'TampilDiCaffe' => $value->TampilDiCaffe,
                    ];

                    $updatelokal = Msbarang::updateOrCreate($updateOrCreate);
                }

                DB::commit();

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Master Barang Berhasil di Synchronize',
                        'code' => Response::HTTP_OK
                    ]
                );
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(
                    [
                        'status' => false,
                        'message' => $e,
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
