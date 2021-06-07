<?php

namespace App\Http\Controllers\Frontend\POS\Transaksi;

use App\Http\Controllers\Controller;
use App\Msbarang;
use Illuminate\Http\Request;
use App\Trmutasihd;
use App\Mslokasi;
use App\Msanggota;
use App\Mssupplier;
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

class StockOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $day = date('d');
        $month = date('m');
        $year = date('Y');

        $trmutasihd = Trmutasihd::where('Transaksi', 'OPNAME')->whereYear('Tanggal', $year)->whereMonth('Tanggal', $month)->whereDay('Tanggal', $day)->OrderBy('Tanggal', 'DESC')->first();
        $opname = Trmutasihd::where('Transaksi', 'OPNAME')->get();
        $mslokasi = Mslokasi::all();
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
                    $formatNomor = "SO-" . date('Y-m-d') . "-" . $nomor;
                } else {
                    $nomor = $nomor + 1;
                    $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                    $formatNomor = "SO-" . date('Y-m-d') . "-" . $addzero;
                }
            }
        } else {
            $nomor = 1;
            $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = "SO-" . date('Y-m-d') . "-" . $addzero;
        }

        if (session()->has('transaksi_opname')) {
            $tropname = session('transaksi_opname');
        } else {
            $data = [
                'transaksi' => 'opname',
                'nomor' => $formatNomor,
                'tanggal' => date('d M y H:i'),
                'diskon_persen' => '0',
                'pajak' => '10',
                'diskon_rp' => '0',
                'lokasi' => auth()->user()->KodeLokasi,
                'keterangan' => '',
                'total_harga_sebelum' => 0,
                'total_harga' => 0,
                'total_harga_setelah_pajak' => 0
            ];
            $tropname = session(['transaksi_opname' => $data]);
            $tropname = session('transaksi_opname');
        }
        return view("frontend.pos.transaksi.stock_opname.index", [
            'formatNomor' => $formatNomor, 'opname' => $opname,
            'mslokasi' => $mslokasi,
            'msbarang' => $msbarang,
            'tropname' => $tropname,
            'msanggota' => $msanggota
        ]);
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

    public function store_transaksi(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'transaksi' => 'required',
                'nomor' => 'required',
                'tanggal' => 'required',
                'lokasi' => 'required',
                'keterangan' => 'nullable',

            ]);

            if ($validator->fails()) {

                return response()->json($validator->errors());
            } else {
                $total = 0;
                if (session()->has('detail_transaksi_opname')) {
                    $datadetail = Session::get('detail_transaksi_opname');
                    foreach ($datadetail as $key => $value) {
                        $total = $total + $value["subtotal"];
                    }
                }
                $data = [
                    'transaksi' => $request->get('transaksi'),
                    'nomor' => $request->get('nomor'),
                    'tanggal' => $request->get('tanggal'),
                    'pajak' => 0,
                    'lokasi' => $request->get('lokasi'),
                    'keterangan' => $request->get('keterangan'),
                    'total_harga_sebelum' => $total,
                    'total_harga' => $total,
                    'total_harga_setelah_pajak' => $total
                ];

                session(['transaksi_opname' => $data]);
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
            $data = [
                'Nama' => $msbarang->Nama,
                'HargaJual' => $msbarang->HargaJual,
                'Saldo' => $trsaldobarang->Saldo
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

            $tropname = Session::get('transaksi_opname');
            $total = 0;
            $harga = $request->get('harga');
            $harga = str_replace('.', '', $harga);
            $subtotal = $request->get('subtotal');
            $subtotal = str_replace('.', '', $subtotal);
            if (Session::has('detail_transaksi_opname')) {
                $datadetail = Session::get('detail_transaksi_opname');
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
                Session::forget('detail_transaksi_opname');
                Session::put('detail_transaksi_opname', $arr);
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
                Session::forget('detail_transaksi_opname');
                Session::put('detail_transaksi_opname', $arr);
                Session::save();
            }

            $data = [
                'transaksi' => $tropname['transaksi'],
                'nomor' => $tropname['nomor'],
                'tanggal' => $tropname['tanggal'],
                'pajak' => $tropname['pajak'],
                'lokasi' => $tropname['lokasi'],
                'keterangan' => $tropname['keterangan'],
                'total_harga_sebelum' => $total,
                'total_harga' =>$total,
                'total_harga_setelah_pajak' => $total
            ];
            Session::forget('transaksi_opname');
            Session::put('transaksi_opname', $data);
            Session::save();



            return response()->json([
                'message' => 'saved',
                'total_harga' =>$total,
                'total_harga_setelah_pajak' => $total
            ]);
        }
    }

    public function save_data_transaksi(Request $request)
    {

        if (session()->has('detail_transaksi_opname') && session()->has('transaksi_opname')) {

            $day = date('d');
            $month = date('m');
            $year = date('Y');
            $trmutasihd = Trmutasihd::where('Transaksi', 'OPNAME')->whereYear('Tanggal', $year)->whereMonth('Tanggal', $month)->whereDay('Tanggal', $day)->OrderBy('Tanggal', 'DESC')->first();
            if ($trmutasihd) {
                $nomor = (int) substr($trmutasihd->Nomor, 14);
                if ($nomor != 0) {
                    if ($nomor >= 9999) {
                        $nomor = $nomor + 1;
                        $formatNomor = "SO-" . date('Y-m-d') . "-" . $nomor;
                    } else {
                        $nomor = $nomor + 1;
                        $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                        $formatNomor = "SO-" . date('Y-m-d') . "-" . $addzero;
                    }
                }
            } else {
                $nomor = 1;
                $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                $formatNomor = "SO-" . date('y-m-d') . "-" . $addzero;
            }

            $tropname = session('transaksi_opname');
            $trmutasihd = new Trmutasihd();
            $trmutasihd->Transaksi = $tropname["transaksi"];
            $trmutasihd->Nomor = $formatNomor;
            $trmutasihd->Tanggal = date('Y-m-d H:i');
            $trmutasihd->KodeSuppCust = null;
            $trmutasihd->Pajak = 0;
            $trmutasihd->LokasiAwal = $tropname["lokasi"];
            $trmutasihd->TotalHarga = $tropname["total_harga"];
            $trmutasihd->UserUpdateSP = auth('web')->user()->UserLogin;
            $trmutasihd->PembayaranTunai = 0;
            $trmutasihd->PembayaranEkop = 0;
            $trmutasihd->StatusPesanan = "";
            $trmutasihd->UserUpdateSP = auth('web')->user()->UserLogin;
            $trmutasihd->TotalHargaSetelahPajak = $tropname["total_harga_setelah_pajak"];
            $trmutasihd->save();



            $datadetail = session('detail_transaksi_opname');
            foreach ($datadetail as $key => $value) {
                $trmutasidt = new Trmutasidt();
                $trmutasidt->Transaksi = 'OPNAME';
                $trmutasidt->Nomor = $formatNomor;
                $trmutasidt->Urut = $value["urut"];
                $trmutasidt->KodeBarang = $value["barang"];
                $trmutasidt->Keterangan = $value["keterangan"];
                $trmutasidt->UserUpdate = auth('web')->user()->UserLogin;
                $trmutasidt->LastUpdate = date('Y-m-d H:i');
                $trmutasidt->Jumlah = $value['qty'];
                $trmutasidt->Harga = $value['subtotal'];
                $trmutasidt->save();

                $trsaldobarang = new Trsaldobarang();
                $trsaldobarang->Tanggal = date('Y-m-d H:i:s');
                $trsaldobarang->KodeBarang = $value["barang"];
                $trsaldobarang->KodeLokasi = auth()->user()->KodeLokasi;
                $trsaldobarang->Saldo = $value['qty'];
                $trsaldobarang->save();
            }
            session()->forget('detail_transaksi_opname');
            session()->forget('transaksi_opname');
            session()->save();
            return redirect()->route('pos.stockopname.index')->with("success", "Detail dan data transaksi opname berhasil disimpan");
        }else{
            return redirect()->route('pos.stockopname.index');
        }
    }

    public function getDataDetail(Request $request)
    {
        if ($request->ajax()) {
            $datadetail = session('detail_transaksi_opname');
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


    public function getDataOpname(Request $request)
    {
        if ($request->ajax()) {
            $datapembelian = [];
            $data2 = array();
            if (session()->has('transaksi_opname')) {
                $pembelian = session('transaksi_opname');
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
            $tropname = Session::get('transaksi_opname');
            $total = 0;
            $harga = $request->get('harga');
            $harga = str_replace('.', '', $harga);
            $subtotal = $request->get('subtotal');
            $subtotal = str_replace('.', '', $subtotal);
            if (Session::has('detail_transaksi_opname')) {
                $datadetail = Session::get('detail_transaksi_opname');
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
                Session::forget('detail_transaksi_opname');
                Session::put('detail_transaksi_opname', $arr);
                Session::save();
            }

            $data = [
                'transaksi' => $tropname['transaksi'],
                'nomor' => $tropname['nomor'],
                'tanggal' => $tropname['tanggal'],
                'pajak' => $tropname['pajak'],
                'lokasi' => $tropname['lokasi'],
                'keterangan' => $tropname['keterangan'],
                'total_harga_sebelum' =>$total,
                'total_harga' =>$total,
                'total_harga_setelah_pajak' => $total
            ];
            Session::forget('transaksi_opname');
            Session::put('transaksi_opname', $data);
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
            if (Session::has('detail_transaksi_opname')) {
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
        if (session()->has('detail_transaksi_opname')) {
            $datadetail = Session::get('detail_transaksi_opname');
            $arr = [];
            $total  = 0;
            $tropname = Session::get('transaksi_opname');
            foreach ($datadetail as $key => $value) {
                if ($value["urut"] != $id) {
                    $total = $total + $value["subtotal"];
                    array_push($arr, $value);
                }
            }

            if ($total == 0) {
                Session::forget('detail_transaksi_opname');
            } else {
                Session::put('detail_transaksi_opname', $arr);
                Session::save();
            }

            $data = [
                'transaksi' => $tropname['transaksi'],
                'nomor' => $tropname['nomor'],
                'tanggal' => $tropname['tanggal'],
                'pajak' => $tropname['pajak'],
                'lokasi' => $tropname['lokasi'],
                'keterangan' => $tropname['keterangan'],
                'total_harga_sebelum' =>$total,
                'total_harga' => $total,
                'total_harga_setelah_pajak' =>$total
            ];
            Session::forget('transaksi_opname');
            Session::put('transaksi_opname', $data);
            Session::save();
            return redirect()->route('pos.stockopname.index')->with("success", "Detail barang berhasil dihapus");
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
