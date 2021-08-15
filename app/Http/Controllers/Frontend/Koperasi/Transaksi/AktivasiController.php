<?php

namespace App\Http\Controllers\Frontend\Koperasi\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Msanggota;
use App\Traktifasi;
use Illuminate\Support\Facades\DB;

class AktivasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $anggota = Msanggota::all();

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'barcode' => 'required'
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
                $barcode = $request->get('barcode');
                $aktivasi = Traktifasi::where('Kode', $barcode)->where('Status', 'aktif')->first();

                if ($aktivasi) {
                    $status = true;
                } else {
                    $status = false;
                }

                return response()->json([
                    'status' => true,
                    'datastatus' => $status,
                    'barcode' => $barcode
                ]);
            }
        }
        return view("frontend.koperasi.transaksi.aktivasi.index", ['anggota' => $anggota]);
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
                'barcode' => 'required',
                'kode_nfc' => 'required|max:10|unique:traktifasi,NoEkop'
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
                $barcode = $request->get('barcode');
                $kode_nfc = $request->get('kode_nfc');
                DB::beginTransaction();

                try {
                    $aktivasi = Traktifasi::where('Kode', $barcode)->where('Status', 'aktif')->first();
                    $day = date('d');
                    $month = date('m');
                    $year = date('Y');
                    $cek = Traktifasi::whereYear('Tanggal', $year)->whereMonth('Tanggal', $month)->whereDay('Tanggal', $day)->OrderBy('Tanggal', 'DESC')->first();
                    if ($cek) {
                        $nomor = (int) substr($cek->Nomor, 14);
                        if ($nomor != 0) {
                            if ($nomor >= 9999) {
                                $nomor = $nomor + 1;
                                $formatNomor = "AK-" . date('Y-m-d') . "-" . $nomor;
                            } else {
                                $nomor = $nomor + 1;
                                $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                                $formatNomor = "AK-" . date('Y-m-d') . "-" . $addzero;
                            }
                        }
                    } else {
                        $nomor = 1;
                        $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                        $formatNomor = "AK-" . date('Y-m-d') . "-" . $addzero;
                    }


                    $newaktivasi = new Traktifasi();
                    $newaktivasi->Nomor = $formatNomor;
                    $newaktivasi->Tanggal = date('Y-m-d H:i:s');
                    $newaktivasi->Kode = $barcode;
                    $newaktivasi->NoEkop = $kode_nfc;
                    $newaktivasi->Status = 'aktif';
                    $newaktivasi->UserUpdate = auth('web')->user()->UserLogin;
                    $newaktivasi->save();
                    if ($aktivasi) {
                        $aktivasi->Status = 'non-aktif';
                        $aktivasi->save();
                    }
                    DB::commit();
                    return response()->json([
                        'status' => true
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;
                    DB::rollBack();
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
