<?php

namespace App\Http\Controllers\Frontend\POS\Laporan;

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

class MutasiBulananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mslokasi = Mslokasi::all();
        return view("frontend.pos.laporan.mutasi_bulanan.index", ['mslokasi' => $mslokasi]);
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
            $tanggal = date("Y-m-d", strtotime($periode));
            $bulan = date('m', strtotime($periode));
            $tahun = date('Y', strtotime($periode));
            $periode = date(" F  Y", strtotime($periode));
            $lokasi = $request->get('lokasi');
            $previous =  date("Y-m-d", strtotime('-1 month', strtotime($tanggal)));
            $bulanlalu = date('m', strtotime($previous));
            $tahunlalu = date('Y', strtotime($previous));

            if ($lokasi == 'Semua') {
                $data = Trmutasihd::select('KodeBarang', 'Nama')->join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->join('msbarang', 'msbarang.Kode', 'trmutasidt.KodeBarang')->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->groupBy('KodeBarang', 'Nama')->get();
            } else {
                $data = Trmutasihd::select('KodeBarang', 'Nama')->join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->join('msbarang', 'msbarang.Kode', 'trmutasidt.KodeBarang')->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $request->get('lokasi'))->groupBy('KodeBarang', 'Nama')->get();
            }

            $arr = [];
            $dataperiode = date('Ym');
            foreach ($data as $key => $value) {
                $x['KodeBarang'] = $value->KodeBarang;
                $x['Nama'] = $value->Nama;
                $saldobarang = Trsaldobarang::where('KodeBarang', $value->KodeBarang)->whereMonth('Tanggal', $bulanlalu)->whereYear('Tanggal', $tahunlalu)->where('KodeLokasi', $lokasi)->orderBy('Tanggal', 'DESC')->first();
                if ($saldobarang) {
                    $saldo = $saldobarang->Saldo;
                } else {
                    $saldo = 0;
                }
                $x['SaldoAwal'] = $saldo;
                $pembelian = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->where('KodeBarang', $value->KodeBarang)->where('trmutasihd.Transaksi', 'PEMBELIAN')->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('LokasiTujuan', $lokasi)->sum('Jumlah');
                $x['Pembelian'] = $pembelian;
                $x['IN'] = 0;
                $x['Retur'] = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->where('KodeBarang', $value->KodeBarang)->where('trmutasihd.Transaksi', 'RETUR PEMBELIAN')->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $lokasi)->sum('Jumlah');
                $x['OUT'] = 0;
                $x['Rusak'] = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->where('KodeBarang', $value->KodeBarang)->where('trmutasihd.Transaksi', 'RUSAK HILANG')->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $lokasi)->sum('Jumlah');
                $x['Penjualan'] = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->where('KodeBarang', $value->KodeBarang)->where('trmutasihd.Transaksi', 'PENJUALAN')->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $lokasi)->sum('Jumlah');
                $x['Opname'] = Trmutasihd::join('trmutasidt', 'trmutasihd.Nomor', 'trmutasidt.Nomor')->where('KodeBarang', $value->KodeBarang)->where('trmutasihd.Transaksi', 'OPNAME')->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('LokasiAwal', $lokasi)->sum('Jumlah');
                $x['Saldo'] = ($saldo + $x['Pembelian'] + $x['IN']) - ($x['Retur'] + $x['OUT'] + $x['Rusak'] + $x['Penjualan']);
                if ($x['Opname'] > 0) {
                    $akhir = $x['Opname'];
                } else {
                    $akhir = $x['Saldo'];
                }
                $x['Adjust'] = $akhir;
                $trhpp = Trhpp::where('Periode', $dataperiode)->where('KodeBarang', $value->KodeBarang)->where('KodeLokasi', $lokasi)->first();
                if ($trhpp) {
                    $x['HPP'] = $trhpp->Hpp;
                } else {
                    $x['HPP'] = 0;
                }
                $barang = Msbarang::find($value->KodeBarang);
                $x['HargaJual'] = $barang->HargaJual;
                $x['Laba'] = ($x['HargaJual'] - $x['HPP']) * $x['Penjualan'];
                $cekakhirsaldo =  Trsaldobarang::where('KodeBarang', $value->KodeBarang)->whereMonth('Tanggal', $bulan)->whereYear('Tanggal', $tahun)->where('KodeLokasi', $request->get('lokasi'))->orderBy('Tanggal', 'DESC')->first();
                if ($cekakhirsaldo) {
                    $resakhir = $cekakhirsaldo->Saldo;
                } else {
                    $resakhir = 0;
                }
                $x['SaldoAkhir'] = $resakhir;
                array_push($arr, $x);
            }
            if ($cetak == 'pdf') {

            } else {

                if (isset($arr[0])) {
                    $data = json_decode(json_encode($arr), true);
                    $data = collect($data);
                    $lastdate = date("Y-m-t", strtotime($periode));

                    return Excel::download(new MutasiBulanan($data, $periode, $lastdate), 'laporan-mutasi-bulanan.xlsx');
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
