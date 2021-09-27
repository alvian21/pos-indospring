<?php

namespace App\Http\Controllers\Frontend\POS\Transaksi;

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use App\Http\Controllers\Controller;
use App\Msbarang;
use Illuminate\Http\Request;
use App\Trmutasihd;
use App\Mslokasi;
use App\Msanggota;
use App\Mssupplier;
use App\Trmutasidt;
use App\Mssetting;
use App\Trsaldoekop;
use Illuminate\Support\Facades\Session;
use DataTables;
use App\Trsaldobarang;
use App\Trsaldototalbelanja;
use App\Trsaldototalbelanjakredit;
use App\Trsaldototalbelanjatunai;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;
use Mike42\Escpos\Printer;

class KasirController extends Controller
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
        $Pajakkasir = Mssetting::where('Kode', 'PajakPenjualan')->first();
        $DiskonRpkasirReadOnly = Mssetting::where('Kode', 'DiskonRpPenjualanReadOnly')->first();
        $DiskonPersenkasirReadOnly = Mssetting::where('Kode', 'DiskonPersenPenjualanReadOnly')->first();
        $trmutasihd = Trmutasihd::where('Transaksi', 'kasir')->whereYear('Tanggal', $year)->whereMonth('Tanggal', $month)->whereDay('Tanggal', $day)->OrderBy('Tanggal', 'DESC')->first();
        $kasir = Trmutasihd::where('Transaksi', 'kasir')->get();
        $mslokasi = Mslokasi::all();
        $msbarang = Msbarang::all();
        $SaldoMinusMax = Mssetting::where('Kode', 'SaldoMinusMax')->first();
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


        $pajak = 10;
        $diskon_rp = 0;
        $diskon_persen = 0;
        if ($Pajakkasir->aktif == 1) {
            $pajak = $Pajakkasir->Nilai;
        }


        if (session()->has('transaksi_kasir')) {
            $trkasir = session('transaksi_kasir');
        } else {
            $data = [
                'transaksi' => 'PENJUALAN',
                'nomor' => $formatNomor,
                'tanggal' => date('d M y H:i'),
                'diskon_persen' => $diskon_persen,
                'pajak' => $pajak,
                'diskon_rp' => $diskon_rp,
                'lokasi' => auth()->user()->KodeLokasi,
                'keterangan' => '',
                'total_harga_sebelum' => 0,
                'total_harga' => 0,
                'total_harga_setelah_pajak' => 0
            ];
            $trkasir = session(['transaksi_kasir' => $data]);
            $trkasir = session('transaksi_kasir');
        }
        // session()->forget('detail_transaksi_kasir');
        // session()->forget('transaksi_kasir');



        return view("frontend.pos.transaksi.kasir.index", [
            'formatNomor' => $formatNomor, 'kasir' => $kasir,
            'mslokasi' => $mslokasi,
            'msbarang' => $msbarang,
            'trkasir' => $trkasir,
            'msanggota' => $msanggota,
            'Pajakkasir' => $Pajakkasir,
            'DiskonRpkasirReadOnly' => $DiskonRpkasirReadOnly,
            'DiskonPersenkasirReadOnly' => $DiskonPersenkasirReadOnly,
            'SaldoMinusMax' => $SaldoMinusMax,
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
        $total = 0;
        $trkasir = Session::get('transaksi_kasir');
        if (session()->has('detail_transaksi_kasir')) {
            $datadetail = Session::get('detail_transaksi_kasir');
            foreach ($datadetail as $key => $value) {
                $total = $total + $value["subtotal"];
            }
        }
        //hitung diskon persen
        $hasil = $total;
        $total_sebelum = $total;

        if ($hasil <= 0) {
            $hasil = 0;
        }
        //cek session
        $data = [
            'transaksi' => $trkasir['transaksi'],
            'nomor' => $trkasir['nomor'],
            'tanggal' => $trkasir['tanggal'],
            'diskon_persen' => $trkasir['diskon_persen'],
            'pajak' => $trkasir['pajak'],
            'diskon_rp' => $trkasir['diskon_rp'],
            'lokasi' => auth()->user()->KodeLokasi,
            'keterangan' => $trkasir['keterangan'],
            'total_harga_sebelum' => $total_sebelum,
            'total_harga' => $hasil,
            'total_harga_setelah_pajak' => $hasil
        ];

        session(['transaksi_kasir' => $data]);
        session()->save();

        return true;
    }

    public function get_data_barang(Request $request)
    {
        if ($request->ajax()) {
            $msbarang = Msbarang::where('Kode', $request->kode_barang)->first();
            $today = date('Y-m-d');
            $trsaldobarang = Trsaldobarang::where('KodeBarang', $request->kode_barang)->where('KodeLokasi', auth()->user()->KodeLokasi)->OrderBy('Tanggal', 'DESC')->first();
            $saldo = 0;
            if ($trsaldobarang) {
                $saldo = $trsaldobarang->Saldo;
            }
            $cekharga = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->where('trmutasihd.Transaksi', 'PROMO')
                ->where('LokasiAwal', auth()->user()->KodeLokasi)->whereDate('TglAwal', '>=', $today)->where('TglAkhir', '<=', $today)
                ->where('StatusPesanan', 'POST')->where('KodeBarang', $request->kode_barang)->orderBy('Tanggal', 'DESC')->first();

            if ($cekharga) {
                $harga = $cekharga->Harga;
            } else {
                $harga = $msbarang->HargaJual;
            }

            $res =   $this->store_detail($msbarang->Kode, $msbarang->Nama, $harga);
            $data = [
                'Nama' => $msbarang->Nama,
                'HargaJual' => $harga,
                'Saldo' => $saldo,
                'Hasil' => $res['hasil'],
                'TotalQty' => $res['totalqty']
            ];
            return response()->json($data);
        }
    }

    public function store_detail($kode, $nama, $harga)
    {
        //cek sisa barang
        $trkasir = Session::get('transaksi_kasir');
        $total = 0;
        $subtotal = 1 * $harga;
        $arr = [];
        $totalqty = 0;
        if (Session::has('detail_transaksi_kasir')) {
            $datadetail = Session::get('detail_transaksi_kasir');
            $no = 0;
            $cek =  false;
            foreach ($datadetail as $key => $value) {
                $total = $total + $value["subtotal"];
                if ($value['barang'] == $kode) {
                    $cek = true;
                    $value['qty'] = 1 + $value['qty'];
                    $value['subtotal'] = $value['qty'] * $value['harga'];
                }
                $totalqty += $value['qty'];
                $no = $value["urut"];
                array_push($arr, $value);
            }
            if (!$cek) {
                $data = [
                    'urut' => $no + 1,
                    'barang' => $kode,
                    'nama_barang' => $nama,
                    'harga' => $harga,
                    'qty' => 1,
                    'diskon_persen' => 0,
                    'subtotal' => $subtotal,
                    'diskon_rp' =>  0,
                    'keterangan' => null,
                ];
                $totalqty += 1;
                array_push($arr, $data);
            }

            $total = $total + $subtotal;

            Session::forget('detail_transaksi_kasir');
            Session::put('detail_transaksi_kasir', $arr);
            Session::save();
        } else {
            $data = [
                'urut' =>  1,
                'barang' => $kode,
                'nama_barang' => $nama,
                'harga' => $harga,
                'qty' => 1,
                'diskon_persen' => 0,
                'subtotal' => $subtotal,
                'diskon_rp' =>  0,
                'keterangan' => null,
            ];
            $totalqty += 1;
            $total = $total + $subtotal;
            array_push($arr, $data);
            Session::forget('detail_transaksi_kasir');
            Session::put('detail_transaksi_kasir', $arr);
            Session::save();
        }

        //hitung diskon persen
        $hasil = $total;
        $total_sebelum = $total;
        if ($hasil <= 0) {
            $hasil = 0;
        }
        $total_sebelum = round($total_sebelum);
        $hasil = round($hasil);
        $data = [
            'transaksi' => $trkasir['transaksi'],
            'nomor' => $trkasir['nomor'],
            'tanggal' => $trkasir['tanggal'],
            'diskon_persen' => $trkasir['diskon_persen'],
            'pajak' => $trkasir['pajak'],
            'diskon_rp' => $trkasir['diskon_rp'],
            'lokasi' => $trkasir['lokasi'],
            'keterangan' => $trkasir['keterangan'],
            'total_harga_sebelum' => $total_sebelum,
            'total_harga' => $hasil,
            'total_harga_setelah_pajak' => $hasil
        ];
        Session::forget('transaksi_kasir');
        Session::put('transaksi_kasir', $data);
        Session::save();
        $res = [
            'hasil' => $hasil,
            'totalqty' => $totalqty
        ];
        return $res;
    }



    public function save_data_transaksi(Request $request)
    {

        if (session()->has('detail_transaksi_kasir') && session()->has('transaksi_kasir')) {
            // dd($request->all());
            $day = date('d');
            $month = date('m');
            $year = date('Y');
            $today = date('Y-m-d');
            $pembayaran_ekop = $request->get('pembayaran_ekop');
            $pembayaran_ekop = str_replace('.', '', $pembayaran_ekop);
            $pembayaran_tunai = $request->get('pembayaran_tunai');
            $pembayaran_tunai = str_replace('.', '', $pembayaran_tunai);
            $pembayaran_kredit = $request->get('pembayaran_kredit');
            $pembayaran_kredit = str_replace('.', '', $pembayaran_kredit);
            $pembayaran_kredit = str_replace(',', '.', $pembayaran_kredit);
            $pembayaran_kredit = floatval($pembayaran_kredit);
            $pembayaran_kredit = round($pembayaran_kredit, 2);
            $chk_tunai = $request->get('chk_tunai');
            $barcode_cust = $request->get('barcode_cust');
            $tunai = $request->get('ttl_pembayaran_tunai');
            $tunai = str_replace('.', '', $tunai);
            $SaldoMinusBunga = Mssetting::where('Kode', 'SaldoMinusBunga')->first();
            DB::beginTransaction();
            try {

                $trmutasihd = Trmutasihd::where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', $today)->max('Nomor');
                if ($trmutasihd != null) {
                    $nomor = (int) substr($trmutasihd, 14);

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
                $trkasir = session('transaksi_kasir');
                $ds_tunai = str_replace('.', '', $trkasir["diskon_rp"]);
                $trmutasihd = new Trmutasihd();
                $trmutasihd->Transaksi = 'PENJUALAN';
                $trmutasihd->Nomor = $formatNomor;
                $trmutasihd->Tanggal = date('Y-m-d H:i');
                $trmutasihd->KodeSuppCust = $barcode_cust;
                if ($trkasir["diskon_persen"] == null || $trkasir["diskon_persen"] == '' ||  $trkasir["diskon_persen"] < 0) {
                    $trmutasihd->DiskonPersen = 0;
                } else {
                    $trmutasihd->DiskonPersen = $trkasir["diskon_persen"];
                }

                $trmutasihd->DiskonTunai = 0;
                $trmutasihd->Pajak = 0;
                $trmutasihd->LokasiAwal = auth('web')->user()->KodeLokasi;
                $trmutasihd->TotalHarga = $trkasir["total_harga"];
                $trmutasihd->UserUpdateSP = auth('web')->user()->UserLogin;
                $statusbayar = '';
                $totalbayar = 0;
                $kembalian  = 0;
                if ($tunai > 0 && $pembayaran_ekop != $trkasir["total_harga_setelah_pajak"] && $pembayaran_kredit != $trkasir["total_harga_setelah_pajak"]) {
                    $trmutasihd->PembayaranTunai = $tunai;
                    //saldototalbelanjatunai
                    $cektunai = Trsaldototalbelanjatunai::where('KodeUser', $barcode_cust)->OrderBy('Tanggal', 'DESC')->first();
                    $trsaldobelanjatunai = new Trsaldototalbelanjatunai();
                    $trsaldobelanjatunai->Tanggal = date('Y-m-d H:i:s');
                    $trsaldobelanjatunai->KodeUser = $barcode_cust;
                    if ($cektunai) {
                        $trsaldobelanjatunai->Saldo = $tunai + $cektunai->Saldo;
                    } else {
                        $trsaldobelanjatunai->Saldo = $tunai;
                    }
                    $trsaldobelanjatunai->save();
                    $statusbayar = 'Tunai';
                    $totalbayar += $tunai;
                    $kembalian += $pembayaran_tunai -  $trkasir["total_harga_setelah_pajak"];
                }
                if (($pembayaran_ekop != '' || $pembayaran_ekop > 0) && $pembayaran_ekop != 0) {
                    $cek = DB::select('call CEKSALDOEKOP(?)', [
                        $barcode_cust
                    ]);
                    $trmutasihd->PembayaranEkop = $pembayaran_ekop;
                    $trsaldoekop = new Trsaldoekop();
                    $trsaldoekop->Tanggal = date('Y-m-d H:i:s');
                    $trsaldoekop->KodeUser = $barcode_cust;
                    $trsaldoekop->Saldo = $cek[0]->Saldo -  $pembayaran_ekop;
                    $trsaldoekop->save();
                    $statusbayar = 'Ekop';
                    $totalbayar += $pembayaran_ekop;
                }
                if (($pembayaran_kredit != '' || $pembayaran_kredit > 0) && $pembayaran_kredit != 0) {
                    $cek = DB::select('call CEKSALDOEKOP(?)', [
                        $barcode_cust
                    ]);
                    $trmutasihd->PembayaranEkop = 0;
                    $trsaldoekop = new Trsaldoekop();
                    $trsaldoekop->Tanggal = date('Y-m-d H:i:s');
                    $trsaldoekop->KodeUser = $barcode_cust;
                    // $trsaldoekop->Saldo = -1 * (abs($cek[0]->Saldo) -  $pembayaran_kredit);
                    $cekreset = Mssetting::where('Kode', 'SaldoMinusResetPerBulan')->where('aktif', 1)->first();

                    $tanggal = '';
                    if ($cekreset) {
                        if ($day < $cekreset->Nilai) {
                            $nilai = $cekreset->Nilai - 1;
                            $tanggal = date('Y-m-' . $nilai);
                        } elseif ($day >= $cekreset->Nilai) {
                            $nilai = $cekreset->Nilai - 1;
                            $firstdate = date('Y-m-' . $nilai);
                            $tanggal = date("Y-m-d", strtotime('+1 month', strtotime($firstdate)));
                        }
                    }
                    $SaldoMinusBunga = Mssetting::where('Kode', 'SaldoMinusBunga')->first();

                    $trsaldokredit = new Trsaldototalbelanjakredit();
                    $trsaldokredit->Tanggal = date('Y-m-d H:i:s');
                    $trsaldokredit->KodeUser = $barcode_cust;

                    $trmutasihd->PembayaranKredit = $pembayaran_kredit;
                    $trsaldoekop->Saldo = round($cek[0]->Saldo, 2) + $pembayaran_kredit;
                    $trmutasihd->DueDate = $tanggal;
                    $cekkredit = Trsaldototalbelanjakredit::where('KodeUser', $barcode_cust)->OrderBy('Tanggal', 'DESC')->first();
                    if ($cekkredit) {
                        $trsaldokredit->Saldo = $pembayaran_kredit + round($cekkredit->Saldo, 2);
                    } else {
                        $trsaldokredit->Saldo = $pembayaran_kredit;
                    }
                    $trsaldoekop->save();
                    $trsaldokredit->save();
                    $statusbayar = 'Kredit';
                    $totalbayar += $pembayaran_kredit;
                }


                $trmutasihd->StatusPesanan = "Barang Telah Diambil";
                $trmutasihd->UserUpdateSP = auth('web')->user()->UserLogin;
                $trmutasihd->TotalHargaSetelahPajak = $trkasir["total_harga_setelah_pajak"];
                $trmutasihd->save();

                //trsaldototalbelanja
                $cektotalbelanja = Trsaldototalbelanja::where('KodeUser', $barcode_cust)->OrderBy('Tanggal', 'DESC')->first();
                $trsaldototalbelanja = new Trsaldototalbelanja();
                $trsaldototalbelanja->Tanggal = date('Y-m-d H:i:s');
                $trsaldototalbelanja->KodeUser = $barcode_cust;
                if ($cektotalbelanja) {
                    $trsaldototalbelanja->Saldo = $pembayaran_kredit + $tunai + $pembayaran_ekop + $cektotalbelanja->Saldo;
                } else {
                    $trsaldototalbelanja->Saldo = $pembayaran_kredit + $tunai + $pembayaran_ekop;
                }
                $trsaldototalbelanja->save();

                $datadetail = session('detail_transaksi_kasir');
                session()->forget('receipt_kasir');
                foreach ($datadetail as $key => $value) {
                    $trmutasidt = new Trmutasidt();
                    $trmutasidt->Transaksi = 'PENJUALAN';
                    $trmutasidt->Nomor = $formatNomor;
                    $trmutasidt->Urut = $value["urut"];
                    $trmutasidt->KodeBarang = $value["barang"];
                    $trmutasidt->Keterangan = $value["keterangan"];
                    $trmutasidt->DiskonPersen = 0;
                    $trmutasidt->DiskonTunai = 0;
                    $trmutasidt->UserUpdate = auth('web')->user()->UserLogin;
                    $trmutasidt->LastUpdate = date('Y-m-d H:i');
                    $trmutasidt->Jumlah = $value['qty'];
                    $trmutasidt->Harga = $value['harga'];
                    $trmutasidt->save();
                    $getstok = Trsaldobarang::where('KodeBarang', $value["barang"])->where('KodeLokasi', auth()->user()->KodeLokasi)->OrderBy('Tanggal', 'DESC')->first();
                    $trsaldobarang = new Trsaldobarang();
                    $trsaldobarang->Tanggal = date('Y-m-d H:i:s');
                    $trsaldobarang->KodeBarang = $value["barang"];
                    if ($getstok) {
                        $trsaldobarang->Saldo = $getstok->Saldo - $value["qty"];
                    } else {
                        $trsaldobarang->Saldo = 0;
                    }

                    $trsaldobarang->KodeLokasi = auth()->user()->KodeLokasi;
                    $trsaldobarang->save();
                }

                $cetak = Mssetting::where('Kode', 'Cetak')->where('aktif', 1)->first();

                if ($cetak) {
                    $this->CetakStruk($datadetail, $formatNomor, $barcode_cust, $statusbayar, $totalbayar, $kembalian);
                }


                // session(['receipt_kasir' => $formatNomor]);
                session()->forget('detail_transaksi_kasir');
                session()->forget('transaksi_kasir');
                session()->save();

                DB::commit();
                return redirect()->route('pos.kasir.index')->with("success", "Detail dan data transaksi kasir berhasil disimpan");
            } catch (\Exception $e) {
                DB::rollback();

                return redirect()->route('pos.kasir.index')->with("error", "Maaf ada yang error");
            }
        }
    }

    public function getDataDetail(Request $request)
    {
        if ($request->ajax()) {
            $datadetail = session('detail_transaksi_kasir');
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
                    $sub["action"] = '<button data-urut="' . $row['urut'] . '" class="edit btn btn-danger ml-2 btnDelete">Delete</button>';
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


    public function getDataKasir(Request $request)
    {
        if ($request->ajax()) {
            $datapembelian = [];
            $data2 = array();
            if (session()->has('transaksi_kasir')) {
                $pembelian = session('transaksi_kasir');
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

            $trkasir = Session::get('transaksi_kasir');
            $total = 0;
            if (session()->has('detail_transaksi_kasir')) {
                $datadetail = Session::get('detail_transaksi_kasir');
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
            session(['transaksi_kasir' => $data]);

            return redirect()->route('pos.pembelian.index')->with("success", "Transaksi pembelian berhasil diupdate");
        }
    }

    public function update_detail_barang(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'id_barang' => 'required',
                'qty' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            } else {
                $trkasir = Session::get('transaksi_kasir');
                $total = 0;
                $subtotal = 0;
                $arr = [];
                $totalqty = 0;
                if (Session::has('detail_transaksi_kasir')) {
                    $datadetail = Session::get('detail_transaksi_kasir');
                    foreach ($datadetail as $key => $value) {
                        if ($value['barang'] == $request->get('id_barang')) {
                            $value['qty'] = $request->get('qty');
                            $value['subtotal'] = $request->get('qty') * $value['harga'];
                        }
                        $totalqty += $value['qty'];
                        $total = $total + $value["subtotal"];
                        array_push($arr, $value);
                    }

                    $total = $total + $subtotal;

                    Session::forget('detail_transaksi_kasir');
                    Session::put('detail_transaksi_kasir', $arr);
                    Session::save();
                }
                //hitung diskon persen
                $hasil = $total;
                $total_sebelum = $total;
                if ($hasil <= 0) {
                    $hasil = 0;
                }
                $total_sebelum = round($total_sebelum);
                $hasil = round($hasil);
                $data = [
                    'transaksi' => $trkasir['transaksi'],
                    'nomor' => $trkasir['nomor'],
                    'tanggal' => $trkasir['tanggal'],
                    'diskon_persen' => $trkasir['diskon_persen'],
                    'pajak' => $trkasir['pajak'],
                    'diskon_rp' => $trkasir['diskon_rp'],
                    'lokasi' => $trkasir['lokasi'],
                    'keterangan' => $trkasir['keterangan'],
                    'total_harga_sebelum' => $total_sebelum,
                    'total_harga' => $hasil,
                    'total_harga_setelah_pajak' => $hasil
                ];
                Session::forget('transaksi_kasir');
                Session::put('transaksi_kasir', $data);
                Session::save();
            }

            return response()->json(['Hasil' => $hasil, 'TotalQty' => $totalqty]);
        }
    }

    public function check_session_detail(Request $request)
    {
        if ($request->ajax()) {
            $message = "";
            $totalqty = 0;
            if (Session::has('detail_transaksi_kasir')) {
                $datadetail = Session::get('detail_transaksi_kasir');
                foreach ($datadetail as $key => $value) {
                    $totalqty += $value['qty'];
                }
                $message = "true";
            } else {
                $message = "false";
            }

            return response()->json([
                'message' => $message,
                'TotalQty' => $totalqty
            ]);
        }
    }

    public function delete_data($id)
    {
        if (session()->has('detail_transaksi_kasir')) {
            $datadetail = Session::get('detail_transaksi_kasir');
            $arr = [];
            $total  = 0;
            $trkasir = Session::get('transaksi_kasir');
            foreach ($datadetail as $key => $value) {
                if ($value["urut"] != $id) {
                    $total = $total + $value["subtotal"];
                    array_push($arr, $value);
                }
            }

            if ($total == 0) {
                Session::forget('detail_transaksi_kasir');
            } else {
                Session::put('detail_transaksi_kasir', $arr);
                Session::save();
            }

            $hasil = $total;
            $total_sebelum = $total;
            //hitung diskon persen
            $hasil = $this->diskon_persen($hasil, $trkasir['diskon_persen']);
            //diskon rp
            $hasil = $this->diskon_rp($hasil, $trkasir['diskon_rp']);
            //pajak
            $pajak = $this->pajak($hasil, $trkasir['pajak']);
            if ($pajak <= 0) {
                $pajak = 0;
            }

            if ($hasil <= 0) {
                $hasil = 0;
            }
            $data = [
                'transaksi' => $trkasir['transaksi'],
                'nomor' => $trkasir['nomor'],
                'tanggal' => $trkasir['tanggal'],
                'diskon_persen' => $trkasir['diskon_persen'],
                'pajak' => $trkasir['pajak'],
                'diskon_rp' => $trkasir['diskon_rp'],
                'lokasi' => $trkasir['lokasi'],
                'keterangan' => $trkasir['keterangan'],
                'total_harga_sebelum' => $total_sebelum,
                'total_harga' => $hasil,
                'total_harga_setelah_pajak' => $pajak
            ];
            Session::forget('transaksi_kasir');
            Session::put('transaksi_kasir', $data);
            Session::save();
            return redirect()->route('pos.kasir.index')->with("success", "Detail barang berhasil dihapus");
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
        $ttl = $request->get('ttl_belanja');
        $tunai = $request->get('pembayaran_tunai');
        $SaldoMinusBunga = Mssetting::where('Kode', 'SaldoMinusBunga')->first();
        $cek = DB::select('call CEKSALDOEKOP(?)', [
            $kode
        ]);

        if (isset($cek[0])) {
            $saldoEkop = $cek[0]->Saldo;
        } else {
            $saldoEkop = 0;
        }
        $total_belanja = $ttl;
        if ($saldoEkop < 0) {
            if ($SaldoMinusBunga->aktif == 1 && $ttl >= $tunai) {
                $ttl = $ttl - $tunai;
                $ttl = $ttl + (($SaldoMinusBunga->Nilai / 100) * $ttl);
                $ttl = number_format($ttl, 2, ',', '.');
            } elseif ($ttl < $tunai) {
                $ttl = 0;
            } elseif ($SaldoMinusBunga->aktif == 0 && $ttl >= $tunai) {
                $ttl = $ttl - $tunai;
                $ttl = number_format($ttl, 2, ',', '.');
            }

            if ($tunai == 0 || $tunai == '') {
                if ($SaldoMinusBunga->aktif == 1 && $total_belanja >= $tunai) {
                    $ttl = $total_belanja + (($SaldoMinusBunga->Nilai / 100) * $total_belanja);
                    $ttl = number_format($ttl, 2, ',', '.');
                } elseif ($SaldoMinusBunga->aktif == 0 && $total_belanja >= $tunai) {
                    $ttl = number_format($total_belanja, 2, ',', '.');
                }
            }
        }



        if ($saldoEkop < 0) {
            $saldoEkop = abs($saldoEkop);
            $status = 'minus';
        } elseif ($saldoEkop > 0) {
            $status = 'plus';
        } else {
            $status = 'nol';
        }

        $saldoEkop = number_format($saldoEkop, 2, ',', '.');
        return response()->json([
            'Saldo' => $saldoEkop,
            'Total' => $ttl,
            'status' => $status
        ]);
    }


    public function receipt()
    {
        $nomor = session('receipt_kasir');
        $trmutasidt = Trmutasidt::join('msbarang', 'msbarang.Kode', 'trmutasidt.KodeBarang')->where('Nomor', $nomor)->get();
        // dd($trmutasidt);
        return view("frontend.pos.transaksi.kasir.receipt", ['data' => $trmutasidt]);
    }

    public function getStatus(Request $request)
    {
        if ($request->ajax()) {
            $cetak = Mssetting::where('Kode', 'Cetak')->first();
            $status = false;
            if ($cetak->aktif == 1) {
                $status = true;
            } else {
                $status = false;
            }

            return response()->json([
                'status' => $status
            ]);
        }
    }

    public function testPrint()
    {
        // $profile = CapabilityProfile::load("simple");
        // $connector = new WindowsPrintConnector(config('app.printer'));
        // $printer = new Printer($connector, $profile);

        // function buatBaris4Kolom($kolom1, $kolom2, $kolom3, $kolom4)
        // {
        //     // Mengatur lebar setiap kolom (dalam satuan karakter)
        //     $lebar_kolom_1 = 15;
        //     $lebar_kolom_2 = 5;
        //     $lebar_kolom_3 = 8;
        //     $lebar_kolom_4 = 9;

        //     // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n
        //     $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        //     $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        //     $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
        //     $kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);

        //     // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        //     $kolom1Array = explode("\n", $kolom1);
        //     $kolom2Array = explode("\n", $kolom2);
        //     $kolom3Array = explode("\n", $kolom3);
        //     $kolom4Array = explode("\n", $kolom4);

        //     // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        //     $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));

        //     // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        //     $hasilBaris = array();

        //     // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris
        //     for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

        //         // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan,
        //         $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
        //         $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

        //         // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
        //         $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
        //         $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

        //         // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
        //         $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
        //     }

        //     // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        //     return implode("\n", $hasilBaris) . "\n";
        // }

        // // Membuat judul
        // $printer->initialize();
        // $printer->selectPrintMode(Printer::MODE_FONT_B); // Setting teks menjadi lebih besar
        // $printer->setJustification(Printer::JUSTIFY_CENTER); // Setting teks menjadi rata tengah
        // $printer->text("Koperasi Karyawan PT.ISP\n");
        // $printer->text("\n");

        // // Data transaksi
        // $printer->initialize();
        // $printer->text("Tanggal : " . date('Y-m-d H:i:s') . "\n");

        // // Membuat tabel
        // $printer->initialize(); // Reset bentuk/jenis teks
        // $printer->setFont(Printer::FONT_B);
        // $printer->text("----------------------------------------\n");
        // $printer->text(buatBaris4Kolom("Barang", "qty", "Harga", "Subtotal"));
        // $printer->text("----------------------------------------\n");
        // $printer->text(buatBaris4Kolom("Makaroni 250gr", "2", "15.000", "30.000"));
        // $printer->text(buatBaris4Kolom("Telur", "2", "5.000", "10.000"));
        // $printer->text(buatBaris4Kolom("Tepung terigu", "1", "8.200", "16.400"));
        // $printer->text("----------------------------------------\n");
        // $printer->text(buatBaris4Kolom('Total Item', '5', "", "56.400"));
        // $printer->text(buatBaris4Kolom('Tunai', '', "", "56.400"));
        // $printer->text(buatBaris4Kolom('Kembalian', '', "", "0"));
        // $printer->text("\n");

        // // Pesan penutup
        // $printer->initialize();
        // $printer->setJustification(Printer::JUSTIFY_CENTER);
        // $printer->text("Terima atas kunjungannya\n");

        // $printer->feed(4); // mencetak 5 baris kosong agar terangkat (pemotong kertas saya memiliki jarak 5 baris dari toner)
        // $printer->cut();
        // $printer->close();
    }

    public function CetakStruk($datadetail, $nomor, $anggota, $pembayaran, $totalbayar, $kembalian)
    {
        $profile = CapabilityProfile::load("simple");
        $connector = new WindowsPrintConnector(config('app.printer'));
        $printer = new Printer($connector, $profile);
        $tipe = Mssetting::where('Kode', 'MasterPrinter')->where('aktif', 1)->first();
        function buatBaris4Kolom($kolom1, $kolom2, $kolom3, $kolom4)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 15;
            $lebar_kolom_2 = 5;
            $lebar_kolom_3 = 8;
            $lebar_kolom_4 = 9;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
            $kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);
            $kolom2Array = explode("\n", $kolom2);
            $kolom3Array = explode("\n", $kolom3);
            $kolom4Array = explode("\n", $kolom4);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan,
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
                $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode("\n", $hasilBaris) . "\n";
        }


        function buatBaris4Kolom33($kolom1, $kolom2, $kolom3, $kolom4)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 12;
            $lebar_kolom_2 = 5;
            $lebar_kolom_3 = 8;
            $lebar_kolom_4 = 9;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
            $kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);
            $kolom2Array = explode("\n", $kolom2);
            $kolom3Array = explode("\n", $kolom3);
            $kolom4Array = explode("\n", $kolom4);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan,
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
                $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode("\n", $hasilBaris) . "\n";
        }

        // Membuat judul
        $printer->initialize();
        $printer->selectPrintMode(Printer::MODE_FONT_B); // Setting teks menjadi lebih besar
        $printer->setJustification(Printer::JUSTIFY_CENTER); // Setting teks menjadi rata tengah
        $printer->text("Koperasi Karyawan PT.ISP\n");
        $printer->text("\n");

        // Data transaksi
        $printer->initialize();
        $printer->text("Tanggal : " . date('Y-m-d H:i:s') . "\n");
        $printer->text($nomor . ' | ' . $anggota . "\n");

        if ($tipe) {
            if ($tipe->Nama == 'Epson tmu220') {
                // Membuat tabel
                $printer->initialize(); // Reset bentuk/jenis teks
                $printer->setFont(Printer::FONT_B);
                $printer->text("----------------------------------------\n");
                $printer->text(buatBaris4Kolom("Barang", "qty", "Harga", "Subtotal"));
                $printer->text("----------------------------------------\n");
                $totalharga = 0;
                $totalitem = 0;
                foreach ($datadetail as $key => $value) {
                    $barang = Msbarang::where('Kode', $value['barang'])->first();
                    if ($barang) {
                        $subtotal = $value['qty'] * $value['harga'];
                        $totalharga += $subtotal;
                        $totalitem +=  $value['qty'];
                        $printer->text(buatBaris4Kolom(substr($barang->Nama, 0, 14), $value['qty'], number_format($value['harga'], 0), number_format($subtotal, 0)));
                    }
                }
                $printer->text("----------------------------------------\n");
                $printer->text(buatBaris4Kolom('Total Item', $totalitem, "", number_format($totalharga, 0)));
                $printer->text(buatBaris4Kolom($pembayaran, '', "", number_format($totalbayar + $kembalian, 0)));
                $printer->text(buatBaris4Kolom('Kembalian', '', "", number_format($kembalian, 0)));
                $printer->text("\n");

                // Pesan penutup
                $printer->initialize();
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("Terimakasih atas kunjungannya\n");

                $printer->feed(2); // mencetak 5 baris kosong agar terangkat (pemotong kertas saya memiliki jarak 5 baris dari toner)
                $printer->cut();
                $printer->close();
            } else {
                // Membuat tabel
                $printer->initialize(); // Reset bentuk/jenis teks
                $printer->setFont(Printer::FONT_B);
                $printer->text("----------------------------------------\n");
                $printer->text(buatBaris4Kolom33("Barang", "qty", "Harga", "Subtotal"));
                $printer->text("----------------------------------------\n");
                $totalharga = 0;
                $totalitem = 0;
                foreach ($datadetail as $key => $value) {
                    $barang = Msbarang::where('Kode', $value['barang'])->first();
                    if ($barang) {
                        $subtotal = $value['qty'] * $value['harga'];
                        $totalharga += $subtotal;
                        $totalitem +=  $value['qty'];
                        $printer->text(buatBaris4Kolom33(substr($barang->Nama, 0, 10), $value['qty'], number_format($value['harga'], 0), number_format($subtotal, 0)));
                    }
                }
                $printer->text("----------------------------------------\n");
                $printer->text(buatBaris4Kolom33('Total Item', $totalitem, "", number_format($totalharga, 0)));
                $printer->text(buatBaris4Kolom33($pembayaran, '', "", number_format($totalbayar + $kembalian, 0)));
                $printer->text(buatBaris4Kolom33('Kembalian', '', "", number_format($kembalian, 0)));
                $printer->text("\n");

                // Pesan penutup
                $printer->initialize();
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("Terimakasih atas kunjungannya\n");

                $printer->feed(2); // mencetak 5 baris kosong agar terangkat (pemotong kertas saya memiliki jarak 5 baris dari toner)
                $printer->cut();
                $printer->close();
            }
        }
    }

    public function reindex()
    {
        $koneksi = 'mysql2';
        $year = date('Y');
        $trmutasihd = Trmutasihd::where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', '2021-09-16')->get();

        foreach ($trmutasihd as $key => $value) {


            $tanggal = date('Y-m-d', strtotime($value->Tanggal));
            $nomor = $this->generateNomor($tanggal, $value->Transaksi, "PE");
            DB::connection($koneksi)->table('trmutasihd')->insert([
                'Transaksi' => $value->Transaksi,
                'Nomor' => $nomor,
                'NomorLokal' => $value->Nomor,
                'Tanggal' => $value->Tanggal,
                'KodeSuppCust' => $value->KodeSuppCust,
                'Keterangan' => $value->Keterangan,
                'DiskonPersen' => $value->DiskonPersen,
                'DiskonTunai' => $value->DiskonTunai,
                'Pajak' => $value->Pajak,
                'LokasiAwal' => $value->LokasiAwal,
                'LokasiTujuan' => $value->LokasiTujuan,
                'PembayaranTunai' => $value->PembayaranTunai,
                'PembayaranKredit' => $value->PembayaranKredit,
                'PembayaranEkop' => $value->PembayaranEkop,
                'TotalHarga' => $value->TotalHarga,
                'StatusPesanan' =>  $value->StatusPesanan,
                'TotalHargaSetelahPajak' => $value->TotalHargaSetelahPajak,
                'UserUpdateSP' => $value->UserUpdateSP,
                'LastUpdateSP' => $value->LastUpdateSP,
                'DueDate' => $value->DueDate,
                'TglAwal' => $value->TglAwal,
                'TglAkhir' => $value->TglAkhir,
            ]);

            $backupdt =  Trmutasidt::where('Nomor', $value->Nomor)->get();

            foreach ($backupdt as $key => $row) {
                DB::connection($koneksi)->table('trmutasidt')->insert([
                    'Transaksi' => $value->Transaksi,
                    'Nomor' =>  $nomor,
                    'Urut' => $row->Urut,
                    'KodeBarang' => $row->KodeBarang,
                    'DiskonPersen' => $row->DiskonPersen,
                    'DiskonTunai' => $row->DiskonTunai,
                    'UserUpdate' => $row->UserUpdate,
                    'LastUpdate' => $row->LastUpdate,
                    'Jumlah' => $row->Jumlah,
                    'Harga' => $row->Harga,
                    'Satuan' => $row->Satuan,
                    'HargaLama' => 0,
                ]);
            }
            // if ($cekmutasi->isNotEmpty()) {


            // }

        }
        // dd($arr);

        return response()->json(['message' => 'success']);
    }

    public function deleteData()
    {
        $koneksi = 'mysql2';
        $trmutasihd = Trmutasihd::on($koneksi)->where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', '2021-09-14')->where('LokasiAwal', 'P2')->get();

        foreach ($trmutasihd as $key => $value) {
            $trmutasidt = Trmutasidt::on($koneksi)->where('Nomor', $value->Nomor)->where('Transaksi', 'PENJUALAN')->delete();
        }

        Trmutasihd::on($koneksi)->where('Transaksi', 'PENJUALAN')->whereDate('Tanggal', '2021-09-14')->where('LokasiAwal', 'P2')->delete();
    }


    public function generateNomor($tanggal, $trans, $status)
    {
        $nomor =  DB::connection('mysql2')->table('trmutasihd')->where('Transaksi', $trans)->whereDate('Tanggal', $tanggal)->max('Nomor');

        if (!is_null($nomor)) {
            $substr = substr($nomor, -5);
            $substr = (int) str_replace('-', '', $substr);
            $nomor = $substr + 1;
            $addzero =  str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = $status . "-" . $tanggal . "-" . $addzero;
        } else {
            $nomor = 1;
            $addzero =  str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $formatNomor = $status . "-" . $tanggal . "-" . $addzero;
        }

        return $formatNomor;
    }
}
