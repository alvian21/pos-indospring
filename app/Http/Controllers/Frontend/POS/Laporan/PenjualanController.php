<?php

namespace App\Http\Controllers\Frontend\POS\Laporan;

use App\Exports\PenjualanExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mslokasi;
use App\Msanggota;
use App\Trmutasihd;
use Maatwebsite\Excel\Facades\Excel;
use App\Trmutasidt;
use Illuminate\Support\Facades\DB;
use PDF;


class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mslokasi = Mslokasi::all();
        $msanggota = Msanggota::all();
        return view("frontend.pos.laporan.penjualan.index", ['mslokasi' => $mslokasi, 'msanggota' => $msanggota]);
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

    public function cetakPdf(Request $request)
    {
        $periode1 = $request->get('periode1');
        $periode2 = $request->get('periode2');
        if ($request->get('transaksi') == 'online') {
            $status = 'CHECKOUT';
        } elseif ($request->get('transaksi') == 'offline') {
            $status = 'PENJUALAN';
        }
        $lokasi = $request->get('lokasi');
        $grup = $request->get('grup');

        if ($grup == 'Group By Customer') {
            if ($request->get('transaksi') == 'semua' && $request->get('customer') == 'UMUM') {
                $trmutasihd = Trmutasihd::select('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat', DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->where(function ($query) {
                    $query->where('Transaksi', 'PENJUALAN')
                        ->orWhere('Transaksi', 'CHECKOUT');
                })->groupBy('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat')->get();
            } elseif ($request->get('transaksi') == 'semua' && $request->get('customer') != 'semua') {
                $trmutasihd = Trmutasihd::select('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat', DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->where(function ($query) {
                    $query->where('Transaksi', 'PENJUALAN')
                        ->orWhere('Transaksi', 'CHECKOUT');
                })->groupBy('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat')->get();
            } else if ($request->get('transaksi') == 'semua' && $request->get('customer') == 'semua') {
                $trmutasihd = Trmutasihd::select('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat', DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where(function ($query) {
                    $query->where('Transaksi', 'PENJUALAN')
                        ->orWhere('Transaksi', 'CHECKOUT');
                })->groupBy('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat')->get();
            } else if ($request->get('transaksi') != 'semua' && $request->get('customer') == 'semua') {
                $trmutasihd = Trmutasihd::select('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat', DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->where('Transaksi', $status)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->groupBy('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat')->get();
            } else  if ($request->get('transaksi') != 'semua' && $request->get('customer') == 'UMUM') {
                $trmutasihd = Trmutasihd::select('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat', DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->where('Transaksi', $status)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->groupBy('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat')->get();
            } else {
                $trmutasihd = Trmutasihd::select('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat', DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->where('Transaksi', $status)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->groupBy('KodeSuppCust', 'Nama', 'SubDept', 'Pangkat')->get();
            }


            $sumtotal = 0;
            $sumekop = 0;
            $sumtunai = 0;
            $sumkredit = 0;
            $sumdiskon = 0;
            $sumpajak = 0;

            $sumtotal = array_sum(array_column($trmutasihd->toArray(), 'TotalHarga'));
            $sumekop = array_sum(array_column($trmutasihd->toArray(), 'PembayaranEkop'));
            $sumtunai = array_sum(array_column($trmutasihd->toArray(), 'PembayaranTunai'));
            $sumkredit = array_sum(array_column($trmutasihd->toArray(), 'PembayaranKredit'));

            if ($request->get('cetak') == 'pdf') {

                $pdf = PDF::loadview(
                    "frontend.pos.laporan.penjualan.pdf",
                    [
                        'grup' => $grup,
                        'laporan' => $trmutasihd, 'periode1' => $periode1, 'periode2' => $periode2, 'total' => $sumtotal,
                        'tunai' => $sumtunai, 'kredit' => $sumkredit, 'ekop' => $sumekop
                    ]
                )->setPaper('a3', 'landscape');
                return $pdf->stream('laporan-penjualan-pdf', array('Attachment' => 0));
            } else {
                $trmutasihd = json_decode(json_encode($trmutasihd), true);
                $penjualan = $trmutasihd;


                $penjualan = collect($penjualan);



                return Excel::download(new PenjualanExport($penjualan, $sumdiskon, $sumpajak, $sumtotal, $sumtunai, $sumkredit, $sumekop, $grup,$periode1,$periode2),'laporan-penjualan-grup.xlsx');
            }
        }else if($grup == 'Group By Tanggal'){
            if ($request->get('transaksi') == 'semua' && $request->get('customer') == 'UMUM') {
                $trmutasihd = Trmutasihd::select(DB::raw('DATE(Tanggal) day'), DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->where(function ($query) {
                    $query->where('Transaksi', 'PENJUALAN')
                        ->orWhere('Transaksi', 'CHECKOUT');
                })->groupBy('day')->get();
            } elseif ($request->get('transaksi') == 'semua' && $request->get('customer') != 'semua') {
                $trmutasihd = Trmutasihd::select(DB::raw('DATE(Tanggal) day'), DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->where(function ($query) {
                    $query->where('Transaksi', 'PENJUALAN')
                        ->orWhere('Transaksi', 'CHECKOUT');
                })->groupBy('day')->get();
            } else if ($request->get('transaksi') == 'semua' && $request->get('customer') == 'semua') {
                $trmutasihd = Trmutasihd::select(DB::raw('DATE(Tanggal) day'), DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where(function ($query) {
                    $query->where('Transaksi', 'PENJUALAN')
                        ->orWhere('Transaksi', 'CHECKOUT');
                })->groupBy('day')->get();
            } else if ($request->get('transaksi') != 'semua' && $request->get('customer') == 'semua') {
                $trmutasihd = Trmutasihd::select(DB::raw('DATE(Tanggal) day'), DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->where('Transaksi', $status)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->groupBy('day')->get();
            } else  if ($request->get('transaksi') != 'semua' && $request->get('customer') == 'UMUM') {
                $trmutasihd = Trmutasihd::select(DB::raw('DATE(Tanggal) day'), DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->where('Transaksi', $status)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->groupBy('day')->get();
            } else {
                $trmutasihd = Trmutasihd::select(DB::raw('DATE(Tanggal) day'), DB::raw('sum(TotalHarga) as TotalHarga'), DB::raw('sum(PembayaranEkop) as PembayaranEkop'), DB::raw('sum(PembayaranTunai) as PembayaranTunai'), DB::raw('sum(PembayaranKredit) as PembayaranKredit'))->leftJoin('msanggota', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->where('Transaksi', $status)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->groupBy('day')->get();
            }



            $sumtotal = 0;
            $sumekop = 0;
            $sumtunai = 0;
            $sumkredit = 0;
            $sumdiskon = 0;
            $sumpajak = 0;

            $sumtotal = array_sum(array_column($trmutasihd->toArray(), 'TotalHarga'));
            $sumekop = array_sum(array_column($trmutasihd->toArray(), 'PembayaranEkop'));
            $sumtunai = array_sum(array_column($trmutasihd->toArray(), 'PembayaranTunai'));
            $sumkredit = array_sum(array_column($trmutasihd->toArray(), 'PembayaranKredit'));
            if ($request->get('cetak') == 'pdf') {

                $pdf = PDF::loadview(
                    "frontend.pos.laporan.penjualan.pdftanggal",
                    [
                        'grup' => $grup,
                        'laporan' => $trmutasihd, 'periode1' => $periode1, 'periode2' => $periode2, 'total' => $sumtotal,
                        'tunai' => $sumtunai, 'kredit' => $sumkredit, 'ekop' => $sumekop
                    ]
                )->setPaper('a4', 'potrait');
                return $pdf->stream('laporan-penjualan-pdf', array('Attachment' => 0));
            }

            else {
                $trmutasihd = json_decode(json_encode($trmutasihd), true);
                $penjualan = $trmutasihd;


                $penjualan = collect($penjualan);



                return Excel::download(new PenjualanExport($penjualan, $sumdiskon, $sumpajak, $sumtotal, $sumtunai, $sumkredit, $sumekop, $grup,$periode1,$periode2), 'laporan-penjualan-grup.xlsx');
            }
        }
        else {



            if ($request->get('transaksi') == 'semua' && $request->get('customer') == 'UMUM') {
                $trmutasihd = Trmutasihd::whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->where(function ($query) {
                    $query->where('Transaksi', 'PENJUALAN')
                        ->orWhere('Transaksi', 'CHECKOUT');
                })->orderBy('Tanggal')->get();
            } elseif ($request->get('transaksi') == 'semua' && $request->get('customer') != 'semua') {
                $trmutasihd = Trmutasihd::whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->where(function ($query) {
                    $query->where('Transaksi', 'PENJUALAN')
                        ->orWhere('Transaksi', 'CHECKOUT');
                })->orderBy('Tanggal')->get();
            } else if ($request->get('transaksi') == 'semua' && $request->get('customer') == 'semua') {
                $trmutasihd = Trmutasihd::whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where(function ($query) {
                    $query->where('Transaksi', 'PENJUALAN')
                        ->orWhere('Transaksi', 'CHECKOUT');
                })->orderBy('Tanggal')->get();
            } else if ($request->get('transaksi') != 'semua' && $request->get('customer') == 'semua') {
                $trmutasihd = Trmutasihd::where('Transaksi', $status)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->orderBy('Tanggal')->get();
            } else  if ($request->get('transaksi') != 'semua' && $request->get('customer') == 'UMUM') {
                $trmutasihd = Trmutasihd::where('Transaksi', $status)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->orderBy('Tanggal')->get();
            } else {
                $trmutasihd = Trmutasihd::where('Transaksi', $status)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiAwal', $lokasi)->where('KodeSuppCust', $request->get('customer'))->orderBy('Tanggal')->get();
            }
            $arr = [];
            $sumdiskon = 0;
            $sumpajak = 0;
            $sumtotal = 0;
            $sumekop = 0;
            $sumtunai = 0;
            $sumkredit = 0;
            foreach ($trmutasihd as $key => $value) {
                if ($value->Transaksi == 'CHECKOUT') {
                    $transaksi = 'Online';
                } else if ($value->Transaksi == 'PENJUALAN') {
                    $transaksi = 'Offline';
                }

                $trmutasidt = Trmutasidt::where('Nomor', $value->Nomor)->where('Transaksi', $transaksi)->get();
                $subtotal = 0;

                foreach ($trmutasidt as $key => $row) {
                    $subtotal += $row->Harga;
                }

                $x['Penjualan'] = $transaksi;
                $x['Lokasi'] = $value->LokasiAwal;
                $x['Tanggal'] = $value->Tanggal;
                $x['Nomor'] = $value->Nomor;
                $x['Customer'] = $value->KodeSuppCust;

                //hitung total diskon
                $diskonpersen = ($value->DiskonPersen / 100) * $subtotal;
                $totaldiskon = $value->DiskonTunai + $diskonpersen;
                $sumdiskon += $totaldiskon;
                $x['Diskon'] = $totaldiskon;

                //hitung pajak
                $totalhargasetelahdiskon = $value->TotalHarga;
                $totalhargasetelahpajak =  ($totalhargasetelahdiskon * ($value->Pajak / 100));
                $sumpajak += $totalhargasetelahpajak;
                $x['Pajak'] = $totalhargasetelahpajak;

                $sumtotal += $value->TotalHargaSetelahPajak;
                $sumtunai += $value->PembayaranTunai;
                $sumekop += $value->PembayaranEkop;
                $sumkredit += $value->PembayaranKredit;
                $x['Total'] = $value->TotalHargaSetelahPajak;
                $x['Ekop'] = $value->PembayaranEkop;
                $x['Tunai'] = $value->PembayaranTunai;
                $x['Kredit'] = $value->PembayaranKredit;
                $x['DueDate'] = $value->DueDate;



                array_push($arr, $x);
            }

            $periode1 = date("l, F j, Y", strtotime($periode1));
            $periode2 = date("l, F j, Y", strtotime($periode2));

            if ($request->get('cetak') == 'pdf') {
                $pdf = PDF::loadview(
                    "frontend.pos.laporan.penjualan.pdf",
                    [
                        'grup' => $grup,
                        'laporan' => $arr, 'periode1' => $periode1, 'periode2' => $periode2,
                        'diskon' => $sumdiskon, 'pajak' => $sumpajak, 'total' => $sumtotal,
                        'tunai' => $sumtunai, 'kredit' => $sumkredit, 'ekop' => $sumekop
                    ]
                )->setPaper('a3', 'landscape');
                return $pdf->stream('laporan-penjualan-pdf', array('Attachment' => 0));
            } else {
                $penjualan = collect($arr);



                return Excel::download(new PenjualanExport($penjualan, $sumdiskon, $sumpajak, $sumtotal, $sumtunai, $sumkredit, $sumekop, $grup,$periode1,$periode2), 'laporan-penjualan.xlsx');
            }
        }
    }

    public function cetakDetail(Request $request)
    {
        $periode1 = $request->get('periode1');
        $periode2 = $request->get('periode2');
        if ($request->get('transaksi') == 'online') {
            $status = 'CHECKOUT';
        } elseif ($request->get('transaksi') == 'offline') {
            $status = 'PENJUALAN';
        }
        $lokasi = $request->get('lokasi');
        if ($request->get('transaksi') == 'semua' && $request->get('customer') == 'UMUM') {
            $trmutasihd =  DB::table('trmutasihd')->where('LokasiAwal', $lokasi)->where('trmutasihd.KodeSuppCust', $request->get('customer'))->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where(function ($query) {
                $query->where('Transaksi', 'PENJUALAN')
                    ->orWhere('Transaksi', 'CHECKOUT');
            })->get();
        } elseif ($request->get('transaksi') == 'semua' && $request->get('customer') != 'semua') {
            $trmutasihd =  DB::table('msanggota')->leftJoin('trmutasihd', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->where('LokasiAwal', $lokasi)->where('trmutasihd.KodeSuppCust', $request->get('customer'))->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where(function ($query) {
                $query->where('Transaksi', 'PENJUALAN')
                    ->orWhere('Transaksi', 'CHECKOUT');
            })->get();
        } else if ($request->get('transaksi') == 'semua' && $request->get('customer') == 'semua') {
            $trmutasihd =  DB::table('msanggota')->leftJoin('trmutasihd', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->where('LokasiAwal', $lokasi)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where(function ($query) {
                $query->where('Transaksi', 'PENJUALAN')
                    ->orWhere('Transaksi', 'CHECKOUT');
            })->get();
        } else if ($request->get('transaksi') != 'semua' && $request->get('customer') == 'semua') {
            $trmutasihd =  DB::table('msanggota')->leftJoin('trmutasihd', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->where('LokasiAwal', $lokasi)->where('Transaksi', $status)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->get();
        } else  if ($request->get('transaksi') != 'semua' && $request->get('customer') == 'UMUM') {
            $trmutasihd =  DB::table('trmutasihd')->where('Transaksi', $status)->where('LokasiAwal', $lokasi)->where('trmutasihd.KodeSuppCust', $request->get('customer'))->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->get();
        } else {
            $trmutasihd = DB::table('msanggota')->leftJoin('trmutasihd', 'msanggota.Kode', 'trmutasihd.KodeSuppCust')->where('LokasiAwal', $lokasi)->where('Transaksi', $status)->where('trmutasihd.KodeSuppCust', $request->get('customer'))->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->get();
        }



        $arr = [];
        foreach ($trmutasihd as $key => $value) {
            $datadetail = DB::table('msbarang')->leftJoin('trmutasidt', 'msbarang.Kode', 'trmutasidt.KodeBarang')->where('Nomor', $value->Nomor)->get();
            $x['datadetail'] = json_decode(json_encode($datadetail), true);
            $data = array_merge($x, (array)$value);
            array_push($arr, $data);
        }

        $periode1 = date("l, F j, Y", strtotime($periode1));
        $periode2 = date("l, F j, Y", strtotime($periode2));

        $pdf = PDF::loadview(
            "frontend.pos.laporan.penjualan.detail.pdf",
            ['data' => $arr, 'periode1' => $periode1, 'periode2' => $periode2]
        )->setPaper('a3', 'landscape');
        return $pdf->stream('laporan-detail-penjualan-pdf', array('Attachment' => 0));
        // return view("frontend.pos.laporan.penjualan.detail.pdf", ['data' => $arr, 'periode1' => $periode1, 'periode2' => $periode2]);
    }
}
