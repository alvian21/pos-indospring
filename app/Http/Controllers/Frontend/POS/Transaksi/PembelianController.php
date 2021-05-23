<?php

namespace App\Http\Controllers\Frontend\POS\Transaksi;

use App\Http\Controllers\Controller;
use App\Msbarang;
use Illuminate\Http\Request;
use App\Trmutasihd;
use App\Mslokasi;
use App\Mssupplier;
use App\Trmutasidt;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Illuminate\Support\Facades\Session;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $trmutasihd = Trmutasihd::where('Transaksi', 'PEMBELIAN')->OrderBy('Tanggal', 'DESC')->first();
        $pembelian = Trmutasihd::where('Transaksi', 'PEMBELIAN')->get();
        $mslokasi = Mslokasi::all();
        $mssupplier = Mssupplier::all();
        $msbarang = Msbarang::all();

        if ($trmutasihd) {
            $nomor = (int) substr($trmutasihd->Nomor, 14);
            if ($nomor != 0) {
                if ($nomor >= 9999) {
                    $nomor = $nomor + 1;
                    $formatNomor = "BE-" . date('Y-m-d') . "-" . $nomor;
                } else {
                    $nomor = $nomor + 1;
                    $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                    $formatNomor = "BE-" . date('Y-m-d') . "-" . $addzero;
                }
            }
        } else {
            $nomor = 1;
            $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = "BE-" . date('y-m-d') . "-" . $addzero;
        }
        // session()->forget('detail_transaksi_pembelian');
        // session()->forget('transaksi_pembelian');



        return view("frontend.pos.transaksi.pembelian.index", [
            'formatNomor' => $formatNomor, 'pembelian' => $pembelian,
            'mslokasi' => $mslokasi, 'mssupplier' => $mssupplier,
            'msbarang' => $msbarang,

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
        $validator = Validator::make($request->all(), [
            'transaksi' => 'required',
            'nomor' => 'required',
            'tanggal' => 'required',
            'supplier' => 'required',
            'diskon_persen' => 'required',
            'pajak' => 'required',
            'diskon_rp' => 'required',
            'lokasi' => 'required',
            'keterangan' => 'nullable',

        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator->errors());
        } else {
            $kodesup = Mssupplier::where("Kode", $request->get('supplier'))->first();
            $data = [
                'transaksi' => $request->get('transaksi'),
                'nomor' => $request->get('nomor'),
                'tanggal' => $request->get('tanggal'),
                'kode' => $request->get('supplier'),
                'supplier' => $kodesup->Nama,
                'diskon_persen' => $request->get('diskon_persen'),
                'pajak' => $request->get('pajak'),
                'diskon_rp' => $request->get('diskon_rp'),
                'lokasi' => $request->get('lokasi'),
                'keterangan' => $request->get('keterangan'),
                'total_harga' => 0
            ];

            session(['transaksi_pembelian' => $data]);

            return redirect()->route('pos.pembelian.index')->with("success", "Transaksi pembelian berhasil ditambahkan");
        }
    }

    public function get_data_barang(Request $request)
    {
        if ($request->ajax()) {
            $msbarang = Msbarang::where('Kode', $request->kode_barang)->first();
            return response()->json($msbarang);
        }
    }

    public function store_detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang' => 'required',
            'nama_barang' => 'required',
            'harga' => 'required',
            'qty' => 'required',
            'diskon_persen' => 'nullable',
            'subtotal' => 'required',
            'diskon_rp' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $arr = [];
            $trpembelian = Session::get('transaksi_pembelian');
            $total = 0;
            if (Session::has('detail_transaksi_pembelian')) {
                $datadetail = Session::get('detail_transaksi_pembelian');
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
                    'harga' => $request->get('harga'),
                    'qty' => $request->get('qty'),
                    'diskon_persen' => $request->get('diskon_persen'),
                    'subtotal' => $request->get('subtotal'),
                    'diskon_rp' => $request->get('diskon_rp'),
                    'keterangan' => $request->get('keterangan'),
                ];
                $total = $total + $request->get('subtotal');
                array_push($arr, $data);
                Session::forget('detail_transaksi_pembelian');
                Session::put('detail_transaksi_pembelian', $arr);
                Session::save();
            } else {
                $data = [
                    'urut' => 1,
                    'barang' => $request->get('barang'),
                    'nama_barang' => $request->get('nama_barang'),
                    'harga' => $request->get('harga'),
                    'qty' => $request->get('qty'),
                    'diskon_persen' => $request->get('diskon_persen'),
                    'subtotal' => $request->get('subtotal'),
                    'diskon_rp' => $request->get('diskon_rp'),
                    'keterangan' => $request->get('keterangan'),
                ];
                $total = $total + $request->get('subtotal');
                array_push($arr, $data);
                Session::forget('detail_transaksi_pembelian');
                Session::put('detail_transaksi_pembelian', $arr);
                Session::save();
            }

            //hitung diskon persen
            $hasil = $total;
            if ($trpembelian['diskon_persen'] != '0') {
                $diskon_persen = $trpembelian['diskon_persen'];
                $hitung = ($diskon_persen / 100) * $total;
                $hasil = $total - $hitung;
            }
            //diskon rp
            if ($trpembelian['diskon_rp'] != '0') {
                $diskon_rp = $trpembelian['diskon_rp'];
                $hasil = $hasil - $diskon_rp;
            }
            //pajak
            $hasil = $hasil + $trpembelian['pajak'];
            $data = [
                'transaksi' => $trpembelian['transaksi'],
                'nomor' => $trpembelian['nomor'],
                'tanggal' => $trpembelian['tanggal'],
                'kode' => $trpembelian['kode'],
                'supplier' => $trpembelian['supplier'],
                'diskon_persen' => $trpembelian['diskon_persen'],
                'pajak' => $trpembelian['pajak'],
                'diskon_rp' => $trpembelian['diskon_rp'],
                'lokasi' => $trpembelian['lokasi'],
                'keterangan' => $trpembelian['keterangan'],
                'total_harga' => $hasil
            ];
            Session::forget('transaksi_pembelian');
            Session::put('transaksi_pembelian', $data);
            Session::save();
            return response()->json(['message' => 'saved']);
        }
    }

    public function save_data_transaksi()
    {

        if (session()->has('detail_transaksi_pembelian') && session()->has('transaksi_pembelian')) {
            $trpembelian = session('transaksi_pembelian');
            $trmutasihd = new Trmutasihd();
            $trmutasihd->Transaksi = $trpembelian["transaksi"];
            $trmutasihd->Nomor = $trpembelian["nomor"];
            $trmutasihd->Tanggal = date('Y-m-d H:i');
            $trmutasihd->KodeSuppCust = $trpembelian["kode"];
            $trmutasihd->DiskonPersen = $trpembelian["diskon_persen"];
            $trmutasihd->DiskonTunai = $trpembelian["diskon_rp"];
            $trmutasihd->Pajak = $trpembelian["pajak"];
            $trmutasihd->LokasiTujuan = $trpembelian["lokasi"];
            $trmutasihd->TotalHarga = $trpembelian["total_harga"];
            $trmutasihd->UserUpdateSP = session('nama_anggota');
            $trmutasihd->StatusPesanan = "Dalam Proses";
            $trmutasihd->save();

            $datadetail = session('detail_transaksi_pembelian');
            foreach ($datadetail as $key => $value) {
                $trmutasidt = new Trmutasidt();
                $trmutasidt->Transaksi = 'PEMBELIAN';
                $trmutasidt->Nomor = $trpembelian["nomor"];
                $trmutasidt->Urut = $value["urut"];
                $trmutasidt->KodeBarang = $value["barang"];
                $trmutasidt->Keterangan = $value["keterangan"];
                $trmutasidt->DiskonPersen = $value["diskon_persen"];
                $trmutasidt->DiskonTunai = $value["diskon_rp"];
                $trmutasidt->UserUpdate = session('nama_anggota');
                $trmutasidt->LastUpdate = date('Y-m-d H:i');
                $trmutasidt->Jumlah = $value['qty'];
                $trmutasidt->Harga = $value['subtotal'];
                $trmutasidt->save();
            }
            session()->forget('detail_transaksi_pembelian');
            session()->forget('transaksi_pembelian');
            return redirect()->route('pos.pembelian.index')->with("success", "Detail dan data transaksi pembelian berhasil disimpan");
        }
    }

    public function getDataDetail(Request $request)
    {
        if ($request->ajax()) {
            if (session()->has('detail_transaksi_pembelian')) {
                $datadetail = session('detail_transaksi_pembelian');
            } else {
                $datadetail = [[
                    'urut' => '',
                    'barang' => '',
                    'nama_barang' => '',
                    'diskon_persen' => '',
                    'qty' => '',
                    'diskon_rp' => '',
                    'harga' => '',
                    'subtotal' => '',
                    'keterangan' => '',
                ]];
            }

            return Datatables::of($datadetail)->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<button class="edit btn btn-warning btnDetailBarangEdit">Edit</button>';
                    $btn .= '<button class="edit btn btn-danger ml-2 btnDelete">Delete</button>';

                    return $btn;
                })
                ->rawColumns(['action'])->make(true);
        }
    }


    public function getDataPembelian(Request $request)
    {
        if ($request->ajax()) {
            $datapembelian = [];
            if (session()->has('transaksi_pembelian')) {
                $pembelian = session('transaksi_pembelian');
            } else {
                $pembelian = [
                    'transaksi' => '',
                    'nomor' => '',
                    'tanggal' => '',
                    'kode' => '',
                    'supplier' => '',
                    'diskon_persen' => '',
                    'diskon_rp' => '',
                    'pajak' => '',
                    'total_harga' => '',
                    'keterangan' => '',
                ];
            }

            array_push($datapembelian, $pembelian);

            return Datatables::of($datapembelian)->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<button class="edit btn btn-warning btnedittr">Edit</button>';

                    return $btn;
                })
                ->rawColumns(['action'])->make(true);
        }
    }

    public function update_transaksi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaksi' => 'required',
            'nomor' => 'required',
            'tanggal' => 'required',
            'supplier' => 'required',
            'diskon_persen' => 'required',
            'pajak' => 'required',
            'diskon_rp' => 'required',
            'lokasi' => 'required',
            'keterangan' => 'nullable',

        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator->errors());
        } else {
            $kodesup = Mssupplier::where("Kode", $request->get('supplier'))->first();
            $trpembelian = Session::get('transaksi_pembelian');
            $total = 0;
            $datadetail = Session::get('detail_transaksi_pembelian');
            foreach ($datadetail as $key => $value) {
                $total = $total + $value["subtotal"];
            }

            //hitung diskon persen
            $hasil = $total;
            if ($request->get('diskon_persen') != '0') {
                $diskon_persen = $request->get('diskon_persen');
                $hitung = ($diskon_persen / 100) * $total;
                $hasil = $total - $hitung;
            }
            //diskon rp
            if ($request->get('diskon_rp') != '0') {
                $diskon_rp = $request->get('diskon_rp');
                $hasil = $hasil - $diskon_rp;
            }
            //pajak
            $hasil = $hasil + $request->get('pajak');
            $data = [
                'transaksi' => $request->get('transaksi'),
                'nomor' => $request->get('nomor'),
                'tanggal' => $request->get('tanggal'),
                'kode' => $request->get('supplier'),
                'supplier' => $kodesup->Nama,
                'diskon_persen' => $request->get('diskon_persen'),
                'pajak' => $request->get('pajak'),
                'diskon_rp' => $request->get('diskon_rp'),
                'lokasi' => $request->get('lokasi'),
                'keterangan' => $request->get('keterangan'),
                'total_harga' => $hasil
            ];
            session(['transaksi_pembelian' => $data]);

            return redirect()->route('pos.pembelian.index')->with("success", "Transaksi pembelian berhasil diupdate");
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
            'diskon_persen' => 'nullable',
            'subtotal' => 'required',
            'diskon_rp' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $arr = [];
            $trpembelian = Session::get('transaksi_pembelian');
            $total = 0;
            if (Session::has('detail_transaksi_pembelian')) {
                $datadetail = Session::get('detail_transaksi_pembelian');
                foreach ($datadetail as $key => $value) {
                    if ($value["urut"] == $request->get('id_urut')) {
                        $value["urut"] = $value["urut"];
                        $value["barang"] = $request->get("barang");
                        $value["nama_barang"] = $request->get("nama_barang");
                        $value["qty"] = $request->get("qty");
                        $value["diskon_persen"] = $request->get("diskon_persen");
                        $value["subtotal"] = $request->get('subtotal');
                        $value["diskon_rp"] = $request->get("diskon_rp");
                        $value["diskon_rp"] = $request->get("diskon_rp");
                        $value["keterangan"] = $request->get("keterangan");
                        $total = $total + $request->get('subtotal');
                        array_push($arr, $value);
                    } else {
                        $total = $total + $value["subtotal"];
                        array_push($arr, $value);
                    }
                }
                Session::forget('detail_transaksi_pembelian');
                Session::put('detail_transaksi_pembelian', $arr);
                Session::save();
            }

            //hitung diskon persen
            $hasil = $total;
            if ($trpembelian['diskon_persen'] != '0') {
                $diskon_persen = $trpembelian['diskon_persen'];
                $hitung = ($diskon_persen / 100) * $total;
                $hasil = $total - $hitung;
            }
            //diskon rp
            if ($trpembelian['diskon_rp'] != '0') {
                $diskon_rp = $trpembelian['diskon_rp'];
                $hasil = $hasil - $diskon_rp;
            }
            //pajak
            $hasil = $hasil + $trpembelian['pajak'];
            $data = [
                'transaksi' => $trpembelian['transaksi'],
                'nomor' => $trpembelian['nomor'],
                'tanggal' => $trpembelian['tanggal'],
                'kode' => $trpembelian['kode'],
                'supplier' => $trpembelian['supplier'],
                'diskon_persen' => $trpembelian['diskon_persen'],
                'pajak' => $trpembelian['pajak'],
                'diskon_rp' => $trpembelian['diskon_rp'],
                'lokasi' => $trpembelian['lokasi'],
                'keterangan' => $trpembelian['keterangan'],
                'total_harga' => $hasil
            ];
            Session::forget('transaksi_pembelian');
            Session::put('transaksi_pembelian', $data);
            Session::save();
            return response()->json(['message' => 'saved']);
        }
    }
}
