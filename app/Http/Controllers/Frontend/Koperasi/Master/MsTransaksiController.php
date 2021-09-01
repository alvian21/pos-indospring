<?php

namespace App\Http\Controllers\Frontend\Koperasi\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mstransaksi;

class MsTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaksi = Mstransaksi::all();
        return view("frontend.koperasi.master.transaksi.index", ['transaksi' => $transaksi]);
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
            $validator = Validator::make($request->all(), [
                'kode' => 'required|unique:mstransaksi,Kode',
                'nama' => 'required',
                'cara_bayar' => 'required',
                'aktif' => 'required',
            ]);
            if ($validator->fails()) {
                $error = '<div class="alert alert-danger" role="alert">
            ' . $validator->errors()->first() . '
           </div>';
                return response()->json([
                    'status' => false,
                    'data' => $error
                ]);
            } else {
                try {
                    $trans = new Mstransaksi();
                    $trans->Kode = $request->get('kode');
                    $trans->Nama = $request->get('nama');
                    $trans->CaraBayar = $request->get('cara_bayar');
                    if ($request->get('aktif') == 'Ya') {
                        $aktif = 1;
                    } else {
                        $aktif = 0;
                    }
                    $trans->Aktif = $aktif;
                    $trans->UserUpdate = auth('web')->user()->UserLogin;
                    $trans->LastUpdate = date('Y-m-d H:i:s');
                    $trans->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'success'
                    ]);
                } catch (\Exception $th) {
                    //throw $th;
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
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'cara_bayar' => 'required',
                'aktif' => 'required',
            ]);
            if ($validator->fails()) {
                $error = '<div class="alert alert-danger" role="alert">
            ' . $validator->errors()->first() . '
           </div>';
                return response()->json([
                    'status' => false,
                    'data' => $error
                ]);
            } else {
                try {
                    $trans =  Mstransaksi::findOrFail($id);
                    $trans->Nama = $request->get('nama');
                    $trans->CaraBayar = $request->get('cara_bayar');
                    if ($request->get('aktif') == 'Ya') {
                        $aktif = 1;
                    } else {
                        $aktif = 0;
                    }
                    $trans->Aktif = $aktif;
                    $trans->UserUpdate = auth('web')->user()->UserLogin;
                    $trans->LastUpdate = date('Y-m-d H:i:s');
                    $trans->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'success'
                    ]);
                } catch (\Exception $th) {
                    //throw $th;
                }
            }
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
