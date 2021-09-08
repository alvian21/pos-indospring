<?php

namespace App\Http\Controllers\Frontend\Master;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Http\Request;
use App\CustomClass\sendWa;

class WaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.master.wa.index");
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
            'nomor_wa' => 'required|phone:ID',
            'opsi' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $nomor = $request->get('nomor_wa');
            $nomor = PhoneNumber::make($nomor, 'ID')->formatE164();
            $nomor = str_replace('+', '', $nomor);
            $opsi = $request->get('opsi');

            if ($opsi == 'otp') {
                $apiwa = new sendWa($nomor, $this->generateCode());
                $apiwa = $apiwa->two_factor_code();
            }else{
                $apiwa = new sendWa($nomor, $request->get('text'));
                $apiwa = $apiwa->text();
            }
         
            if ($apiwa['success']) {
                return redirect()->back()->with("success", $apiwa['message']);
            } else {
                return redirect()->back()->with("error", $apiwa['message']);
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

    public function generateCode()
    {
        return  rand(100000, 999999);
    }
}
