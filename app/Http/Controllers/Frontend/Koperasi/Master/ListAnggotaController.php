<?php

namespace App\Http\Controllers\Frontend\Koperasi\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Msanggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AnggotaImport;

class ListAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anggota = Msanggota::all();
        return view('frontend.koperasi.master.anggota.index', ['anggota' => $anggota]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontend.koperasi.master.anggota.create');
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
            'kode' => 'required',
            'nama' => 'required',
            'status' => 'required',
            'jenis_kelamin' => 'required',
            'grp' => 'required',
            'pangkat' => 'required',
            'dept' => 'required',
            'subdept' => 'required',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
            'password' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $cekKode = Msanggota::where("Kode", $request->get("kode"))->first();

            if ($cekKode) {
                return redirect()->back()->with('error', 'Maaf, kode sudah digunakan anggota lain');
            } else {
                $msanggota = new Msanggota();
                $msanggota->Kode = $request->get('kode');
                $msanggota->Nama = $request->get('nama');
                $msanggota->Aktif = $request->get('status');
                $msanggota->Sex = $request->get('jenis_kelamin');
                $msanggota->Grp = $request->get('grp');
                $msanggota->Pangkat = $request->get('pangkat');
                $msanggota->Dept = $request->get('dept');
                $msanggota->SubDept = $request->get('subdept');
                $msanggota->TglMasuk = date('Y-m-d', strtotime($request->get('tanggal_masuk')));
                if ($request->has('tanggal_keluar')) {
                    $msanggota->TglKeluar = date('Y-m-d', strtotime($request->get('tanggal_keluar')));
                } else {
                    $msanggota->TglKeluar = '0000-00-00';
                }

                if ($request->has('password')) {
                    $msanggota->UserPassword = $request->get('password');
                } else {
                    $msanggota->UserPassword = '000000';
                }
                $msanggota->UserUpdate = Auth::guard('web')->user()->UserLogin;
                $msanggota->LastUpdate = date('Y-m-d H:i:s');
                $msanggota->save();

                return redirect()->route('koperasi.anggota.index')->with("success", "Anggota berhasil ditambahkan");
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
        $anggota = Msanggota::where('Kode', $id)->first();
        return view("frontend.koperasi.master.anggota.edit", ['anggota' => $anggota]);
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
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
            'status' => 'required',
            'jenis_kelamin' => 'required',
            'grp' => 'required',
            'pangkat' => 'required',
            'dept' => 'required',
            'subdept' => 'required',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
            'password' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {

                $msanggota = Msanggota::where('Kode',$id)->first();
                $msanggota->Nama = $request->get('nama');
                $msanggota->Aktif = $request->get('status');
                $msanggota->Sex = $request->get('jenis_kelamin');
                $msanggota->Grp = $request->get('grp');
                $msanggota->Pangkat = $request->get('pangkat');
                $msanggota->Dept = $request->get('dept');
                $msanggota->SubDept = $request->get('subdept');
                $msanggota->TglMasuk = date('Y-m-d', strtotime($request->get('tanggal_masuk')));
                if ($request->has('tanggal_keluar')) {
                    $msanggota->TglKeluar = date('Y-m-d', strtotime($request->get('tanggal_keluar')));
                } else {
                    $msanggota->TglKeluar = '0000-00-00';
                }

                if ($request->has('password')) {
                    $msanggota->UserPassword = $request->get('password');
                } else {
                    $msanggota->UserPassword = '000000';
                }
                $msanggota->UserUpdate = Auth::guard('web')->user()->UserLogin;
                $msanggota->LastUpdate = date('Y-m-d H:i:s');
                $msanggota->save();

                return redirect()->route('koperasi.anggota.index')->with("success", "Anggota berhasil diupdate");

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

    public function AnggotaImport(Request $request)
    {
        if ($request->file('file')) {
            Excel::import(new AnggotaImport, $request->file('file'));
            return back()->with('success', 'Data has been imported');
        } else {
            return back()->with('error', 'Please input the file');
        }
    }
}
