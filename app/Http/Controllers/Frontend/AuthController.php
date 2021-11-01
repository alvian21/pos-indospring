<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Msanggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Mslogindt;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(["login", "getLogin"]);
    }
    public function getLogin()
    {
        if (Auth::guard("web")->check()) {
            return redirect()->route('dashboard.index');
        } else {
            return view("auth.new_login");
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $user = User::where('UserLogin', $request->get('username'))->where('UserPassword', $request->get('password'))->first();
            if ($user) {
                $auth = Auth::guard("web")->login($user);
                $user = Auth::guard('web')->user();
                $anggota = Msanggota::where("Kode", $user->KodeAnggota)->first();
                if ($anggota) {
                    session(['nama_anggota' => $anggota->Nama]);
                }

                $logindt = Mslogindt::where('KodeUser', $user->KodeUser)->get();
                $arr = [];
                foreach ($logindt as $key => $value) {
                    array_push($arr, $value->Nama);
                }

                session(['data_role' => $arr]);

                session()->flash('info', 'Selamat Datang  !');
                return redirect()->route('dashboard.index');
            } else {
                return redirect()->back()->withErrors("wrong kode user or password");
            }
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Session::flush();
        session()->forget('detail_transaksi_pembelian_baru');
        session()->forget('transaksi_pembelian_baru');
        return redirect('/login')
            ->with('alert-info', 'Anda telah keluar, Sampai ketemu lagi!');
    }
}
