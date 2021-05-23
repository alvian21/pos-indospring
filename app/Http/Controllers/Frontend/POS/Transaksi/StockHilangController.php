<?php

namespace App\Http\Controllers\Frontend\POS\Transaksi;

use App\Http\Controllers\Controller;
use App\Msbarang;
use Illuminate\Http\Request;
use App\Trmutasihd;
use App\Mslokasi;
use App\Mssupplier;
use App\Trmutasidt;
use DataTables;
use Illuminate\Support\Facades\Validator;

class StockHilangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trmutasihd = Trmutasihd::where('Transaksi', 'RUSAK HILA')->OrderBy('Tanggal', 'DESC')->first();
        $stockhilang = Trmutasihd::where('Transaksi', 'RUSAK HILA')->get();
        $mslokasi = Mslokasi::all();
        $mssupplier = Mssupplier::all();
        $msbarang = Msbarang::all();

        if ($trmutasihd) {
            $nomor = (int) substr($trmutasihd->Nomor, 14);
            if ($nomor != 0) {
                if ($nomor >= 9999) {
                    $nomor = $nomor + 1;
                    $formatNomor = "RH-" . date('Y-m-d') . "-" . $nomor;
                } else {
                    $nomor = $nomor + 1;
                    $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                    $formatNomor = "RH-" . date('Y-m-d') . "-" . $addzero;
                }
            }
        } else {
            $nomor = 1;
            $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = "RH-" . date('y-m-d') . "-" . $addzero;
        }

        return view("frontend.pos.transaksi.stock_hilang.index", [
            'formatNomor' => $formatNomor, 'stockhilang' => $stockhilang,
            'mslokasi' => $mslokasi, 'mssupplier' => $mssupplier,
            'msbarang' => $msbarang
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
                'diskon_persen' => $request->get('diskon_persen'),
                'pajak' => $request->get('pajak'),
                'diskon_rp' => $request->get('diskon_rp'),
                'lokasi' => $request->get('lokasi'),
                'keterangan' => $request->get('keterangan'),
                'total_harga' => 0
            ];

            session(['transaksi_stockhilang' => $data]);

            return redirect()->route('pos.stockhilang.index')->with("success", "Transaksi stockhilang berhasil ditambahkan");
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
            $trstockhilang = session('transaksi_stockhilang');
            $total = 0;
            if (session()->has('detail_transaksi_stockhilang')) {
                $datadetail = session('detail_transaksi_stockhilang');
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
                session(['detail_transaksi_stockhilang' => $arr]);
                session()->save();
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
                session(['detail_transaksi_stockhilang' => $arr]);
            }

            $data = [
                'transaksi' => $trstockhilang['transaksi'],
                'nomor' => $trstockhilang['nomor'],
                'tanggal' => $trstockhilang['tanggal'],
                'diskon_persen' => $trstockhilang['diskon_persen'],
                'pajak' => $trstockhilang['pajak'],
                'diskon_rp' => $trstockhilang['diskon_rp'],
                'lokasi' => $trstockhilang['lokasi'],
                'keterangan' => $trstockhilang['keterangan'],
                'total_harga' => $total
            ];

            session(['transaksi_stockhilang' => $data]);
            session()->save();
            return response()->json(['message' => 'saved']);
        }
    }

    public function save_data_transaksi()
    {

        if (session()->has('detail_transaksi_stockhilang') && session()->has('transaksi_stockhilang')) {
            $trstockhilang = session('transaksi_stockhilang');
            $trmutasihd = new Trmutasihd();
            $trmutasihd->Transaksi = "RUSAK HILA";
            $trmutasihd->Nomor = $trstockhilang["nomor"];
            $trmutasihd->Tanggal = date('Y-m-d H:i');
            $trmutasihd->DiskonPersen = $trstockhilang["diskon_persen"];
            $trmutasihd->DiskonTunai = $trstockhilang["diskon_rp"];
            $trmutasihd->Pajak = $trstockhilang["pajak"];
            $trmutasihd->LokasiTujuan = $trstockhilang["lokasi"];
            $trmutasihd->TotalHarga = $trstockhilang["total_harga"];
            $trmutasihd->StatusPesanan = "Dalam Proses";
            $trmutasihd->save();

            $datadetail = session('detail_transaksi_stockhilang');
            foreach ($datadetail as $key => $value) {
                $trmutasidt = new Trmutasidt();
                $trmutasidt->Transaksi = 'RUSAK HILA';
                $trmutasidt->Nomor = $trstockhilang["nomor"];
                $trmutasidt->Urut = $value["urut"];
                $trmutasidt->KodeBarang = $value["barang"];
                $trmutasidt->Keterangan = $value["keterangan"];
                $trmutasidt->DiskonPersen = $value["diskon_persen"];
                $trmutasidt->DiskonTunai = $value["diskon_rp"];
                $trmutasidt->UserUpdate = "Super User";
                $trmutasidt->LastUpdate = date('Y-m-d H:i');
                $trmutasidt->Jumlah = $value['qty'];
                $trmutasidt->Harga = $value['subtotal'];
                $trmutasidt->save();
            }
            session()->forget('detail_transaksi_stockhilang');
            session()->forget('transaksi_stockhilang');
            return redirect()->route('pos.stockhilang.index')->with("success", "Detail dan data transaksi stockhilang berhasil disimpan");
        }
    }


    public function getDataDetail(Request $request)
    {
        if ($request->ajax()) {
            if (session()->has('detail_transaksi_stockhilang')) {
                $datadetail = session('detail_transaksi_stockhilang');
            } else {
                $datadetail = [[
                    'urut' => '',
                    'barang' => '',
                    'nama_barang' => '',
                    'diskon_persen' => '',
                    'diskon_rp' => '',
                    'harga' => '',
                    'subtotal' => '',
                    'keterangan' => '',
                ]];
            }

            return Datatables::of($datadetail)->make(true);
        }
    }


    public function getDataStockHilang(Request $request)
    {
        if ($request->ajax()) {
            $datastockhilang = [];
            if (session()->has('transaksi_stockhilang')) {
                $stockhilang = session('transaksi_stockhilang');
            } else {
                $stockhilang = [
                    'transaksi' => '',
                    'nomor' => '',
                    'tanggal' => '',
                    'diskon_persen' => '',
                    'diskon_rp' => '',
                    'pajak' => '',
                    'total_harga' => '',
                    'keterangan' => '',
                ];
            }

            array_push($datastockhilang, $stockhilang);

            return Datatables::of($datastockhilang)->make(true);
        }
    }
}
