<?php

namespace App\Http\Controllers\Frontend\POS\Master;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mskategori;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Mskategori::all()->whereNotNull('Kode');
        $kode = Mskategori::max('Kode');
        if (empty($kode)) {
            $kode = "01";
        } else {
            $kode = $kode + 1;
        }
        return view("frontend.pos.master.kategori.index", ['kategori' => $kategori, 'kode' => $kode]);
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
                'nama' => 'required|unique:mskategori,Nama',
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
                    $kode = Mskategori::max('Kode');
                    if (empty($kode)) {
                        $kode = "01";
                    } else {
                        $kode = $kode + 1;
                    }
                    $kategori = new Mskategori();
                    $kategori->Kode = $kode;
                    $kategori->Nama = $request->get('nama');
                    $kategori->save();

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
            $kategori = Mskategori::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'nama' => 'required|unique:mskategori,Nama,'.$kategori->Nama.',Nama',
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
                    $kategori = Mskategori::findOrFail($id);
                    $kategori->Nama = $request->get('nama');
                    $kategori->save();

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
