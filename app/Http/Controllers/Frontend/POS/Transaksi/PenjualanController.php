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

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trmutasihd = Trmutasihd::where('Transaksi', 'PENJUALAN')->OrderBy('Tanggal', 'DESC')->first();
        $penjualan = Trmutasihd::where('Transaksi', 'PENJUALAN')->get();
        $mslokasi = Mslokasi::all();
        $mssupplier = Mssupplier::all();
        $msbarang = Msbarang::all();
        $trpenjualan = session('transaksi_penjualan');
        $datadetail = session('detail_transaksi_penjualan');
        $nomor = (int) substr($trmutasihd->Nomor, 14);
        if ($nomor != 0) {
            if ($nomor >= 9999) {
                $nomor = $nomor + 1;
                $formatNomor = "JU-" . date('y-m-d') . "-" . $nomor;
            } else {
                $nomor = $nomor + 1;
                $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                $formatNomor = "JU-" . date('y-m-d') . "-" . $addzero;
            }
        } else {
            $nomor = 1;
            $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = "JU-" . date('y-m-d') . "-" . $addzero;
        }
        $trpenjualan = json_decode(json_encode($trpenjualan));
        $datadetail = json_decode(json_encode($datadetail));
        return view("frontend.pos.transaksi.penjualan.index",[
            'formatNomor' => $formatNomor, 'penjualan' => $penjualan,
            'mslokasi' => $mslokasi, 'mssupplier' => $mssupplier,
            'trpenjualan' => $trpenjualan, 'msbarang' => $msbarang,
            'datadetail' => $datadetail
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

            session(['transaksi_penjualan' => $data]);

            return redirect()->route('pos.penjualan.index')->with("success", "Transaksi penjualan berhasil ditambahkan");
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
            $trpenjualan = session('transaksi_penjualan');
            $total = 0;
            if (session()->has('detail_transaksi_penjualan')) {
                $datadetail = session('detail_transaksi_penjualan');
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
                session(['detail_transaksi_penjualan' => $arr]);
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
                session(['detail_transaksi_penjualan' => $arr]);
            }

            $data = [
                'transaksi' => $trpenjualan['transaksi'],
                'nomor' => $trpenjualan['nomor'],
                'tanggal' => $trpenjualan['tanggal'],
                'kode' => $trpenjualan['kode'],
                'supplier' => $trpenjualan['supplier'],
                'diskon_persen' => $trpenjualan['diskon_persen'],
                'pajak' => $trpenjualan['pajak'],
                'diskon_rp' => $trpenjualan['diskon_rp'],
                'lokasi' => $trpenjualan['lokasi'],
                'keterangan' => $trpenjualan['keterangan'],
                'total_harga' => $total
            ];

            session(['transaksi_penjualan' => $data]);
            session()->save();
            return redirect()->route('pos.penjualan.index')->with("success", "Detail transaksi penjualan berhasil ditambahkan");
        }
    }

    public function save_data_transaksi()
    {

        if (session()->has('detail_transaksi_penjualan') && session()->has('transaksi_penjualan')) {
            $trpenjualan = session('transaksi_penjualan');
            $trmutasihd = new Trmutasihd();
            $trmutasihd->Transaksi = $trpenjualan["transaksi"];
            $trmutasihd->Nomor = $trpenjualan["nomor"];
            $trmutasihd->Tanggal = date('Y-m-d H:i');
            $trmutasihd->KodeSuppCust = $trpenjualan["kode"];
            $trmutasihd->DiskonPersen = $trpenjualan["diskon_persen"];
            $trmutasihd->DiskonTunai = $trpenjualan["diskon_rp"];
            $trmutasihd->Pajak = $trpenjualan["pajak"];
            $trmutasihd->LokasiTujuan = $trpenjualan["lokasi"];
            $trmutasihd->TotalHarga = $trpenjualan["total_harga"];
            $trmutasihd->StatusPesanan = "Dalam Proses";
            $trmutasihd->save();

            $datadetail = session('detail_transaksi_penjualan');
            foreach ($datadetail as $key => $value) {
               $trmutasidt = new Trmutasidt();
               $trmutasidt->Transaksi = 'PENJUALAN';
               $trmutasidt->Nomor = $trpenjualan["nomor"];
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
            session()->forget('detail_transaksi_penjualan');
            session()->forget('transaksi_penjualan');
            return redirect()->route('pos.penjualan.index')->with("success", "Detail dan data transaksi penjualan berhasil disimpan");
        }
    }
}
