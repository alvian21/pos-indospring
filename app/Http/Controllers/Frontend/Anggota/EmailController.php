<?php

namespace App\Http\Controllers\Frontend\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Msanggota;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationSuccessful;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $msanggota = Msanggota::where('verified_email', 0)->orWhere('verified_email',1)->get();
        return view('frontend.anggota.index', ['msanggota' => $msanggota]);
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
        //
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


    public function verifyUser(Request $request)
    {
        if ($request->ajax()) {
            $kode = $request->get('kode');
            $msanggota = Msanggota::find($kode);
            $msanggota->verified_email = 1;
            $msanggota->verified_email_date = date("Y-m-d H:i:s");
            $msanggota->UserUpdate = auth('web')->user()->UserLogin;
            $msanggota->save();
            Mail::to($msanggota->email)->send(new VerificationSuccessful());
            return response()->json(
                ['status' => 'success']
            );
        }
    }
}
