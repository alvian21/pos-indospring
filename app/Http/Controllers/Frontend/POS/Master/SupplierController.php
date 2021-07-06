<?php

namespace App\Http\Controllers\Frontend\POS\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mssupplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplier = Mssupplier::all();
        return view('frontend.pos.master.supplier.index',['supplier'=>$supplier]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("frontend.pos.master.supplier.create");
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
            'kode' => 'required|unique:mssupplier,Kode',
            'nama' => 'required'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }else{
            $supplier = new Mssupplier();
            $supplier->Kode = $request->get('kode');
            $supplier->Nama = $request->get('nama');
            $supplier->Kota = $request->get('kota');
            $supplier->Alamat = $request->get('alamat');
            $supplier->KontakPerson = $request->get('kontak_person');
            $supplier->Phone1 = $request->get('phone1');
            $supplier->Phone2 = $request->get('phone2');
            $supplier->UserUpdate = auth()->user()->UserLogin;
            $supplier->LastUpdate = date('Y-m-d H:i:s');
            $supplier->save();

            return redirect()->route('pos.master.supplier.index')->with('success','Data Supplier berhasil disimpan');
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
        $supplier = Mssupplier::find($id);
        return view('frontend.pos.master.supplier.edit',['supplier'=>$supplier]);
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
        $validator = Validator::make($request->all(), [
            'nama' => 'required'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }else{
            $supplier = Mssupplier::find($id);
            $supplier->Nama = $request->get('nama');
            $supplier->Kota = $request->get('kota');
            $supplier->Alamat = $request->get('alamat');
            $supplier->KontakPerson = $request->get('kontak_person');
            $supplier->Phone1 = $request->get('phone1');
            $supplier->Phone2 = $request->get('phone2');
            $supplier->UserUpdate = auth()->user()->UserLogin;
            $supplier->LastUpdate = date('Y-m-d H:i:s');
            $supplier->save();

            return redirect()->route('pos.master.supplier.index')->with('success','Data Supplier berhasil diupdate');
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
