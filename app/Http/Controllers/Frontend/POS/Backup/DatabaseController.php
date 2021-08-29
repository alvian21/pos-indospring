<?php

namespace App\Http\Controllers\Frontend\POS\Backup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Response;
use App\Listeners\BackupSuccessListener;
use Spatie\Backup\Events\BackupZipWasCreated;
use GuzzleHttp\Client;


class DatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.pos.backup.database.index");
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

            // try {
                Artisan::call('backup:run --only-db');
                $path = session('path_backup');
                $token = session('api_token');
                $client = new Client();
                $url = config('app.api_url') . 'backup';
                $response = $client->request('POST', $url, [
                    'multipart' => [
                        [
                            'name'     => 'database',
                            'contents' => fopen($path, 'r')
                        ],
                        [
                            'name'     => 'KodeLokasi',
                            'contents' => auth('web')->user()->KodeLokasi,
                        ]
                    ],
                    'headers' => [
                        'Authorization' => "Bearer " . $token
                    ],
                ]);

                $response = $response->getBody()->getContents();

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Backup Berhasil di Lakukan',
                        'code' => Response::HTTP_OK,
                        'path' => $path,
                        'response' => $response
                    ]
                );
            // } catch (\Throwable $th) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Maaf ada yang error'
            //     ]);
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
}
