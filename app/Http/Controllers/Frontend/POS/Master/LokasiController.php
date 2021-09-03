<?php

namespace App\Http\Controllers\Frontend\POS\Master;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mslokasi;


class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lokasi = Mslokasi::all();

        return view("frontend.pos.master.lokasi.index", ['lokasi' => $lokasi]);
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
                'kode' => 'required|unique:mslokasi,Kode',
                'nama' => 'required|unique:mslokasi,Nama',
                'grup' => 'required',
                'status' => 'required',
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
                    $lokasi = new Mslokasi();
                    $lokasi->Kode = strtoupper($request->get('kode'));
                    $lokasi->Nama = strtoupper($request->get('nama'));
                    $lokasi->Grup = strtoupper($request->get('grup'));
                    $lokasi->Status = $request->get('status');
                    $lokasi->UserUpdate = auth('web')->user()->UserLogin;
                    $lokasi->LastUpdate = date('Y-m-d H:i:s');
                    $lokasi->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'success'
                    ]);
                } catch (\Exception $th) {
                    //throw $th;
                    return response()->json($th);
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
            $lokasi = Mslokasi::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'nama' => 'sometimes|required|unique:mslokasi,Nama,' . $lokasi->Nama . ',Nama',
                'grup' => 'required',
                'status' => 'required',
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
                    $lokasi = Mslokasi::findOrFail($id);
                    $lokasi->Nama = strtoupper($request->get('nama'));
                    $lokasi->Grup = strtoupper($request->get('grup'));
                    $lokasi->Status = $request->get('status');
                    $lokasi->UserUpdate = auth('web')->user()->UserLogin;
                    $lokasi->LastUpdate = date('Y-m-d H:i:s');
                    $lokasi->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'success'
                    ]);
                } catch (\Exception $th) {
                    //throw $th;
                    return response()->json($th);
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
