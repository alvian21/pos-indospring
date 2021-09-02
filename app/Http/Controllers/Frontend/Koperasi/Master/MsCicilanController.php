<?php

namespace App\Http\Controllers\Frontend\Koperasi\Master;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mscicilan;

class MsCicilanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cicilan = Mscicilan::all();
        return view("frontend.koperasi.master.cicilan.index", ['cicilan' => $cicilan]);
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
                'bulan' => 'required',
                'nominal' => 'required|min:0|integer',
                'cicilan_total' => 'required|min:0|integer',
                'cicilan_pokok' => 'required|min:0|integer',
                'cicilan_bunga' => 'required|min:0|integer',
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
                try {
                    $cicilan = new Mscicilan();
                    $cicilan->bulan = $request->get('bulan');
                    $cicilan->nominal = $request->get('nominal');
                    $cicilan->CicilanTotal = $request->get('cicilan_total');
                    $cicilan->CicilanPokok = $request->get('cicilan_pokok');
                    $cicilan->CicilanBunga = $request->get('cicilan_bunga');
                    $cicilan->UserUpdate = auth('web')->user()->UserLogin;
                    $cicilan->LastUpdate = date('Y-m-d H:i:s');
                    $cicilan->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'success'
                    ]);
                } catch (\Exception $th) {
                    //throw $th;
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
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'bulan' => 'required',
                'nominal' => 'required|min:0|integer',
                'cicilan_total' => 'required|min:0|integer',
                'cicilan_pokok' => 'required|min:0|integer',
                'cicilan_bunga' => 'required|min:0|integer',
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
                try {
                    $cicilan = Mscicilan::findOrFail($id);
                    $cicilan->bulan = $request->get('bulan');
                    $cicilan->nominal = $request->get('nominal');
                    $cicilan->CicilanTotal = $request->get('cicilan_total');
                    $cicilan->CicilanPokok = $request->get('cicilan_pokok');
                    $cicilan->CicilanBunga = $request->get('cicilan_bunga');
                    $cicilan->UserUpdate = auth('web')->user()->UserLogin;
                    $cicilan->LastUpdate = date('Y-m-d H:i:s');
                    $cicilan->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'success'
                    ]);
                } catch (\Exception $th) {
                    //throw $th;
                }
            }
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

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $cicilan = Mscicilan::findOrFail($request->get('id'));
            return response()->json([
                'status' => true,
                'data' => $cicilan
            ]);
        }
    }
}
