<?php

namespace App\Http\Controllers\Frontend\POS\Synchronize;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Mssetting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.pos.synchronize.setting.index");
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
                $msettingcloud = Mssetting::on($koneksi)->get();

                foreach ($msettingcloud as $key => $value) {
                    $lokalsetting = Mssetting::find($value->id);
                    if($lokalsetting){
                        $lokalsetting->Kode = $value->Kode;
                        $lokalsetting->Nama = $value->Nama;
                        $lokalsetting->aktif = $value->aktif;
                        $lokalsetting->Nilai = $value->Nilai;
                        $lokalsetting->MobileTimeOn = $value->MobileTimeOn;
                        $lokalsetting->MobileTimeOff = $value->MobileTimeOff;
                        $lokalsetting->save();
                    }else{
                        $lokalsetting = new Mssetting();
                        $lokalsetting->Kode = $value->Kode;
                        $lokalsetting->Nama = $value->Nama;
                        $lokalsetting->aktif = $value->aktif;
                        $lokalsetting->Nilai = $value->Nilai;
                        $lokalsetting->MobileTimeOn = $value->MobileTimeOn;
                        $lokalsetting->MobileTimeOff = $value->MobileTimeOff;
                        $lokalsetting->save();
                    }
                }

                DB::commit();
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Setting Berhasil di Synchronize',
                        'code' => Response::HTTP_OK,
                    ]
                );

            } catch (\Exception $th) {
                    //throw $th;
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'Maaf ada yang error'
                    ]);
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
