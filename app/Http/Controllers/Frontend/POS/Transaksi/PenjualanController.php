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
use App\Trsaldototalbelanjatunai;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
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

        $trmutasihd = Trmutasihd::where('Transaksi', 'PENJUALAN')->whereYear('Tanggal', $year)->whereMonth('Tanggal', $month)->whereDay('Tanggal', $day)->OrderBy('Tanggal', 'DESC')->first();
        $penjualan = Trmutasihd::where('Transaksi', 'PENJUALAN')->get();
        $mslokasi = Mslokasi::all();
        $msbarang = Msbarang::all();
        $msanggota = DB::table('msanggota')->leftJoin('traktifasi', 'msanggota.Kode', 'traktifasi.Kode')->where('traktifasi.Status', 'aktif')->get();
        if ($trmutasihd) {
            $nomor = (int) substr($trmutasihd->Nomor, 14);
            if ($nomor != 0) {
                if ($nomor >= 9999) {
                    $nomor = $nomor + 1;
                    $formatNomor = "PE-" . date('Y-m-d') . "-" . $nomor;
                } else {
                    $nomor = $nomor + 1;
                    $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                    $formatNomor = "PE-" . date('Y-m-d') . "-" . $addzero;
                }
            }
        } else {
            $nomor = 1;
            $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = "PE-" . date('Y-m-d') . "-" . $addzero;
        }

        if (session()->has('transaksi_penjualan')) {
            $trpenjualan = session('transaksi_penjualan');
        } else {
            $data = [
                'transaksi' => 'PENJUALAN',
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
            $trpenjualan = session(['transaksi_penjualan' => $data]);
            $trpenjualan = session('transaksi_penjualan');
        }
        // session()->forget('detail_transaksi_penjualan');
        // session()->forget('transaksi_penjualan');



        return view("frontend.pos.transaksi.penjualan.index", [
            'formatNomor' => $formatNomor, 'penjualan' => $penjualan,
            'mslokasi' => $mslokasi,
            'msbarang' => $msbarang,
            'trpenjualan' => $trpenjualan,
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
                'diskon_persen' => 'required',
                'pajak' => 'required',
                'diskon_rp' => 'required',
                'lokasi' => 'required',
                'keterangan' => 'nullable',

            ]);

            if ($validator->fails()) {

                return response()->json($validator->errors());
            } else {
                $total = 0;
                if (session()->has('detail_transaksi_penjualan')) {
                    $datadetail = Session::get('detail_transaksi_penjualan');
                    foreach ($datadetail as $key => $value) {
                        $total = $total + $value["subtotal"];
                    }
                }
                //hitung diskon persen
                $hasil = $total;
                $total_sebelum = $total;
                $diskon_rp = $request->get('diskon_rp');
                $diskon_rp = str_replace(".", "", $diskon_rp);
                //hitung diskon persen
                $hasil = $this->diskon_persen($hasil, $request->get('diskon_persen'));
                //diskon rp
                $hasil = $this->diskon_rp($hasil, $request->get('diskon_rp'));
                //pajak
                $pajak = $this->pajak($hasil, $request->get('pajak'));
                if ($pajak <= 0) {
                    $pajak = 0;
                }

                if ($hasil <= 0) {
                    $hasil = 0;
                }
                //cek session
                $data = [
                    'transaksi' => $request->get('transaksi'),
                    'nomor' => $request->get('nomor'),
                    'tanggal' => $request->get('tanggal'),
                    'diskon_persen' => $request->get('diskon_persen'),
                    'pajak' => $request->get('pajak'),
                    'diskon_rp' => $request->get('diskon_rp'),
                    'lokasi' => $request->get('lokasi'),
                    'keterangan' => $request->get('keterangan'),
                    'total_harga_sebelum' => $total_sebelum,
                    'total_harga' => $hasil,
                    'total_harga_setelah_pajak' => $pajak
                ];

                session(['transaksi_penjualan' => $data]);
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
            'diskon_persen' => 'nullable',
            'subtotal' => 'required',
            'diskon_rp' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $arr = [];

            //cek sisa barang

            $trpenjualan = Session::get('transaksi_penjualan');
            $total = 0;
            $diskon_rp = $request->get('diskon_rp');
            $diskon_rp = str_replace('.', '', $diskon_rp);
            $harga = $request->get('harga');
            $harga = str_replace('.', '', $harga);
            $subtotal = $request->get('subtotal');
            $subtotal = str_replace('.', '', $subtotal);
            if (Session::has('detail_transaksi_penjualan')) {
                $datadetail = Session::get('detail_transaksi_penjualan');
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
                    'diskon_persen' => $request->get('diskon_persen'),
                    'subtotal' => $subtotal,
                    'diskon_rp' =>  $diskon_rp,
                    'keterangan' => $request->get('keterangan'),
                ];
                $total = $total + $subtotal;
                array_push($arr, $data);
                Session::forget('detail_transaksi_penjualan');
                Session::put('detail_transaksi_penjualan', $arr);
                Session::save();
            } else {
                $data = [
                    'urut' => 1,
                    'barang' => $request->get('barang'),
                    'nama_barang' => $request->get('nama_barang'),
                    'harga' => $harga,
                    'qty' => $request->get('qty'),
                    'diskon_persen' => $request->get('diskon_persen'),
                    'subtotal' => $subtotal,
                    'diskon_rp' =>  $diskon_rp,
                    'keterangan' => $request->get('keterangan'),
                ];
                $total = $total + $subtotal;
                array_push($arr, $data);
                Session::forget('detail_transaksi_penjualan');
                Session::put('detail_transaksi_penjualan', $arr);
                Session::save();
            }

            //hitung diskon persen
            $hasil = $total;
            $total_sebelum = $total;
            $hasil = $this->diskon_persen($hasil, $trpenjualan['diskon_persen']);
            //diskon rp
            $hasil = $this->diskon_rp($hasil, $trpenjualan['diskon_rp']);
            //pajak
            $pajak = $this->pajak($hasil, $trpenjualan['pajak']);
            if ($pajak <= 0) {
                $pajak = 0;
            }

            if ($hasil <= 0) {
                $hasil = 0;
            }
            $total_sebelum = round($total_sebelum);
            $hasil = round($hasil);
            $pajak = round($pajak);
            $data = [
                'transaksi' => $trpenjualan['transaksi'],
                'nomor' => $trpenjualan['nomor'],
                'tanggal' => $trpenjualan['tanggal'],
                'diskon_persen' => $trpenjualan['diskon_persen'],
                'pajak' => $trpenjualan['pajak'],
                'diskon_rp' => $trpenjualan['diskon_rp'],
                'lokasi' => $trpenjualan['lokasi'],
                'keterangan' => $trpenjualan['keterangan'],
                'total_harga_sebelum' => $total_sebelum,
                'total_harga' => $hasil,
                'total_harga_setelah_pajak' => $pajak
            ];
            Session::forget('transaksi_penjualan');
            Session::put('transaksi_penjualan', $data);
            Session::save();

            return response()->json([
                'message' => 'saved',
                'total_harga' => $hasil,
                'total_harga_setelah_pajak' => $pajak
            ]);
        }
    }

    public function save_data_transaksi(Request $request)
    {

        if (session()->has('detail_transaksi_penjualan') && session()->has('transaksi_penjualan')) {

            $day = date('d');
            $month = date('m');
            $year = date('Y');
            $pembayaran_ekop = $request->get('pembayaran_ekop');
            $pembayaran_ekop = str_replace('.', '', $pembayaran_ekop);
            $pembayaran_tunai = $request->get('pembayaran_tunai');
            $pembayaran_tunai = str_replace('.', '', $pembayaran_tunai);
            $barcode_cust = $request->get('barcode_cust');
            $trmutasihd = Trmutasihd::where('Transaksi', 'PENJUALAN')->whereYear('Tanggal', $year)->whereMonth('Tanggal', $month)->whereDay('Tanggal', $day)->OrderBy('Tanggal', 'DESC')->first();
            if ($trmutasihd) {
                $nomor = (int) substr($trmutasihd->Nomor, 14);
                if ($nomor != 0) {
                    if ($nomor >= 9999) {
                        $nomor = $nomor + 1;
                        $formatNomor = "PE-" . date('Y-m-d') . "-" . $nomor;
                    } else {
                        $nomor = $nomor + 1;
                        $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                        $formatNomor = "PE-" . date('Y-m-d') . "-" . $addzero;
                    }
                }
            } else {
                $nomor = 1;
                $addzero = str_pad($nomor, 4, '0', STR_PAD_LEFT);
                $formatNomor = "PE-" . date('y-m-d') . "-" . $addzero;
            }
            $trpenjualan = session('transaksi_penjualan');
            $trmutasihd = new Trmutasihd();
            $trmutasihd->Transaksi = $trpenjualan["transaksi"];
            $trmutasihd->Nomor = $formatNomor;
            $trmutasihd->Tanggal = date('Y-m-d H:i');
            $trmutasihd->KodeSuppCust = $barcode_cust;
            $trmutasihd->DiskonPersen = $trpenjualan["diskon_persen"];
            $trmutasihd->DiskonTunai = $trpenjualan["diskon_rp"];
            $trmutasihd->Pajak = $trpenjualan["pajak"];
            $trmutasihd->LokasiTujuan = $trpenjualan["lokasi"];
            $trmutasihd->TotalHarga = $trpenjualan["total_harga"];
            $trmutasihd->UserUpdateSP = auth('web')->user()->UserLogin;
            if (($pembayaran_tunai != '' || $pembayaran_tunai > 0) && $pembayaran_ekop != $trpenjualan["total_harga_setelah_pajak"]) {
                $trmutasihd->PembayaranTunai = $pembayaran_tunai;

                //saldototalbelanjatunai
                $trsaldobelanjatunai = new Trsaldototalbelanjatunai();
                $trsaldobelanjatunai->Tanggal = date('Y-m-d H:i:s');
                $trsaldobelanjatunai->KodeUser = $barcode_cust;
                $trsaldobelanjatunai->Saldo = $pembayaran_tunai;
                $trsaldobelanjatunai->save();
            }
            if ($pembayaran_ekop != '' || $pembayaran_ekop > 0) {
                $cek = DB::select('call CEKSALDOEKOP(?)', [
                    $barcode_cust
                ]);
                $trmutasihd->PembayaranEkop = $pembayaran_ekop;
                $trsaldoekop = new Trsaldoekop();
                $trsaldoekop->Tanggal = date('Y-m-d H:i:s');
                $trsaldoekop->KodeUser = $barcode_cust;
                $trsaldoekop->Saldo = $cek[0]->Saldo -  $pembayaran_ekop;
                $trsaldoekop->save();
            }
            $trmutasihd->StatusPesanan = "Dalam Proses";
            $trmutasihd->UserUpdateSP = auth('web')->user()->UserLogin;
            $trmutasihd->TotalHargaSetelahPajak = $trpenjualan["total_harga_setelah_pajak"];
            $trmutasihd->save();

            //trsaldototalbelanja
            $trsaldototalbelanja = new Trsaldototalbelanja();
            $trsaldototalbelanja->Tanggal = date('Y-m-d H:i:s');
            $trsaldototalbelanja->KodeUser = $barcode_cust;
            $trsaldototalbelanja->Saldo = $trpenjualan["total_harga_setelah_pajak"];
            $trsaldototalbelanja->save();


            $datadetail = session('detail_transaksi_penjualan');
            foreach ($datadetail as $key => $value) {
                $trmutasidt = new Trmutasidt();
                $trmutasidt->Transaksi = 'PENJUALAN';
                $trmutasidt->Nomor = $formatNomor;
                $trmutasidt->Urut = $value["urut"];
                $trmutasidt->KodeBarang = $value["barang"];
                $trmutasidt->Keterangan = $value["keterangan"];
                $trmutasidt->DiskonPersen = $value["diskon_persen"];
                $trmutasidt->DiskonTunai = $value["diskon_rp"];
                $trmutasidt->UserUpdate = auth('web')->user()->UserLogin;
                $trmutasidt->LastUpdate = date('Y-m-d H:i');
                $trmutasidt->Jumlah = $value['qty'];
                $trmutasidt->Harga = $value['subtotal'];
                $trmutasidt->save();
                $getstok = Trsaldobarang::where('KodeBarang', $value["barang"])->where('KodeLokasi', auth()->user()->KodeLokasi)->OrderBy('Tanggal', 'DESC')->first();
                $trsaldobarang = new Trsaldobarang();
                $trsaldobarang->Tanggal = date('Y-m-d H:i:s');
                $trsaldobarang->KodeBarang = $value["barang"];
                $trsaldobarang->Saldo = $getstok->Saldo - $value["qty"];
                $trsaldobarang->KodeLokasi = auth()->user()->KodeLokasi;
                $trsaldobarang->save();
            }
            session()->forget('detail_transaksi_penjualan');
            session()->forget('transaksi_penjualan');
            session()->save();
            return redirect()->route('pos.penjualan.index')->with("success", "Detail dan data transaksi penjualan berhasil disimpan");
        }
    }

    public function getDataDetail(Request $request)
    {
        if ($request->ajax()) {
            $datadetail = session('detail_transaksi_penjualan');
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
                    $sub["diskon_persen"] = $row['diskon_persen'];
                    $sub["subtotal"] = $row['subtotal'];
                    $sub["diskon_rp"] = $row['diskon_rp'];
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


    public function getDataPenjualan(Request $request)
    {
        if ($request->ajax()) {
            $datapembelian = [];
            $data2 = array();
            if (session()->has('transaksi_penjualan')) {
                $pembelian = session('transaksi_penjualan');
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

    public function update_transaksi(Request $request)
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

            $trpenjualan = Session::get('transaksi_penjualan');
            $total = 0;
            if (session()->has('detail_transaksi_penjualan')) {
                $datadetail = Session::get('detail_transaksi_penjualan');
                foreach ($datadetail as $key => $value) {
                    $total = $total + $value["subtotal"];
                }
            }


            //hitung diskon persen
            $hasil = $total;
            $total_sebelum = $total;
            $diskon_rp = $request->get('diskon_rp');
            $diskon_rp = str_replace('.', '', $diskon_rp);
            //hitung diskon persen
            $hasil = $this->diskon_persen($hasil, $request->get('diskon_persen'));
            //diskon rp
            $hasil = $this->diskon_rp($hasil, $diskon_rp);
            //pajak
            $pajak = $this->pajak($hasil, $request->get('pajak'));
            if ($pajak <= 0) {
                $pajak = 0;
            }

            if ($hasil <= 0) {
                $hasil = 0;
            }
            $total_sebelum = round($total_sebelum);
            $hasil = round($hasil);
            $pajak = round($pajak);
            $data = [
                'transaksi' => $request->get('transaksi'),
                'nomor' => $request->get('nomor'),
                'tanggal' => $request->get('tanggal'),
                'diskon_persen' => $request->get('diskon_persen'),
                'pajak' => $request->get('pajak'),
                'diskon_rp' => $request->get('diskon_rp'),
                'lokasi' => $request->get('lokasi'),
                'keterangan' => $request->get('keterangan'),
                'total_harga_sebelum' => $total_sebelum,
                'total_harga' => $hasil,
                'total_harga_setelah_pajak' => $pajak
            ];
            session(['transaksi_penjualan' => $data]);

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
            return response()->json($validator->errors());
        } else {
            $arr = [];
            $trpenjualan = Session::get('transaksi_penjualan');
            $total = 0;
            $diskon_rp = $request->get('diskon_rp');
            $diskon_rp = str_replace('.', '', $diskon_rp);
            $harga = $request->get('harga');
            $harga = str_replace('.', '', $harga);
            $subtotal = $request->get('subtotal');
            $subtotal = str_replace('.', '', $subtotal);
            if (Session::has('detail_transaksi_penjualan')) {
                $datadetail = Session::get('detail_transaksi_penjualan');
                foreach ($datadetail as $key => $value) {
                    if ($value["urut"] == $request->get('id_urut')) {
                        $value["urut"] = $value["urut"];
                        $value["barang"] = $request->get("barang");
                        $value["nama_barang"] = $request->get("nama_barang");
                        $value["qty"] = $request->get("qty");
                        $value["diskon_persen"] = $request->get("diskon_persen");
                        $value["subtotal"] = $subtotal;
                        $value["diskon_rp"] = $diskon_rp;
                        $value["harga"] = $harga;
                        $value["keterangan"] = $request->get("keterangan");
                        $total = $total + $subtotal;
                        array_push($arr, $value);
                    } else {
                        $total = $total + $value["subtotal"];
                        array_push($arr, $value);
                    }
                }
                Session::forget('detail_transaksi_penjualan');
                Session::put('detail_transaksi_penjualan', $arr);
                Session::save();
            }

            //hitung diskon persen
            $hasil = $total;
            $total_sebelum = $total;
            //hitung diskon persen
            $hasil = $this->diskon_persen($hasil, $trpenjualan['diskon_persen']);
            //diskon rp
            $hasil = $this->diskon_rp($hasil, $trpenjualan['diskon_rp']);
            //pajak
            $pajak = $this->pajak($hasil, $trpenjualan['pajak']);
            if ($pajak <= 0) {
                $pajak = 0;
            }

            if ($hasil <= 0) {
                $hasil = 0;
            }
            $total_sebelum = round($total_sebelum);
            $hasil = round($hasil);
            $pajak = round($pajak);
            $data = [
                'transaksi' => $trpenjualan['transaksi'],
                'nomor' => $trpenjualan['nomor'],
                'tanggal' => $trpenjualan['tanggal'],
                'diskon_persen' => $trpenjualan['diskon_persen'],
                'pajak' => $trpenjualan['pajak'],
                'diskon_rp' => $trpenjualan['diskon_rp'],
                'lokasi' => $trpenjualan['lokasi'],
                'keterangan' => $trpenjualan['keterangan'],
                'total_harga_sebelum' => $total_sebelum,
                'total_harga' => $hasil,
                'total_harga_setelah_pajak' => $pajak
            ];
            Session::forget('transaksi_penjualan');
            Session::put('transaksi_penjualan', $data);
            Session::save();
            return response()->json([
                'message' => 'saved',
                'total_harga' => $hasil,
                'total_harga_setelah_pajak' => $pajak
            ]);
        }
    }

    public function check_session_detail(Request $request)
    {
        if ($request->ajax()) {
            $message = "";
            if (Session::has('detail_transaksi_penjualan')) {
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
        if (session()->has('detail_transaksi_penjualan')) {
            $datadetail = Session::get('detail_transaksi_penjualan');
            $arr = [];
            $total  = 0;
            $trpenjualan = Session::get('transaksi_penjualan');
            foreach ($datadetail as $key => $value) {
                if ($value["urut"] != $id) {
                    $total = $total + $value["subtotal"];
                    array_push($arr, $value);
                }
            }

            if ($total == 0) {
                Session::forget('detail_transaksi_penjualan');
            } else {
                Session::put('detail_transaksi_penjualan', $arr);
                Session::save();
            }

            $hasil = $total;
            $total_sebelum = $total;
            //hitung diskon persen
            $hasil = $this->diskon_persen($hasil, $trpenjualan['diskon_persen']);
            //diskon rp
            $hasil = $this->diskon_rp($hasil, $trpenjualan['diskon_rp']);
            //pajak
            $pajak = $this->pajak($hasil, $trpenjualan['pajak']);
            if ($pajak <= 0) {
                $pajak = 0;
            }

            if ($hasil <= 0) {
                $hasil = 0;
            }
            $data = [
                'transaksi' => $trpenjualan['transaksi'],
                'nomor' => $trpenjualan['nomor'],
                'tanggal' => $trpenjualan['tanggal'],
                'diskon_persen' => $trpenjualan['diskon_persen'],
                'pajak' => $trpenjualan['pajak'],
                'diskon_rp' => $trpenjualan['diskon_rp'],
                'lokasi' => $trpenjualan['lokasi'],
                'keterangan' => $trpenjualan['keterangan'],
                'total_harga_sebelum' => $total_sebelum,
                'total_harga' => $hasil,
                'total_harga_setelah_pajak' => $pajak
            ];
            Session::forget('transaksi_penjualan');
            Session::put('transaksi_penjualan', $data);
            Session::save();
            return redirect()->route('pos.penjualan.index')->with("success", "Detail barang berhasil dihapus");
        }
    }

    public function diskon_persen($total, $diskon)
    {
        if ($diskon > 0 || $diskon != '') {
            $diskon_persen = $diskon;
            $hitung = ($diskon_persen / 100) * $total;
            $hasil = $total - $hitung;
            return round($hasil);
        } else {
            return $total;
        }
    }

    public function diskon_rp($total, $diskon)
    {
        if ($diskon > 0 || $diskon != '') {
            $diskon_rp = $diskon;
            $hasil = $total - $diskon_rp;
            return round($hasil);
        } else {
            return $total;
        }
    }

    public function pajak($total, $pajak)
    {
        if ($pajak > 0  || $pajak != '') {
            $pajak = $total + (($pajak / 100) * $total);
            return round($pajak);
        } else {
            return $total;
        }
    }

    public function CekSaldoEkop(Request $request)
    {

        $kode = $request->get('kode');
        $cek = DB::select('call CEKSALDOEKOP(?)', [
            $kode
        ]);

        return response()->json($cek[0]);
    }
}
