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
        $SaldoMinusMax = Mssetting::where('Kode', 'SaldoMinusMax')->first();
        $SaldoMinusBunga = Mssetting::where('Kode', 'SaldoMinusBunga')->first();
        $SaldoMinusResetPerBulan = Mssetting::where('Kode', 'SaldoMinusResetPerBulan')->first();

        return view(
            "frontend.settings.setting.index",
            [
                'MobileBelanjaOnOff' => $MobileBelanjaOnOff,
                'MobileBelanjaJamOnOff' => $MobileBelanjaJamOnOff,
                'MobilePengajuanDay' => $MobilePengajuanDay,
                'MobilePengajuanfirst' => $MobilePengajuanfirst,
                'MobilePengajuanMaksAnggota' => $MobilePengajuanMaksAnggota,
                'MobileInfoPinjamanMulaiDari' => $MobileInfoPinjamanMulaiDari,
                'SaldoMinusMax' => $SaldoMinusMax,
                'SaldoMinusBunga' => $SaldoMinusBunga,
                'SaldoMinusResetPerBulan' => $SaldoMinusResetPerBulan,
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

        //mobilebelanjaonoff
        $name1 = $request->get('name1');
        $retname1 = Mssetting::where('Kode', $name1)->first();
        if($retname1){
            if($request->get('ckmblbelanjaonoff') == "on"){
                $retname1->aktif = 1;
            }else{
                $retname1->aktif = 0;
            }

            $retname1->Nama = $request->get('inputmblbelanjaonoff');
            $retname1->save();
        }


        $name2 = $request->get('name2');
        $retname2 = Mssetting::where('Kode', $name2)->first();
        if($retname2){
            if($request->get('ckmblbelanjajam') == "on"){
                $retname2->aktif = 1;
            }else{
                $retname2->aktif = 0;
            }
            $retname2->MobileTimeOn = $request->get('inputmblbelanjajamon');
            $retname2->MobileTimeOff = $request->get('inputmblbelanjajamoff');
            $retname2->Nama = $request->get('inputmblbelanjajam');
            $retname2->save();

        }


        $name3 = $request->get('name3');
        $retname3 = Mssetting::where('Kode', $name3)->first();
        if($retname3){
            $pengajuandayid = $request->get('pengajuandayid');
            $pengajuanday = $request->get('pengajuanday');
            $ckpengajuanday = $request->get('ckmblpengajuanday');
            foreach($pengajuandayid as $key => $row){
                $findpengajuan = Mssetting::find($row);

                if($findpengajuan){
                    $findpengajuan->Nilai = $pengajuanday[$key];
                    $findpengajuan->Nama = $request->get('inputmblpengajuan');
                    if(isset($ckpengajuanday[$key])){
                        if($ckpengajuanday[$key] == "on"){
                            $findpengajuan->aktif = 1;
                        }
                    }else{
                        $findpengajuan->aktif = 0;
                    }
                    $findpengajuan->save();
                }
            }

        }



        $name4 = $request->get('name4');
        $retname4 = Mssetting::where('Kode', $name4)->first();
        if($retname4){
            if($request->get('ckmblpengajuanmaksanggota') == "on"){
                $retname4->aktif = 1;
            }else{
                $retname4->aktif = 0;
            }
            $retname4->Nilai = $request->get('inputmblpengajuanmaksanggotanilai');
            $retname4->Nama = $request->get('inputmblpengajuanmaksanggotanama');
            $retname4->save();

        }

        $name5 = $request->get('name5');
        $retname5 = Mssetting::where('Kode', $name5)->first();
        if($retname5){
            if($request->get('ckmblinfopinjaman') == "on"){
                $retname5->aktif = 1;
            }else{
                $retname5->aktif = 0;
            }
            $retname5->Nilai = $request->get('inputmblinfopinjamannilai');
            $retname5->Nama = $request->get('inputmblinfopinjamannama');
            $retname5->save();

        }

        $name6 = $request->get('name6');
        $retname6 = Mssetting::where('Kode', $name6)->first();
        if($retname6){
            if($request->get('ckmblSaldoMinusMax') == "on"){
                $retname6->aktif = 1;
            }else{
                $retname6->aktif = 0;
            }
            $retname6->Nilai = $request->get('inputmblSaldoMinusMaxnilai');
            $retname6->Nama = $request->get('inputmblSaldoMinusMaxnama');
            $retname6->save();

        }
        $name7 = $request->get('name7');
        $retname7 = Mssetting::where('Kode', $name7)->first();
        if($retname7){
            if($request->get('ckSaldoMinusBunga') == "on"){
                $retname7->aktif = 1;
            }else{
                $retname7->aktif = 0;
            }
            $retname7->Nilai = $request->get('inputSaldoMinusBunganilai');
            $retname7->Nama = $request->get('inputmblSaldoMinusBunganama');
            $retname7->save();

        }
        $name8 = $request->get('name8');
        $retname8 = Mssetting::where('Kode', $name8)->first();
        if($retname8){
            if($request->get('ckSaldoMinusResetPerBulan') == "on"){
                $retname8->aktif = 1;
            }else{
                $retname8->aktif = 0;
            }
            $retname8->Nilai = $request->get('inputSaldoMinusResetPerBulannilai');
            $retname8->Nama = $request->get('inputSaldoMinusResetPerBulannama');
            $retname8->save();

        }


        return response()->json([
            'message'=> 'true'
        ]);

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
