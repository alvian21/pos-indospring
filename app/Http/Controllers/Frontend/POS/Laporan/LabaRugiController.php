<?php

namespace App\Http\Controllers\Frontend\POS\Laporan;

use App\Exports\LabaRugi;
use App\Exports\MutasiBulanan;
use App\Http\Controllers\Controller;
use App\Msbarang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Mslokasi;
use App\Trhpp;
use App\Trmutasidt;
use App\Trmutasihd;
use App\Trsaldobarang;
use PDF;
use Illuminate\Support\Facades\DB;

class LabaRugiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mslokasi = Mslokasi::all();
        $years = range(2020, strftime("%Y", time()));
        return view("frontend.pos.laporan.laba_rugi.index", ['mslokasi' => $mslokasi, 'years' => $years]);
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
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'lokasi' => 'required',
            'cetak' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $periode = $request->get('periode');
            $cetak = $request->get('cetak');
            $tanggal = date("Y-m", strtotime($periode));
            $bulan = date('m', strtotime($periode));
            $tahun = date('Y', strtotime($periode));
            $periode = date(" F  Y", strtotime($periode));
            $lokasi = $request->get('lokasi');
            // $previous =  date("Y-m-d", strtotime('-1 month', strtotime($tanggal)));
            // $bulanlalu = date('m', strtotime($previous));
            // $tahunlalu = date('Y', strtotime($previous));

            if ($lokasi == 'Semua') {
                $data = Trmutasihd::select('KodeBarang', 'Nama')->join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->join('msbarang', 'msbarang.Kode', 'trmutasidt.KodeBarang')->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->groupBy('KodeBarang', 'Nama')->get();
            } else {
                $data = Trmutasihd::select('KodeBarang', 'Nama')->join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->join('msbarang', 'msbarang.Kode', 'trmutasidt.KodeBarang')->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $request->get('lokasi'))->groupBy('KodeBarang', 'Nama')->get();
            }
            $nomor = range(1, 10);
            $arr = [];
            $dataperiode = date('Ym');
            $bulan = range(1, 12);
            $bulan = [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember',
            ];

            $kolom = [
                'Penjualan',
                'Potongan_Penjualan',
                'Bunga',
                'Total',
                'Persediaan_Awal',
                'Pembelian',
                'Retur_Barang',
                'Persediaan_Akhir',
                'Harga_Pokok',
                'Laba_Kotor',
            ];
            $datares = [];

            //persediaan awal
            foreach ($bulan as $keybulan => $row) {
                $arrpersediaan = [];
                $arrpembelian = [];
                $arrretur = [];
                $arrpersediaanakhir = [];
                $jumbulan = $keybulan + 1;
                $data = Trmutasihd::select('KodeBarang', 'Nama')->join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->join('msbarang', 'msbarang.Kode', 'trmutasidt.KodeBarang')->whereMonth('Tanggal', $jumbulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $request->get('lokasi'))->groupBy('KodeBarang', 'Nama')->get();
                $tanggal = $tahun . '-' . $jumbulan . '-01';
                $previous =  date("Y-m-d", strtotime('-1 month', strtotime($tanggal)));
                $dataperiode = date('Ym', strtotime($tanggal));

                $bulanlalu = date('m', strtotime($previous));
                $tahunlalu = date('Y', strtotime($previous));
                $penjualan = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->join('msbarang', 'msbarang.Kode', 'trmutasidt.KodeBarang')->select(DB::raw('sum(Jumlah*HargaJual) as TotalPenjualan'))->where('trmutasihd.Transaksi', 'PENJUALAN')->whereMonth('Tanggal', $jumbulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $lokasi)->first();
                foreach ($data as $key => $value) {
                    $saldobarang = Trsaldobarang::where('KodeBarang', $value->KodeBarang)->whereMonth('Tanggal', $bulanlalu)->whereYear('Tanggal', $tahunlalu)->where('KodeLokasi', $lokasi)->orderBy('Tanggal', 'DESC')->first();
                    if ($saldobarang) {
                        $saldoawal = $saldobarang->Saldo;
                    } else {
                        $saldoawal = 0;
                    }
                    $x['Pembelian'] = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->where('KodeBarang', $value->KodeBarang)->where('trmutasihd.Transaksi', 'PEMBELIAN')->whereMonth('Tanggal', $jumbulan)->whereYear('Tanggal', $tahun)->where('LokasiTujuan', $lokasi)->sum('Jumlah');
                    $x['IN'] = 0;
                    $x['Retur'] = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->where('KodeBarang', $value->KodeBarang)->where('trmutasihd.Transaksi', 'RETUR PEMBELIAN')->whereMonth('Tanggal', $jumbulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $lokasi)->sum('Jumlah');
                    $x['OUT'] = 0;
                    $x['Rusak'] = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->where('KodeBarang', $value->KodeBarang)->where('trmutasihd.Transaksi', 'RUSAK HILANG')->whereMonth('Tanggal', $jumbulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $lokasi)->sum('Jumlah');
                    $x['Penjualan'] = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->where('KodeBarang', $value->KodeBarang)->where('trmutasihd.Transaksi', 'PENJUALAN')->whereMonth('Tanggal', $jumbulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $lokasi)->sum('Jumlah');
                    $x['Opname'] = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->where('KodeBarang', $value->KodeBarang)->where('trmutasihd.Transaksi', 'OPNAME')->whereMonth('Tanggal', $jumbulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $lokasi)->sum('Jumlah');
                    $x['Saldo'] = ($saldoawal + $x['Pembelian'] + $x['IN']) - ($x['Retur'] + $x['OUT'] + $x['Rusak'] + $x['Penjualan']);
                    if ($x['Opname'] > 0) {
                        $akhir = $x['Opname'];
                    } else {
                        $akhir = $x['Saldo'];
                    }

                    $trhpp = Trhpp::where('Periode', $dataperiode)->where('KodeBarang', $value->KodeBarang)->where('KodeLokasi', $lokasi)->first();
                    if ($trhpp) {
                        $x['HPP'] = $trhpp->Hpp;
                    } else {
                        $x['HPP'] = 0;
                    }

                    $barang = Msbarang::find($value->KodeBarang);
                    $x['HargaJual'] = $barang->HargaJual;
                    $x['Laba'] = ($x['HargaJual'] - $x['HPP']) * $x['Penjualan'];
                    $cekakhirsaldo =  Trsaldobarang::where('KodeBarang', $value->KodeBarang)->whereMonth('Tanggal', $jumbulan)->whereYear('Tanggal', $tahun)->where('KodeLokasi', $request->get('lokasi'))->orderBy('Tanggal', 'DESC')->first();
                    if ($cekakhirsaldo) {
                        $saldoakhir = $cekakhirsaldo->Saldo;
                    } else {
                        $saldoakhir = 0;
                    }

                    $x['Adjust'] =  $saldoakhir -  $x['Saldo'];
                    $totalpersediaan = ($saldoawal + $x['Adjust']) * $x['HPP'];
                    $pembelian = $x['Pembelian'] * $x['HPP'];
                    $retur = $x['Retur'] * $x['HPP'];
                    $persediaanakhir = $saldoakhir * $x['HPP'];
                    array_push($arrpersediaan, $totalpersediaan);
                    array_push($arrpembelian, $pembelian);
                    array_push($arrretur, $retur);
                    array_push($arrpersediaanakhir, $persediaanakhir);
                }
                $datax['bulan'] = $row;
                $datax['penjualan'] = $penjualan->TotalPenjualan;
                $datax['total'] = $penjualan->TotalPenjualan;
                $datax['persediaan_awal'] = array_sum($arrpersediaan);
                $datax['pembelian'] = array_sum($arrpembelian);
                $datax['retur'] = array_sum($arrretur) * -1;
                $datax['persediaan_akhir'] = array_sum($arrpersediaanakhir) * -1;
                $datax['harga_pokok'] = $datax['persediaan_awal'] +  $datax['pembelian'] +  $datax['retur'] + $datax['persediaan_akhir'];
                $datax['laba_kotor'] = $datax['total'] - $datax['harga_pokok'];
                array_push($datares, $datax);
            }



            // dd($datares);
            foreach ($kolom as $key => $value) {
                foreach ($datares as $keybulan => $row) {
                    $jumbulan = $keybulan + 1;
                    if ($value == "Penjualan") {
                        $dpenjualan[$row['bulan']] = $row['penjualan'];
                    } elseif ($value == "Total") {
                        $dpenjualan[$row['bulan']] = $row['total'];
                    } elseif ($value == "Persediaan_Awal") {
                        $dpenjualan[$row['bulan']] = $row['persediaan_awal'];
                    } elseif ($value == "Pembelian") {
                        $dpenjualan[$row['bulan']] = $row['pembelian'];
                    } elseif ($value == "Retur_Barang") {
                        $dpenjualan[$row['bulan']] = $row['retur'];
                    } elseif ($value == "Persediaan_Akhir") {
                        $dpenjualan[$row['bulan']] = $row['persediaan_akhir'];
                    } elseif ($value == "Harga_Pokok") {
                        $dpenjualan[$row['bulan']] = $row['harga_pokok'];
                    } elseif ($value == "Laba_Kotor") {
                        $dpenjualan[$row['bulan']] = $row['laba_kotor'];
                    } else {
                        $dpenjualan[$row['bulan']] = 0;
                    }
                }
                if ($value == "Penjualan") {
                    array_push($arr, $dpenjualan);
                } elseif ($value == "Total") {
                    array_push($arr, $dpenjualan);
                } elseif ($value == "Persediaan_Awal") {
                    array_push($arr, $dpenjualan);
                } elseif ($value == "Pembelian") {
                    array_push($arr, $dpenjualan);
                } elseif ($value == "Retur_Barang") {
                    array_push($arr, $dpenjualan);
                } elseif ($value == "Persediaan_Akhir") {
                    array_push($arr, $dpenjualan);
                } elseif ($value == "Harga_Pokok") {
                    array_push($arr, $dpenjualan);
                } elseif ($value == "Laba_Kotor") {
                    array_push($arr, $dpenjualan);
                } else {
                    array_push($arr, $dpenjualan);
                }
            }

            if ($cetak == 'pdf') {
            } else {

                if (isset($arr[0])) {
                    $data = json_decode(json_encode($arr), true);
                    $data = collect($data);
                    $lastdate = date("Y-m-t", strtotime($periode));

                    return Excel::download(new LabaRugi($data, $tahun, $lastdate), 'laporan-laba-rugi.xlsx');
                } else {
                    return redirect()->back()->withErrors(['maaf data pada periode ' . $periode . ' masih kosong']);
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
}
