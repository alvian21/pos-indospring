<?php

namespace App\Http\Controllers\Frontend\Master;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Msloginhd;
use App\Msanggota;
use App\Mslogindt;
use App\Mslokasi;
use App\Msmenu;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $msloginhd = Msloginhd::max('KodeUser');
        $user = Msloginhd::all();
        $msmenu = Msmenu::all();
        $msanggota = Msanggota::all();
        $mslokasi = Mslokasi::all();
        $kodeuser = 0;
        if (!is_null($msloginhd)) {
            $kodeuser = $msloginhd + 1;
        } else {
            $nomor = 1;
            $addzero = str_pad($nomor, 3, '0', STR_PAD_LEFT);
            $kodeuser = date('y') . $addzero;
        }
        return view("frontend.master.user.index", ['msanggota' => $msanggota, 'mslokasi' => $mslokasi, 'user' => $user, 'kodeuser' => $kodeuser]);
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
                'user_login' => 'required|unique:msloginhd,UserLogin',
                'user_password' => 'required',
                'level_approval' => 'nullable',
                'lokasi' => 'nullable',
                'boleh_backup' => 'required',
                'anggota' => 'nullable',
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

                    $msloginhd = Msloginhd::max('KodeUser');
                    $kodeuser = 0;
                    if (!is_null($msloginhd)) {
                        $kodeuser = $msloginhd + 1;
                    } else {
                        $nomor = 1;
                        $addzero = str_pad($nomor, 3, '0', STR_PAD_LEFT);
                        $kodeuser = date('y') . $addzero;
                    }
                    $user = new Msloginhd();
                    $user->KodeUser = $kodeuser;
                    $user->UserLogin     = $request->get('user_login');
                    $user->UserPassword = $request->get('user_password');
                    $user->LevelApprovalPengajuan = $request->get('level_approval');
                    $user->KodeLokasi = $request->get('lokasi');
                    $user->BolehBackup = $request->get('boleh_backup');
                    $user->UserUpdate =  auth('web')->user()->UserLogin;
                    $user->KodeAnggota = $request->get('anggota');
                    $user->LastUpdate = date('Y-m-d H:i:s');
                    $user->save();

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
        $detail = Mslogindt::where('KodeUser', $id)->get();
        return view("frontend.master.user.show", ['detail' => $detail]);
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
                'user_password' => 'required',
                'level_approval' => 'nullable',
                'lokasi' => 'nullable',
                'boleh_backup' => 'required',
                'anggota' => 'nullable',
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
                    $user = Msloginhd::findOrFail($id);
                    $user->UserPassword = $request->get('user_password');
                    $user->LevelApprovalPengajuan = $request->get('level_approval');
                    $user->KodeLokasi = $request->get('lokasi');
                    $user->BolehBackup = $request->get('boleh_backup');
                    $user->UserUpdate =  auth('web')->user()->UserLogin;
                    $user->KodeAnggota = $request->get('anggota');
                    $user->LastUpdate = date('Y-m-d H:i:s');
                    $user->save();

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

    public function saveHeader(Request $request)
    {
        if ($request->ajax()) {
            $datauser = session('master_user');
            $data = [
                'KodeUser' => $request->get('KodeUser'),
                'UserLogin' => $request->get('UserLogin'),
                'UserPassword' => $request->get('UserPassword'),
                'anggota' =>  $request->get('anggota'),
                'level' =>  $request->get('level'),
                'lokasi' =>  $request->get('lokasi'),
            ];
            session(['master_user' => $data]);
            session()->save();

            return response()->json(
                [
                    'message' => 'true'
                ]
            );
        }
    }

    public function saveDetail(Request $request)
    {
        if ($request->ajax()) {

            $menu = Msmenu::find($request->get('item_index'));
            $arr = [];
            $no = 1;
            if (session()->has('detail_user')) {
                $datadetail = session('detail_user');
                $status = false;
                foreach ($datadetail as $key => $value) {
                    if ($value['ItemIndex'] == $menu->ItemIndex) {
                        $status = true;
                        array_push($arr, $value);
                    } else {
                        array_push($arr, $value);
                    }
                    $no++;
                }

                if (!$status) {
                    $data = [
                        'no' => $no,
                        'ItemIndex' => $menu->ItemIndex,
                        'Level' => $menu->Lvl,
                        'Grup' => $menu->Grup,
                        'Nama' => $menu->Nama
                    ];
                    array_push($arr, $data);
                }
            } else {

                $data = [
                    'no' => $no,
                    'ItemIndex' => $menu->ItemIndex,
                    'Level' => $menu->Lvl,
                    'Grup' => $menu->Grup,
                    'Nama' => $menu->Nama
                ];
                array_push($arr, $data);
            }

            session(['detail_user' => $arr]);

            return response()->json([
                'message' => 'true'
            ]);
        }
    }


    public function updateDetail(Request $request)
    {
        if ($request->ajax()) {

            $menu = Msmenu::find($request->get('item_index'));
            $arr = [];
            $no = 1;
            if (session()->has('detail_user')) {
                $datadetail = session('detail_user');
                $status = false;
                foreach ($datadetail as $key => $value) {
                    if ($value['ItemIndex'] == $menu->ItemIndex) {
                        $status = true;
                    } else {
                        $no++;
                        array_push($arr, $value);
                    }
                }

                if (!$status) {
                    $data = [
                        'no' => $no,
                        'ItemIndex' => $menu->ItemIndex,
                        'Level' => $menu->Lvl,
                        'Grup' => $menu->Grup,
                        'Nama' => $menu->Nama
                    ];
                    array_push($arr, $data);
                }
            } else {

                $data = [
                    'no' => $no,
                    'ItemIndex' => $menu->ItemIndex,
                    'Level' => $menu->Lvl,
                    'Grup' => $menu->Grup,
                    'Nama' => $menu->Nama
                ];
                array_push($arr, $data);
            }

            session(['detail_user' => $arr]);

            return response()->json([
                'message' => 'true'
            ]);
        }
    }

    public function getDataDetail(Request $request)
    {
        if ($request->ajax()) {
            $datadetail = session('detail_user');
            $data2 = array();
            if ($datadetail != null) {
                $count = count($datadetail);
                $no = 1;
                foreach ($datadetail as $row) {
                    $sub = array();
                    $sub["no"] = $row['no'];
                    $sub["item"] = $row['ItemIndex'];
                    $sub["level"] = $row['Level'];
                    $sub["grup"] = $row['Grup'];
                    $sub["nama"] = $row['Nama'];
                    $sub["action"] = '<button data-item="' . $row['ItemIndex'] . '" class="edit btn btn-danger ml-2 btnDelete">Delete</button>';
                    $data2[] = $sub;
                }
            } else {
                $count = 0;
            }
            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $data2
            ];
            return response()->json($output);
        }
    }

    public function delete_detail($id)
    {
        if (session()->has('detail_user')) {
            $datadetail = session('detail_user');
            $arr = [];
            foreach ($datadetail as $key => $value) {
                if ($value['ItemIndex'] != $id) {
                    array_push($arr, $value);
                }
            }

            session(['detail_user' => $arr]);

            session()->save();
            return redirect()->route('master.user.index')->with("success", "Detail user berhasil dihapus");
        }
    }

    public function checkUser(Request $request)
    {
        if ($request->ajax()) {
            $msloginhd = Msloginhd::where('UserLogin', $request->get('UserLogin'))->first();
            if ($msloginhd) {
                return response()->json([
                    'message' => 'true'
                ]);
            } else {
                return response()->json([
                    'message' => 'false'
                ]);
            }
        }
    }


    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $user = Msloginhd::findOrFail($request->get('id'));
            return response()->json([
                'status' => true,
                'data' => $user
            ]);
        }
    }
}
