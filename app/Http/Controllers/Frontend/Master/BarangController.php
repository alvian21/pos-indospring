<?php

namespace App\Http\Controllers\Frontend\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Msbarang;
use App\Mskategori;
use Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $msbarang = Msbarang::all();
        $mskategori = Mskategori::all();
       return view("frontend.master.barang.index",["msbarang" => $msbarang, "mskategori" => $mskategori]);
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
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'kode_barcode' => 'nullable',
            'kategori' => 'required',
            'tampildimobile' => 'required',
            'nama'=> 'required',
            'harga'=> 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
           if($request->get("status") == "update"){
            $msbarang = Msbarang::find($request->get('kode'));
           }elseif($request->get("status") == "store"){
            $msbarang = new Msbarang();
            $msbarang->Kode = $request->get("kode");
            $msbarang->Nama = $request->get("nama");

           }
            $msbarang->HargaJual = $request->get('harga');
            $msbarang->KodeBarcode = $request->get('kode_barcode');
            $msbarang->KodeKategori = $request->get('kategori');
            $msbarang->TampilDiMobile = $request->get('tampildimobile');

            // if ($request->hasFile("gambar")) {
            //     if ($msbarang->LokasiGambar != null) {
            //         unlink(storage_path('app/public/images/msbarang/' . $msbarang->LokasiGambar));
            //     }
            //     $gambar = $request->file("gambar");
            //     $ext = $gambar->getClientOriginalExtension();
            //     $filename = time() . '.' . $ext;
            //     $img = Image::make($gambar);
            //     $img->resize(200, 200);
            //     $img->stream();
            //     $img->orientate();
            //     Storage::disk("indospring")->put("msbarang/" . $filename, $img);
            //     $msbarang->LokasiGambar = $filename;
            // }
            $msbarang->save();

            // dd($msbarang);
            if($request->get("status") == "update"){
                return redirect()->back()->with("success", "Data Barang berhasil di update");
            }else{
                return redirect()->back()->with("success", "Data Barang berhasil di simpan");
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

    public function getKategori(Request $request)
    {
        if ($request->ajax()) {
            $mskategori = Mskategori::where("Kode","!=",null)->get();
            return response()->json($mskategori);
        }
    }

    public function CheckKodeBarang(Request $request){
        if($request->ajax()){
            $msbarang = Msbarang::find($request->get("kode"));
            if($msbarang){
                return response()->json([
                    'status'=> true
                ]);
            }else{
                return response()->json([
                    'status'=> false
                ]);
            }
        }
    }

    public function CheckKodeBarcode(Request $request){
        if($request->ajax()){
            $msbarang = Msbarang::where('KodeBarcode', $request->get("KodeBarcode"))->where('KodeBarcode','!=',null)->first();
            if($msbarang){
                return response()->json([
                    'status'=> true
                ]);
            }else{
                return response()->json([
                    'status'=> false
                ]);
            }
        }
    }
}