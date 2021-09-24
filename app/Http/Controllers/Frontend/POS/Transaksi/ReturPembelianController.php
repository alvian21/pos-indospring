<?php

namespace App\Http\Controllers\Frontend\POS\Transaksi;

use App\Http\Controllers\Controller;
use App\Msbarang;
use Illuminate\Http\Request;
use App\Trmutasihd;
use App\Mslokasi;
use App\Msanggota;
use App\Mssupplier;
use App\Trhpp;
use App\Trmutasidt;
use App\Trsaldoekop;
use Illuminate\Support\Facades\Session;
use DataTables;
use App\Trsaldobarang;
use App\Trsaldototalbelanja;
use App\Trsaldototalbelanjakredit;
use App\Trsaldototalbelanjatunai;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReturPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trmutasihd = Trmutasihd::where('Transaksi', 'RETUR PEMBELIAN')->get();
        return view("frontend.pos.transaksi.retur_pembelian.index", ['trmutasihd' => $trmutasihd]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $day = date('d');
        $month = date('m');
        $year = date('Y');

        $trmutasihd = Trmutasihd::where('Transaksi', 'RETUR PEMBELIAN')->whereYear('Tanggal', $year)->whereMonth('Tanggal', $month)->whereDay('Tanggal', $day)->OrderBy('Tanggal', 'DESC')->first();
        $retur = Trmutasihd::where('Transaksi', 'RETUR PEMBELIAN')->get();
        $mssupplier = Mssupplier::all();
        $msbarang = Msbarang::all();

        $msanggota = DB::table('msanggota')->select('msanggota.Kode', 'traktifasi.NoEkop', 'msanggota.Nama')
            ->leftJoin('traktifasi', function ($join) {
                $join->where('traktifasi.Status', 'aktif')
                    ->on('msanggota.Kode', 'traktifasi.Kode');
            })
            ->get();
        if ($trmutasihd) {
            $nomor = (int) substr($trmutasihd->Nomor, 14);
            if ($nomor != 0) {
                if ($nomor >= 9999) {
                    $nomor = $nomor + 1;
                    $formatNomor = "RP-" . date('Y-m-d') . "-" . $nomor;
                } else {
                    $nomor = $nomor + 1;
                    $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                    $formatNomor = "RP-" . date('Y-m-d') . "-" . $addzero;
                }
            }
        } else {
            $nomor = 1;
            $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = "RP-" . date('Y-m-d') . "-" . $addzero;
        }

        if (session()->has('transaksi_retur')) {
            $trretur = session('transaksi_retur');
        } else {
            $data = [
                'transaksi' => 'RETUR PEMBELIAN',
                'nomor' => $formatNomor,
                'tanggal' => date('d M y H:i'),
                'diskon_persen' => '0',
                'pajak' => '10',
                'diskon_rp' => '0',
                'lokasi' => auth()->user()->KodeLokasi,
                'keterangan' => '',
                'total_harga_sebelum' => 0,
                'total_harga' => 0,
                'total_harga_setelah_pajak' => 0,
                'kode' => ''
            ];
            $trretur = session(['transaksi_retur' => $data]);
            $trretur = session('transaksi_retur');
        }

        return view("frontend.pos.transaksi.retur_pembelian.create", [
            'formatNomor' => $formatNomor, 'retur' => $retur,
            'mssupplier' => $mssupplier,
            'msbarang' => $msbarang,
            'trretur' => $trretur,
            'msanggota' => $msanggota
        ]);
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
        $trmutasidt = Trmutasidt::join('msbarang', 'msbarang.Kode', 'trmutasidt.KodeBarang')->where('Nomor', $id)->get();
        return view("frontend.pos.transaksi.retur_pembelian.show", ['trmutasidt' => $trmutasidt]);
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

    public function store_transaksi(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'transaksi' => 'required',
                'nomor' => 'required',
                'tanggal' => 'required',
                'lokasi' => 'required',
                'keterangan' => 'nullable',
                'supplier' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            } else {
                $total = 0;
                if (session()->has('detail_transaksi_retur')) {
                    $datadetail = Session::get('detail_transaksi_retur');
                    foreach ($datadetail as $key => $value) {
                        $total = $total + $value["subtotal"];
                    }
                }
                $data = [
                    'transaksi' => $request->get('transaksi'),
                    'kode' => $request->get('supplier'),
                    'nomor' => $request->get('nomor'),
                    'tanggal' => $request->get('tanggal'),
                    'pajak' => 0,
                    'lokasi' => $request->get('lokasi'),
                    'keterangan' => $request->get('keterangan'),
                    'total_harga_sebelum' => $total,
                    'total_harga' => $total,
                    'total_harga_setelah_pajak' => $total
                ];

                session(['transaksi_retur' => $data]);
                session()->save();

                return response()->json($data);
            }
        }
    }

    public function get_data_barang(Request $request)
    {
        if ($request->ajax()) {
            $msbarang = Msbarang::where('Kode', $request->kode_barang)->first();
            $trsaldobarang = Trsaldobarang::where('KodeBarang', $request->kode_barang)->where('KodeLokasi', auth()->user()->KodeLokasi)->OrderBy('Tanggal', 'DESC')->first();
            $saldo = 0;
            if ($trsaldobarang) {
                $saldo = $trsaldobarang->Saldo;
            }
            $periode = date('Ym');
            $lokasi = auth()->user()->KodeLokasi;
            $trhpp = Trhpp::where('Periode', $periode)->where('KodeLokasi', $lokasi)->where('KodeBarang', $request->kode_barang)->first();

            $data = [
                'Nama' => $msbarang->Nama,
                'HargaJual' => $trhpp->Hpp,
                'Saldo' => $saldo
            ];
            return response()->json($data);
        }
    }

    public function store_detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang' => 'required',
            'nama_barang' => 'required',
            'harga' => 'required',
            'qty' => 'required',
            'subtotal' => 'required',
            'keterangan' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $arr = [];

            //cek sisa barang

            $trretur = Session::get('transaksi_retur');
            $total = 0;
            $harga = $request->get('harga');
            $harga = str_replace('.', '', $harga);
            $subtotal = $request->get('subtotal');
            $subtotal = str_replace('.', '', $subtotal);
            if (Session::has('detail_transaksi_retur')) {
                $datadetail = Session::get('detail_transaksi_retur');
                $no = 0;
                foreach ($datadetail as $key => $value) {
                    $total = $total + $value["subtotal"];
                    $no = $value["urut"];
                    array_push($arr, $value);
                }
                $data = [
                    'urut' => $no + 1,
                    'barang' => $request->get('barang'),
                    'nama_barang' => $request->get('nama_barang'),
                    'harga' => $harga,
                    'qty' => $request->get('qty'),
                    'subtotal' => $subtotal,
                    'keterangan' => $request->get('keterangan'),
                ];
                $total = $total + $subtotal;
                array_push($arr, $data);
                Session::forget('detail_transaksi_retur');
                Session::put('detail_transaksi_retur', $arr);
                Session::save();
            } else {
                $data = [
                    'urut' => 1,
                    'barang' => $request->get('barang'),
                    'nama_barang' => $request->get('nama_barang'),
                    'harga' => $harga,
                    'qty' => $request->get('qty'),
                    'subtotal' => $subtotal,
                    'keterangan' => $request->get('keterangan'),
                ];
                $total = $total + $subtotal;
                array_push($arr, $data);
                Session::forget('detail_transaksi_retur');
                Session::put('detail_transaksi_retur', $arr);
                Session::save();
            }

            $data = [
                'transaksi' => $trretur['transaksi'],
                'kode' => $trretur['kode'],
                'nomor' => $trretur['nomor'],
                'tanggal' => $trretur['tanggal'],
                'pajak' => $trretur['pajak'],
                'lokasi' => $trretur['lokasi'],
                'keterangan' => $trretur['keterangan'],
                'total_harga_sebelum' => $total,
                'total_harga' => $total,
                'total_harga_setelah_pajak' => $total
            ];
            Session::forget('transaksi_retur');
            Session::put('transaksi_retur', $data);
            Session::save();



            return response()->json([
                'message' => 'saved',
                'total_harga' => $total,
                'total_harga_setelah_pajak' => $total
            ]);
        }
    }

    public function save_data_transaksi(Request $request)
    {

        if (session()->has('detail_transaksi_retur') && session()->has('transaksi_retur')) {
            DB::beginTransaction();
            try {
                $day = date('d');
                $month = date('m');
                $year = date('Y');
                $trmutasihd = Trmutasihd::where('Transaksi', 'RETUR PEMBELIAN')->whereYear('Tanggal', $year)->whereMonth('Tanggal', $month)->whereDay('Tanggal', $day)->OrderBy('Tanggal', 'DESC')->first();
                if ($trmutasihd) {
                    $nomor = (int) substr($trmutasihd->Nomor, 14);
                    if ($nomor != 0) {
                        if ($nomor >= 9999) {
                            $nomor = $nomor + 1;
                            $formatNomor = "RP-" . date('Y-m-d') . "-" . $nomor;
                        } else {
                            $nomor = $nomor + 1;
                            $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                            $formatNomor = "RP-" . date('Y-m-d') . "-" . $addzero;
                        }
                    }
                } else {
                    $nomor = 1;
                    $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                    $formatNomor = "RP-" . date('Y-m-d') . "-" . $addzero;
                }

                $trretur = session('transaksi_retur');
                $trmutasihd = new Trmutasihd();
                $trmutasihd->Transaksi = 'RETUR PEMBELIAN';
                $trmutasihd->Nomor = $formatNomor;
                $trmutasihd->Tanggal = date('Y-m-d H:i');
                $trmutasihd->KodeSuppCust =  $trretur["kode"];
                $trmutasihd->Pajak = 0;
                $trmutasihd->LokasiAwal = $trretur["lokasi"];
                $trmutasihd->TotalHarga = $trretur["total_harga"];
                $trmutasihd->UserUpdateSP = auth('web')->user()->UserLogin;
                $trmutasihd->PembayaranTunai = 0;
                $trmutasihd->PembayaranEkop = 0;
                $trmutasihd->StatusPesanan = "";
                $trmutasihd->UserUpdateSP = auth('web')->user()->UserLogin;
                $trmutasihd->TotalHargaSetelahPajak = $trretur["total_harga_setelah_pajak"];
                $trmutasihd->save();

                $datadetail = session('detail_transaksi_retur');
                foreach ($datadetail as $key => $value) {
                    $trmutasidt = new Trmutasidt();
                    $trmutasidt->Transaksi = 'RETUR PEMBELIAN';
                    $trmutasidt->Nomor = $formatNomor;
                    $trmutasidt->Urut = $value["urut"];
                    $trmutasidt->KodeBarang = $value["barang"];
                    $trmutasidt->Keterangan = $value["keterangan"];
                    $trmutasidt->UserUpdate = auth('web')->user()->UserLogin;
                    $trmutasidt->LastUpdate = date('Y-m-d H:i');
                    $trmutasidt->Jumlah = $value['qty'];
                    $trmutasidt->Harga = $value['harga'];
                    $trmutasidt->save();

                    $ceksaldo = Trsaldobarang::where("KodeBarang", $value["barang"])->where('KodeLokasi', auth()->user()->KodeLokasi)->OrderBy("Tanggal", "DESC")->first();

                    $trsaldobarang = new Trsaldobarang();
                    $trsaldobarang->Tanggal = date('Y-m-d H:i:s');
                    $trsaldobarang->KodeBarang = $value["barang"];
                    $trsaldobarang->KodeLokasi = auth()->user()->KodeLokasi;
                    if ($ceksaldo) {
                        $trsaldobarang->Saldo = $ceksaldo->Saldo - $value['qty'];
                    } else {
                        $trsaldobarang->Saldo = $value['qty'];
                    }
                    $trsaldobarang->save();
                }
                session()->forget('detail_transaksi_retur');
                session()->forget('transaksi_retur');
                session()->save();
                DB::commit();
                return redirect()->route('pos.returpembelian.index')->with("success", "Detail dan data transaksi retur pembelian berhasil disimpan");
            } catch (\Exception $th) {
                //throw $th;
                DB::rollBack();
            }
        } else {
            return redirect()->route('pos.returpembelian.index');
        }
    }

    public function getDataDetail(Request $request)
    {
        if ($request->ajax()) {
            $datadetail = session('detail_transaksi_retur');
            $data2 = array();
            if ($datadetail != null) {
                $count = count($datadetail);
                $no = 1;
                foreach ($datadetail as $row) {
                    $sub = array();
                    $sub["urut"] = $row['urut'];
                    $sub["barang"] = $row['barang'];
                    $sub["nama_barang"] = $row['nama_barang'];
                    $sub["harga"] = $row['harga'];
                    $sub["qty"] = $row['qty'];
                    $sub["subtotal"] = $row['subtotal'];
                    $sub["keterangan"] = $row['keterangan'];
                    $sub["action"] = '<button class="edit btn btn-warning btnDetailBarangEdit">Edit</button><button data-urut="' . $row['urut'] . '" class="edit btn btn-danger ml-2 btnDelete">Delete</button>';
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


    public function getDatareturpembelian(Request $request)
    {
        if ($request->ajax()) {
            $datapembelian = [];
            $data2 = array();
            if (session()->has('transaksi_retur')) {
                $pembelian = session('transaksi_retur');
                array_push($datapembelian, $pembelian);
                $count = count($datapembelian);
                $no = 1;
                foreach ($datapembelian as $row) {
                    $sub = array();
                    $sub["transaksi"] = $row['transaksi'];
                    $sub["nomor"] = $row['nomor'];
                    $sub["tanggal"] = $row['tanggal'];
                    $sub["diskon_persen"] = $row['diskon_persen'];
                    $sub["pajak"] = $row['pajak'];
                    $sub["diskon_rp"] = $row['diskon_rp'];
                    $sub["lokasi"] = $row['lokasi'];
                    $sub["keterangan"] = $row['keterangan'];
                    $sub["total_harga_sebelum"] = $row['total_harga_sebelum'];
                    $sub["total_harga"] = $row['total_harga'];
                    $sub["total_harga_setelah_pajak"] = $row['total_harga_setelah_pajak'];
                    $sub["action"] = '<button class="edit btn btn-warning btnedittr">Edit</button>';
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


    public function update_detail_barang(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_urut' => 'required',
            'barang' => 'required',
            'nama_barang' => 'required',
            'harga' => 'required',
            'qty' => 'required',
            'subtotal' => 'required',
            'keterangan' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            $arr = [];
            $trretur = Session::get('transaksi_retur');
            $total = 0;
            $harga = $request->get('harga');
            $harga = str_replace('.', '', $harga);
            $subtotal = $request->get('subtotal');
            $subtotal = str_replace('.', '', $subtotal);
            if (Session::has('detail_transaksi_retur')) {
                $datadetail = Session::get('detail_transaksi_retur');
                foreach ($datadetail as $key => $value) {
                    if ($value["urut"] == $request->get('id_urut')) {
                        $value["urut"] = $value["urut"];
                        $value["barang"] = $request->get("barang");
                        $value["nama_barang"] = $request->get("nama_barang");
                        $value["qty"] = $request->get("qty");
                        $value["subtotal"] = $subtotal;
                        $value["harga"] = $harga;
                        $value["keterangan"] = $request->get("keterangan");
                        $total = $total + $subtotal;
                        array_push($arr, $value);
                    } else {
                        $total = $total + $value["subtotal"];
                        array_push($arr, $value);
                    }
                }
                Session::forget('detail_transaksi_retur');
                Session::put('detail_transaksi_retur', $arr);
                Session::save();
            }

            $data = [
                'transaksi' => $trretur['transaksi'],
                'nomor' => $trretur['nomor'],
                'tanggal' => $trretur['tanggal'],
                'pajak' => $trretur['pajak'],
                'lokasi' => $trretur['lokasi'],
                'keterangan' => $trretur['keterangan'],
                'total_harga_sebelum' => $total,
                'total_harga' => $total,
                'total_harga_setelah_pajak' => $total
            ];
            Session::forget('transaksi_retur');
            Session::put('transaksi_retur', $data);
            Session::save();
            return response()->json([
                'message' => 'saved',
                'total_harga' => $total,
                'total_harga_setelah_pajak' => $total
            ]);
        }
    }

    public function check_session_detail(Request $request)
    {
        if ($request->ajax()) {
            $message = "";
            if (Session::has('detail_transaksi_retur')) {
                $message = "true";
            } else {
                $message = "false";
            }

            return response()->json([
                'message' => $message
            ]);
        }
    }

    public function delete_data($id)
    {
        if (session()->has('detail_transaksi_retur')) {
            $datadetail = Session::get('detail_transaksi_retur');
            $arr = [];
            $total  = 0;
            $trretur = Session::get('transaksi_retur');
            foreach ($datadetail as $key => $value) {
                if ($value["urut"] != $id) {
                    $total = $total + $value["subtotal"];
                    array_push($arr, $value);
                }
            }

            if ($total == 0) {
                Session::forget('detail_transaksi_retur');
            } else {
                Session::put('detail_transaksi_retur', $arr);
                Session::save();
            }

            $data = [
                'transaksi' => $trretur['transaksi'],
                'nomor' => $trretur['nomor'],
                'tanggal' => $trretur['tanggal'],
                'pajak' => $trretur['pajak'],
                'lokasi' => $trretur['lokasi'],
                'keterangan' => $trretur['keterangan'],
                'total_harga_sebelum' => $total,
                'total_harga' => $total,
                'total_harga_setelah_pajak' => $total
            ];
            Session::forget('transaksi_retur');
            Session::put('transaksi_retur', $data);
            Session::save();
            return redirect()->route('pos.returpembelian.index')->with("success", "Detail barang berhasil dihapus");
        }
    }



    public function CekSaldoEkop(Request $request)
    {

        $kode = $request->get('kode');
        $cek = DB::select('call CEKSALDOEKOP(?)', [
            $kode
        ]);

        if (isset($cek[0])) {
            return response()->json($cek[0]);
        } else {
            return response()->json([
                'Saldo' => 0
            ]);
        }
    }
}