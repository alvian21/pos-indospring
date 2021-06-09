<?php

namespace App\Http\Controllers\Frontend\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mssetting;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $MobileBelanjaOnOff = Mssetting::where('Kode', 'MobileBelanjaOnOff')->first();
        $MobileBelanjaJamOnOff = Mssetting::where('Kode', 'MobileBelanjaJamOnOff')->first();
        $MobilePengajuanDay = Mssetting::where('Kode', 'MobilePengajuanDay')->get();
        $MobilePengajuanfirst = Mssetting::where('Kode', 'MobilePengajuanDay')->first();
        $MobilePengajuanMaksAnggota = Mssetting::where('Kode', 'MobilePengajuanMaksAnggota')->first();
        $MobileInfoPinjamanMulaiDari = Mssetting::where('Kode', 'MobileInfoPinjamanMulaiDari')->first();

        return view(
            "frontend.settings.setting.index",
            [
                'MobileBelanjaOnOff' => $MobileBelanjaOnOff,
                'MobileBelanjaJamOnOff' => $MobileBelanjaJamOnOff,
                'MobilePengajuanDay' => $MobilePengajuanDay,
                'MobilePengajuanfirst' => $MobilePengajuanfirst,
                'MobilePengajuanMaksAnggota' => $MobilePengajuanMaksAnggota,
                'MobileInfoPinjamanMulaiDari' => $MobileInfoPinjamanMulaiDari,
            ]
        );
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
            'aktif' => 'required',
            'nilai' => 'nullable',
            'id' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            if ($request->get('kode') == 'MobilePengajuanDay') {
                $mssetting = Mssetting::find($request->get('id'));
            } else {
                $mssetting = Mssetting::where('Kode', $request->get('kode'))->first();
            }
            $mssetting->aktif = $request->get('aktif');
            $mssetting->save();

            return response()->json([
                'message' => 'true'
            ]);
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
