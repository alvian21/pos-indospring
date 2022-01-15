<?php

namespace App\Http\Controllers\Frontend\POS\Synchronize;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;
use App\Trmutasihd;
use App\Trmutasidt;
use App\Trsaldoekop;
use App\Trsaldobarang;
use App\Trsaldototalbelanja;
use App\Trsaldototalbelanjaekop;
use App\Trsaldototalbelanjatunai;
use App\Trsaldototalbelanjakredit;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.pos.synchronize.penjualan.index");
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
            $tanggal = $request->get('tanggal');
            $tanggal = date('Y-m-d', strtotime($tanggal));
            $koneksi = 'mysql2';
            DB::beginTransaction();

            // try {
                $backuphd = Trmutasihd::where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', $tanggal)->get();
                $mutasidt = Trmutasidt::where('Transaksi', 'PENJUALAN')->whereDate('LastUpdate', $tanggal)->get();
                $backuphd = json_encode($backuphd);
                $mutasidt = json_encode($mutasidt);
                $token = session('api_token');
                $client = new Client();
                $url = config('app.api_url') . 'penjualan';

                $response = $client->request('POST', $url, [
                    'multipart' => [
                        [
                            'name' => 'trmutasihd',
                            'contents' => $backuphd
                        ],
                        [
                            'name' => 'trmutasidt',
                            'contents' => $mutasidt
                        ],
                        [
                            'name' => 'KodeLokasi',
                            'contents' => auth()->user()->KodeLokasi
                        ],
                    ],
                    'headers' => [
                        'Authorization' => "Bearer " . $token
                    ],
                ]);

                $response = $response->getBody()->getContents();

                $response = json_decode($response, true);

                if ($response['status']) {
                    if(!empty($response['saldototalbelanjatunai'])){
                        Trsaldototalbelanjatunai::insert($response['saldototalbelanjatunai']);
                    }
                    if(!empty($response['saldototalbelanjaekop'])){
                        Trsaldototalbelanjaekop::insert($response['saldototalbelanjaekop']);
                    }

                    if(!empty($response['saldototalbelanjakredit'])){
                        Trsaldototalbelanjakredit::insert($response['saldototalbelanjakredit']);
                    }

                    if(!empty($response['saldototalbelanja'])){
                        Trsaldototalbelanja::insert($response['saldototalbelanja']);
                    }

                    if(!empty($response['saldobarang'])){
                        Trsaldobarang::insert($response['saldobarang']);
                    }
                    Trsaldoekop::insert($response['saldoekop']);


                    // Trsaldototalbelanja::insert($response['saldototalbelanja']);
                    // Trsaldobarang::insert($response['saldobarang']);
                    DB::commit();

                    return response()->json(
                        [
                            'status' => true,
                            'message' => 'Penjualan Berhasil di Synchronize',
                            'code' => Response::HTTP_OK,
                        ]
                    );
                } else {
                    return response()->json(
                        [
                            'status' => false,
                            'code' => Response::HTTP_BAD_REQUEST
                        ]
                    );
                }
            // } catch (\Exception $th) {
            //     //throw $th;
            //     DB::rollBack();

            //     return response()->json(
            //         [
            //             'status' => false,
            //             'message' => $th,
            //             'code' => Response::HTTP_BAD_REQUEST
            //         ]
            //     );
            // }
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

    public function hapus()
    {
        $koneksi = 'mysql2';
        $cek = DB::connection($koneksi)->table('trmutasihd')->where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', "2021-07-30")->where('NomorLokal', '!=', null)->get();

        foreach ($cek as $key => $value) {
            $cekdt = DB::connection($koneksi)->table('trmutasidt')->where('Transaksi', 'PENJUALAN')->where('Nomor', $value->Nomor)->delete();
        }

        $cek = DB::connection($koneksi)->table('trmutasihd')->where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', "2021-07-30")->where('NomorLokal', '!=', null)->delete();

        return response()->json([
            'message' => 'berhasil'
        ]);
    }

    public function generateNomor($tanggal)
    {
        $nomor =  DB::connection('mysql2')->table('trmutasihd')->where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', $tanggal)->max('Nomor');

        if (!is_null($nomor)) {
            $substr = substr($nomor, -5);
            $substr = (int) str_replace('-', '', $substr);
            $nomor = $substr + 1;
            $addzero =  str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = "PE-" . $tanggal . "-" . $addzero;
        } else {
            $nomor = 1;
            $addzero =  str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = "PE-" . $tanggal . "-" . $addzero;
        }

        return $formatNomor;
    }
}
