<?php

namespace App\Http\Controllers\Frontend\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mssetting;
use App\Msanggota;
use App\Trsaldoekop;
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
        $PajakPenjualan = Mssetting::where('Kode', 'PajakPenjualan')->first();
        $DiskonRpPenjualanReadOnly = Mssetting::where('Kode', 'DiskonRpPenjualanReadOnly')->first();
        $DiskonPersenPenjualanReadOnly = Mssetting::where('Kode', 'DiskonPersenPenjualanReadOnly')->first();
        $cetak = Mssetting::where('Kode', 'Cetak')->first();
        $HitungSimPin = Mssetting::where('Kode', 'HitungSimPin')->first();
        $FooterPrinter = Mssetting::where('Kode', 'FooterPrinter')->first();
        $ToleransiApproval = Mssetting::where('Kode','RecordTanggalMaksimalSetting')->first();
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
                'PajakPenjualan' => $PajakPenjualan,
                'DiskonRpPenjualanReadOnly' => $DiskonRpPenjualanReadOnly,
                'DiskonPersenPenjualanReadOnly' => $DiskonPersenPenjualanReadOnly,
                'cetak' => $cetak,
                'HitungSimPin' => $HitungSimPin,
                'FooterPrinter' => $FooterPrinter,
                'ToleransiApproval' => $ToleransiApproval,
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
        if ($retname1) {
            if ($request->get('ckmblbelanjaonoff') == "on") {
                $retname1->aktif = 1;
            } else {
                $retname1->aktif = 0;
            }

            $retname1->Nama = $request->get('inputmblbelanjaonoff');
            $retname1->save();
        }


        $name2 = $request->get('name2');
        $retname2 = Mssetting::where('Kode', $name2)->first();
        if ($retname2) {
            if ($request->get('ckmblbelanjajam') == "on") {
                $retname2->aktif = 1;
            } else {
                $retname2->aktif = 0;
            }
            $retname2->MobileTimeOn = $request->get('inputmblbelanjajamon');
            $retname2->MobileTimeOff = $request->get('inputmblbelanjajamoff');
            $retname2->Nama = $request->get('inputmblbelanjajam');
            $retname2->save();
        }


        $name3 = $request->get('name3');
        $retname3 = Mssetting::where('Kode', $name3)->first();
        if ($retname3) {
            $pengajuandayid1 = $request->get('pengajuandayid1');
            $pengajuandayid2 = $request->get('pengajuandayid2');
            $pengajuanday1 = $request->get('pengajuanday1');
            $pengajuanday2 = $request->get('pengajuanday2');
            $ckpengajuanday = $request->get('ckmblpengajuanday');

            if ($ckpengajuanday== "on") {
                $aktif = 1;
            }else{
                $aktif = 0;
            }

            $updatename3 = Mssetting::where('Kode',$name3)->update([
                'aktif' => $aktif,
                'Nama' => $request->get('inputmblpengajuan')
            ]);

            $pengajuan1 = Mssetting::find($pengajuandayid1);
            $pengajuan1->Nilai = $pengajuanday1;
            $pengajuan1->save();

            $pengajuan2 = Mssetting::find($pengajuandayid2);
            $pengajuan2->Nilai = $pengajuanday2;
            $pengajuan2->save();

        }



        $name4 = $request->get('name4');
        $retname4 = Mssetting::where('Kode', $name4)->first();
        if ($retname4) {
            if ($request->get('ckmblpengajuanmaksanggota') == "on") {
                $retname4->aktif = 1;
            } else {
                $retname4->aktif = 0;
            }
            $retname4->Nilai = $request->get('inputmblpengajuanmaksanggotanilai');
            $retname4->Nama = $request->get('inputmblpengajuanmaksanggotanama');
            $retname4->save();
        }

        $name5 = $request->get('name5');
        $retname5 = Mssetting::where('Kode', $name5)->first();
        if ($retname5) {
            if ($request->get('ckmblinfopinjaman') == "on") {
                $retname5->aktif = 1;
            } else {
                $retname5->aktif = 0;
            }
            $retname5->Nilai = $request->get('inputmblinfopinjamannilai');
            $retname5->Nama = $request->get('inputmblinfopinjamannama');
            $retname5->save();
        }

        $name6 = $request->get('name6');
        $retname6 = Mssetting::where('Kode', $name6)->first();
        if ($retname6) {
            if ($request->get('ckmblSaldoMinusMax') == "on") {
                $retname6->aktif = 1;
                $this->generateSaldoMinus($retname6->Nilai);
            } else {
                $retname6->aktif = 0;
            }
            $retname6->Nilai = $request->get('inputmblSaldoMinusMaxnilai');
            $retname6->Nama = $request->get('inputmblSaldoMinusMaxnama');
            $retname6->save();
        }
        $name7 = $request->get('name7');
        $retname7 = Mssetting::where('Kode', $name7)->first();
        if ($retname7) {
            if ($request->get('ckSaldoMinusBunga') == "on") {
                $retname7->aktif = 1;
            } else {
                $retname7->aktif = 0;
            }
            $retname7->Nilai = $request->get('inputSaldoMinusBunganilai');
            $retname7->Nama = $request->get('inputmblSaldoMinusBunganama');
            $retname7->save();
        }
        $name8 = $request->get('name8');
        $retname8 = Mssetting::where('Kode', $name8)->first();
        if ($retname8) {
            if ($request->get('ckSaldoMinusResetPerBulan') == "on") {
                $retname8->aktif = 1;
            } else {
                $retname8->aktif = 0;
            }
            $retname8->Nilai = $request->get('inputSaldoMinusResetPerBulannilai');
            $retname8->Nama = $request->get('inputSaldoMinusResetPerBulannama');
            $retname8->save();
        }

        $name9 = $request->get('name9');
        $retname9 = Mssetting::where('Kode', $name9)->first();
        if ($retname9) {
            if ($request->get('ckmblPajakPenjualan') == "on") {
                $retname9->aktif = 1;
            } else {
                $retname9->aktif = 0;
            }
            $retname9->Nilai = $request->get('inputmblPajakPenjualannilai');
            $retname9->Nama = $request->get('inputmblPajakPenjualannama');
            $retname9->save();
        }


        $name10 = $request->get('name10');
        $retname10 = Mssetting::where('Kode', $name10)->first();
        if ($retname10) {
            if ($request->get('ckDiskonRpPenjualanReadOnly') == "on") {
                $retname10->aktif = 1;
            } else {
                $retname10->aktif = 0;
            }
            $retname10->Nama = $request->get('inputmblDiskonRpPenjualanReadOnlynama');
            $retname10->save();
        }


        $name11 = $request->get('name11');
        $retname11 = Mssetting::where('Kode', $name11)->first();
        if ($retname11) {
            if ($request->get('ckDiskonPersenPenjualanReadOnly') == "on") {
                $retname11->aktif = 1;
            } else {
                $retname11->aktif = 0;
            }
            $retname11->Nama = $request->get('inputDiskonPersenPenjualanReadOnlynama');
            $retname11->save();
        }



        $name12 = $request->get('name12');
        $retname12 = Mssetting::where('Kode', $name12)->first();
        if ($retname12) {
            if ($request->get('ckcetak') == "on") {
                $retname12->aktif = 1;
            } else {
                $retname12->aktif = 0;
            }

            $retname12->save();
        }

        $name13 = $request->get('name13');
        $retname13 = Mssetting::where('Kode', $name13)->first();
        if ($retname13) {
            if ($request->get('ckmblHitungSimPin') == "on") {
                $retname13->aktif = 1;
            } else {
                $retname13->aktif = 0;
            }
            $retname13->Nilai = $request->get('inputmblHitungSimPinnilai');
            $retname13->Nama = $request->get('inputHitungSimPinnama');
            $retname13->save();
        }

        $name14 = $request->get('name14');
        $retname14 = Mssetting::where('Kode', $name14)->first();
        if ($retname14) {
            $retname14->Nama = $request->get('inputfooter');
            $retname14->save();
        }
        $name15 = $request->get('name15');
        $retname15 = Mssetting::where('Kode', $name15)->first();
        if ($retname15) {
            if ($request->get('ckmblToleransiApproval') == "on") {
                $retname15->aktif = 1;
            } else {
                $retname15->aktif = 0;
            }
            $retname15->Nilai = $request->get('inputmblToleransiApprovalnilai');
            $retname15->Nama = $request->get('inputToleransiApprovalnama');
            $retname15->save();
        }

        session()->forget('transaksi_penjualan');
        return response()->json([
            'message' => 'true'
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

    public function generateSaldoMinus($jumlah)
    {
        $grp = [
            "ISP",
            "IPS",
            "SIJ",
        ];

        $pangkat = [
            "MANAGER",
            "ASST. MANAGER",
            "KARYAWAN",
            "KARYAWAN KONTRAK",
            "STAFF",
        ];
        $anggota = Msanggota::whereIn('Grp', $grp)->whereIn('Pangkat', $pangkat)->get();


        $month = date('m');
        $year = date('Y');
        $arr = [];
        foreach ($anggota as $key => $value) {
            $ekop = Trsaldoekop::where('KodeUser', $value->Kode)->whereMonth('Tanggal', $month)->whereYear('Tanggal', $year)->where('Saldo', '<=', 0)->orderBy('Tanggal', 'DESC')->first();
            if (!$ekop) {
                array_push($arr, $value);
            }
        }

        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $ekop = new Trsaldoekop();
                $ekop->Tanggal = date('Y-m-d');
                $ekop->KodeUser = $value->Kode;
                $ekop->Saldo = $jumlah;
            }
        }
    }
}
