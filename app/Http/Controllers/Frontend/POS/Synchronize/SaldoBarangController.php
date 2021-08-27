<?php

namespace App\Http\Controllers\Frontend\POS\Synchronize;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Trsaldobarang;
use App\Msbarang;

class SaldoBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view("frontend.pos.synchronize.saldobarang.index");
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

            DB::beginTransaction();
            try {

                $barang = Msbarang::all();
                $kodelokasi = auth('web')->user()->KodeLokasi;

                foreach ($barang as $key => $value) {
                    $saldobarang = Trsaldobarang::on($koneksi)->where('KodeBarang', $value->Kode)->where('KodeLokasi',$kodelokasi)->orderBy('Tanggal','DESC')->first();
                    if($saldobarang){
                        $newsaldo = new Trsaldobarang();
                        $newsaldo->Tanggal = date('Y-m-d H:i:s');
                        $newsaldo->KodeBarang = $value->Kode;
                        $newsaldo->KodeLokasi = $kodelokasi;
                        $newsaldo->Saldo = $saldobarang->Saldo;
                        $newsaldo->save();
                    }
                }

                DB::commit();

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Saldo barang Berhasil di Synchronize',
                        'code' => Response::HTTP_OK,
                    ]
                );
            } catch (\Exception $th) {
                //throw $th;
                DB::rollBack($th);
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
