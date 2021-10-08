<?php

namespace App\Http\Controllers\Frontend\POS\Laporan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mslokasi;
use App\Msanggota;
use App\Trmutasihd;
use Maatwebsite\Excel\Facades\Excel;
use App\Trmutasidt;
use App\Mssupplier;
use Illuminate\Support\Facades\DB;
use PDF;

class ReturPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mslokasi = Mslokasi::all();
        $mssupplier = Mssupplier::all();
        return view("frontend.pos.laporan.retur_pembelian.index", ['mslokasi' => $mslokasi, 'mssupplier' => $mssupplier]);
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


        $lokasi = $request->get('lokasi');
        if ($request->get('supplier') == 'UMUM') {
            $trmutasihd = Trmutasihd::where('Transaksi', 'RETUR PEMBELIAN')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiTujuan', $lokasi)->where('KodeSuppCust', $request->get('supplier'))->orderBy('Tanggal')->get();
        } elseif ($request->get('supplier') != 'semua') {
            $trmutasihd = Trmutasihd::join('mssupplier','mssupplier.Kode','trmutasihd.KodeSuppCust')->where('Transaksi', 'RETUR PEMBELIAN')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiTujuan', $lokasi)->where('KodeSuppCust', $request->get('supplier'))->orderBy('Tanggal')->get();
        } else if ($request->get('supplier') == 'semua') {
            $trmutasihd = Trmutasihd::join('mssupplier','mssupplier.Kode','trmutasihd.KodeSuppCust')->where('Transaksi', 'RETUR PEMBELIAN')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiTujuan', $lokasi)->orderBy('Tanggal')->get();
        } else {
            $trmutasihd = Trmutasihd::join('mssupplier','mssupplier.Kode','trmutasihd.KodeSuppCust')->where('Transaksi', 'RETUR PEMBELIAN')->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->where('LokasiTujuan', $lokasi)->where('KodeSuppCust', $request->get('supplier'))->orderBy('Tanggal')->get();
        }
        $arr = [];

        $sumdiskon = 0;
        $sumpajak = 0;
        $sumtotal = 0;
        $sumekop = 0;
        $sumtunai = 0;
        $sumkredit = 0;
        foreach ($trmutasihd as $key => $value) {
            $transaksi = 'RETUR PEMBELIAN';

            $trmutasidt = Trmutasidt::where('Nomor', $value->Nomor)->where('Transaksi', $transaksi)->get();
            $subtotal = 0;

            foreach ($trmutasidt as $key => $row) {
                $subtotal += $row->Harga;
            }

            $x['Transaksi'] = $transaksi;
            $x['Lokasi'] = $value->LokasiTujuan;
            $x['Tanggal'] = $value->Tanggal;
            $x['Nomor'] = $value->Nomor;
            $x['Supplier'] = $value->KodeSuppCust;
            $x['Nama'] = $value->Nama;

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
                "frontend.pos.laporan.retur_pembelian.pdf",
                [
                    'laporan' => $arr, 'periode1' => $periode1, 'periode2' => $periode2,
                    'diskon' => $sumdiskon, 'pajak' => $sumpajak, 'total' => $sumtotal,
                    'tunai' => $sumtunai, 'kredit' => $sumkredit, 'ekop' => $sumekop
                ]
            )->setPaper('a3', 'landscape');
            return $pdf->stream('laporan-retur-pembelian-pdf', array('Attachment' => 0));
        }
    }

    public function cetakDetail(Request $request)
    {
        $periode1 = $request->get('periode1');
        $periode2 = $request->get('periode2');
        $lokasi = $request->get('lokasi');
        if ($request->get('supplier') == 'UMUM') {
            $trmutasihd =  DB::table('trmutasihd')->where('Transaksi', 'RETUR PEMBELIAN')->where('LokasiTujuan', $lokasi)->where('trmutasihd.KodeSuppCust', $request->get('supplier'))->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->get();
        } elseif ($request->get('supplier') != 'semua') {
            $trmutasihd =  Trmutasihd::join('mssupplier','mssupplier.Kode','trmutasihd.KodeSuppCust')->where('Transaksi', 'RETUR PEMBELIAN')->where('LokasiTujuan', $lokasi)->where('trmutasihd.KodeSuppCust', $request->get('supplier'))->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->get();
        } else if ($request->get('supplier') == 'semua') {
            $trmutasihd =  Trmutasihd::join('mssupplier','mssupplier.Kode','trmutasihd.KodeSuppCust')->where('Transaksi', 'RETUR PEMBELIAN')->where('LokasiTujuan', $lokasi)->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->get();
        } else {
            $trmutasihd = Trmutasihd::where('LokasiTujuan', $lokasi)->where('Transaksi', 'RETUR PEMBELIAN')->where('trmutasihd.KodeSuppCust', $request->get('supplier'))->whereDate('Tanggal', '>=', $periode1)->whereDate('Tanggal', '<=', $periode2)->get();
        }

        $arr = [];
        foreach ($trmutasihd as $key => $value) {
            $datadetail = DB::table('msbarang')->leftJoin('trmutasidt', 'msbarang.Kode', 'trmutasidt.KodeBarang')->where('Nomor', $value->Nomor)->get();
            $x['datadetail'] = json_decode(json_encode($datadetail), true);
            $cv = json_decode(json_encode($value), true);
            $data = array_merge($x, $cv);
            array_push($arr, $data);
        }

        $periode1 = date("l, F j, Y", strtotime($periode1));
        $periode2 = date("l, F j, Y", strtotime($periode2));

        $pdf = PDF::loadview(
            "frontend.pos.laporan.retur_pembelian.detail.pdf",
            ['data' => $arr, 'periode1' => $periode1, 'periode2' => $periode2]
        )->setPaper('a3', 'landscape');
        return $pdf->stream('laporan-detail-retur-pembelian-pdf', array('Attachment' => 0));
        // return view("frontend.pos.laporan.pembelian.detail.pdf", ['data' => $arr, 'periode1' => $periode1, 'periode2' => $periode2]);
    }
}
