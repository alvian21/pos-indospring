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
            $koneksi = 'mysql2';
            $totalbarang = DB::connection($koneksi)->table('msbarang')->count();
            $cloudbarang = DB::connection($koneksi)->table('msbarang')->get();
            $msbarang = Msbarang::all();
            session(['totalbarang' => $totalbarang]);
            $total = 0;
            DB::beginTransaction();
            DB::connection($koneksi)->beginTransaction();
            try {

                if ($request->get('status') == 'progress') {
                    if (session()->has('totalpersen')) {
                        $total = session('totalpersen');
                    } else {
                        $total = 0;
                    }


                    $persen = ($total / $totalbarang) * 100;
                    $persen = round($persen);
                    $html = '<div class="progress-bar" role="progressbar" style="width: ' . $persen . '%;" aria-valuenow="' . $persen . '"
                    aria-valuemin="0" aria-valuemax="100">' . $persen . '%</div>';

                    if ($persen >= 100) {
                        $pesan = 'selesai';
                    } else {
                        $pesan = 'belum';
                    }

                    return response()->json([
                        'status' => true,
                        'data' => $html

                    ]);
                } else {
                    foreach ($cloudbarang as $key => $value) {
                        $total += 1;
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
                        session()->forget('totalpersen');
                        session(['totalpersen' => $total]);
                        session()->save();
                        $updatelokal = Msbarang::updateOrCreate($updateOrCreate);
                    }
                    session()->save();


                    DB::commit();
                    DB::connection($koneksi)->commit();

                    return response()->json(
                        [
                            'status' => true,
                            'message' => 'Master Barang Berhasil di Synchronize',
                            'code' => Response::HTTP_OK
                        ]
                    );
                }
            } catch (\Exception $e) {
                DB::rollBack();
                DB::connection($koneksi)->rollBack();
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
