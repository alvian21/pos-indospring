<?php

namespace App\Http\Controllers\Frontend\POS\Synchronize;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;
use App\Trmutasihd;
use App\Trmutasidt;

class ListPromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.pos.synchronize.list_promo.index");
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
            DB::beginTransaction();

            try {
                $token = session('api_token');
                $client = new Client();
                $url = config('app.api_url') . 'listpromo';

                $response = $client->request('GET', $url, [

                    'headers' => [
                        'Authorization' => "Bearer " . $token
                    ],
                ]);

                $response = $response->getBody()->getContents();

                $response = json_decode($response, true);

                if ($response['status']) {
                    Trmutasidt::where('Transaksi','PROMO')->delete();
                    Trmutasihd::where('Transaksi','PROMO')->delete();

                    Trmutasihd::insert($response['trmutasihd']);
                    Trmutasidt::insert($response['trmutasidt']);

                    DB::commit();

                    return response()->json(
                        [
                            'status' => true,
                            'message' => 'List promo Berhasil di Synchronize',
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
            } catch (\Exception $th) {
                //throw $th;
                DB::rollBack();

                return response()->json(
                    [
                        'status' => false,
                        'message' => $th,
                        'code' => Response::HTTP_BAD_REQUEST
                    ]
                );
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
