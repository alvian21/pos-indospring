<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Msanggota;
use Illuminate\Http\Request;
use App\Trmutasihd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Mslokasi;
use App\Msbarang;
use App\Trsaldobarang;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lokasi = Mslokasi::where('Kode', auth('web')->user()->KodeLokasi)->first();

        return view("frontend.dashboard.index", ['lokasi' => $lokasi]);
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

    public function PenjualanOffline(Request $request)
    {
        if ($request->ajax()) {
            $from = date("Y-m-d", strtotime("-10 day"));
            $to = date('Y-m-d');
            $penjualanoffline = Trmutasihd::select([
                DB::raw('sum(TotalHarga) as `total`'),
                DB::raw("DATE_FORMAT(Tanggal, '%d-%M') as day")
            ])->groupBy('day')
                ->where('Transaksi', 'PENJUALAN')
                ->where('LokasiAwal', auth('web')->user()->KodeLokasi)
                ->whereBetween('Tanggal', [$from . ' 00:00:00', $to . ' 23:59:59'])->OrderBy('day','ASC')
                ->limit(10)->get();

            return response()->json($penjualanoffline);
        }
    }

    public function PenjualanOnline(Request $request)
    {
        if ($request->ajax()) {
            $from = date("Y-m-d", strtotime("-10 day"));
            $to = date('Y-m-d');
            $penjualanonline = Trmutasihd::select([
                DB::raw('sum(TotalHarga) as `total`'),
                DB::raw("DATE_FORMAT(Tanggal, '%d-%M') as day")
            ])->groupBy('day')
                ->where('Transaksi', 'CHECKOUT')
                ->where('LokasiAwal', auth('web')->user()->KodeLokasi)->OrderBy('day','ASC')
                ->whereBetween('Tanggal', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->limit(10)->get();

            return response()->json($penjualanonline);
        }
    }

    public function StatusPesanan(Request $request)
    {
        if ($request->ajax()) {
            $from = strtotime(date("Y-m-d", strtotime("-10 day")));
            $to = date('Y-m-d');
            $status = [
                'Pesanan',
                'Dalam Proses',
                'Barang Sudah Siap',
                'Barang Telah Diambil'
            ];

            $arr = [];
            foreach ($status as $key => $value) {
                $penjualanonline = Trmutasihd::select([
                    DB::raw('StatusPesanan as status'),
                    DB::raw('count(StatusPesanan) as total')
                ])->groupBy('status')
                    ->where('LokasiAwal', auth('web')->user()->KodeLokasi)->where('StatusPesanan', $value)
                    ->whereBetween('Tanggal', [$from . ' 00:00:00', $to . ' 23:59:59'])
                    ->limit(10)->first();
                if ($penjualanonline) {
                    $x['status'] = $penjualanonline->status;
                    $x['total'] = $penjualanonline->total;
                } else {
                    $x['status'] = $value;
                    $x['total'] = 0;
                }
                array_push($arr, $x);
            }

            return response()->json($arr);
        }
    }

    public function EmailStatus(Request $request)
    {
        if ($request->ajax()) {
            $verified = Msanggota::where('verified_email', 1)->count();
            $unverified = Msanggota::where('verified_email', 0)->count();

            $arr = [
                'verified' => $verified,
                'unverified' => $unverified
            ];

            return response()->json($arr);
        }
    }

    public function EmailStatusWithout(Request $request)
    {
        if ($request->ajax()) {
            $verified = Msanggota::where('verified_email', 1)->count();
            $without = Msanggota::where('email', null)->count();

            $arr = [
                'verified' => $verified,
                'without' => $without
            ];

            return response()->json($arr);
        }
    }

    public function BarangPictures(Request $request)
    {
        if ($request->ajax()) {
            $with = Msbarang::where('LokasiGambar', '!=', null)->count();
            $without = Msbarang::where('LokasiGambar', null)->count();

            $arr = [
                'with' => $with,
                'without' => $without
            ];

            return response()->json($arr);
        }
    }

    public function BarangBarcode(Request $request)
    {
        if ($request->ajax()) {
            $with = Msbarang::where('KodeBarcode', '!=', null)->count();
            $without = Msbarang::where('KodeBarcode', null)->count();

            $arr = [
                'with' => $with,
                'without' => $without
            ];

            return response()->json($arr);
        }
    }

    public function MinimumStok(Request $request)
    {
        if ($request->ajax()) {
            $barang = Msbarang::all();

            $minimum = [];

            foreach ($barang as $key => $value) {
                $trsaldobarang = Trsaldobarang::where('KodeBarang', $value->Kode)->where('KodeLokasi', auth()->user()->KodeLokasi)->OrderBy('Tanggal', 'DESC')->first();
                if ($trsaldobarang) {
                    if ($trsaldobarang->Saldo <= $value->MinimumStok) {
                        array_push($minimum, $trsaldobarang->KodeBarang);
                    }
                }
            }

            $available = [];
            foreach ($barang as $key => $value) {
                $trsaldobarang = Trsaldobarang::where('KodeBarang', $value->Kode)->where('KodeLokasi', auth()->user()->KodeLokasi)->OrderBy('Tanggal', 'DESC')->first();
                if ($trsaldobarang) {
                    if ($trsaldobarang->Saldo > $value->MinimumStok) {
                        array_push($available, $trsaldobarang->KodeBarang);
                    }
                }
            }

            $res = [
                'minimum' => count($minimum),
                'available' => count($available)
            ];

            return response()->json($res);
        }
    }
}
