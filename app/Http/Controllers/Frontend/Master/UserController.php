<?php

namespace App\Http\Controllers\Frontend\Master;

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

        if (session()->has('master_user')) {
            $datauser = session('master_user');
        } else {
            $data = [
                'KodeUser' => $kodeuser,
                'UserLogin' => '',
                'UserPassword' => '',
                'anggota' => 'Tidak Ada',
                'level' => 'Tidak Ada',
                'lokasi' => 'Tidak Ada',
            ];
            session(['master_user' => $data]);
            $datauser = session('master_user');
        }

        return view("frontend.master.user.index", ['datauser' => $datauser, 'msanggota' => $msanggota, 'mslokasi' => $mslokasi, 'msmenu' => $msmenu]);
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
            if ($request->get('status') == 'check') {
                if (session()->has('detail_user')) {
                    return response()->json([
                        'message' => 'true'
                    ]);
                } else {
                    return response()->json([
                        'message' => 'false'
                    ]);
                }
            }

            if ($request->get('status') == 'save') {
                $datauser = session('master_user');
                if (session()->has('detail_user')) {
                    $datadetail = session('detail_user');
                    $msloginhd = Msloginhd::max('KodeUser');
                    $kodeuser = 0;
                    if (!is_null($msloginhd)) {
                        $kodeuser = $msloginhd + 1;
                    } else {
                        $nomor = 1;
                        $addzero = str_pad($nomor, 3, '0', STR_PAD_LEFT);
                        $kodeuser = date('y') . $addzero;
                    }

                    $msloginhd = new Msloginhd();
                    $msloginhd->KodeUser = $kodeuser;
                    $msloginhd->UserLogin = $datauser['UserLogin'];
                    $msloginhd->UserPassword = $datauser['UserPassword'];
                    $msloginhd->UserUpdate =  auth('web')->user()->UserLogin;
                    $msloginhd->KodeAnggota = $datauser['anggota'];
                    $msloginhd->LastUpdate = date('Y-m-d H:i:s');
                    $msloginhd->LevelApprovalPengajuan = $datauser['level'];
                    $msloginhd->KodeLokasi = $datauser['lokasi'];
                    $msloginhd->save();

                    foreach ($datadetail as $key => $value) {
                        $mslogindt = new Mslogindt();
                        $mslogindt->KodeUser = $kodeuser;
                        $mslogindt->ItemIndex = $value['ItemIndex'];
                        $mslogindt->Lvl = $value['Level'];
                        $mslogindt->Grup = $value['Grup'];
                        $mslogindt->Nama = $value['Nama'];
                        $mslogindt->UserUpdate =  auth('web')->user()->UserLogin;
                        $mslogindt->LastUpdate = date('Y-m-d H:i:s');
                        $mslogindt->save();
                    }
                    session()->forget('detail_user');
                    session()->forget('master_user');
                    session()->flash('success','Detail dan data user berhasil disimpan');
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

    public function checkUser(Request $request){
        if($request->ajax()){
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

}
