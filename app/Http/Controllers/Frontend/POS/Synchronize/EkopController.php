<?php

namespace App\Http\Controllers\Frontend\POS\Synchronize;

use App\Http\Controllers\Controller;
use App\Msanggota;
use Illuminate\Http\Request;
use App\Trsaldoekop;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class EkopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.pos.synchronize.ekop.index");
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
                $anggota = Msanggota::all();
                foreach ($anggota as $key => $value) {
                    $cloudekop = Trsaldoekop::on($koneksi)->where('KodeUser', $value->Kode)->orderBy('Tanggal', 'DESC')->first();
                    if ($cloudekop) {
                        $newekop = new Trsaldoekop();
                        $newekop->Tanggal = $cloudekop->Tanggal;
                        $newekop->KodeUser = $value->Kode;
                        $newekop->Saldo = $cloudekop->Saldo;
                        $newekop->SaldoMinus = $cloudekop->SaldoMinus;
                        $newekop->save();
                    }
                }

                DB::commit();

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'E-kop Berhasil di Synchronize',
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
