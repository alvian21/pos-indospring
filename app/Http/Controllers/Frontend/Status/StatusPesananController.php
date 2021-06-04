<?php

namespace App\Http\Controllers\Frontend\Status;

use App\Http\Controllers\Controller;
use App\Trmutasidt;
use Illuminate\Http\Request;
use App\Trmutasihd;
use Illuminate\Support\Facades\DB;
use App\Msanggota;
use App\Msbarang;
class StatusPesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("frontend.dashboard.status_pesanan.index");
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
        $trmutasidt = DB::table('trmutasidt')
        ->join('msbarang','msbarang.Kode','trmutasidt.KodeBarang')
        ->where('trmutasidt.Nomor',$id)->get();
        return view('frontend.dashboard.status_pesanan.show', ['barang' => $trmutasidt]);
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

    public function getDataPesanan(Request $request)
    {
        if ($request->ajax()) {
            $date = date('Y-m-d');
            $trmutasihd = Trmutasihd::where('Transaksi', 'CHECKOUT')->whereDate('Tanggal',$date)->where('LokasiAwal', auth()->user()->KodeLokasi)->get();

            $arr = [];
            foreach ($trmutasihd as $key => $value) {
                $anggota = Msanggota::where('Kode', $value->KodeSuppCust)->first();
                $sub = array();
                $sub['nomor'] = $value->Nomor;
                $sub['tanggal'] = $value->Tanggal;
                $sub['kode_anggota'] = $value->KodeSuppCust;
                if ($anggota) {
                    $sub['nama_anggota'] = $anggota->Nama;
                } else {
                    $sub['nama_anggota'] = '';
                }

                if ($value->StatusPesanan == "Dalam Proses") {
                    $sub['status_pesanan'] = '<select class="form-control" data-nomor="' . $sub['nomor'] . '" id="status_pesanan">
                            <option value="Dalam Proses" selected >Dalam Proses</option>
                            <option value="Barang Sudah Siap">Barang Sudah Siap</option>
                            <option value="Barang Telah Diambil">Barang Telah Diambil</option>
                        </select>';
                } elseif ($value->StatusPesanan == "Barang Sudah Siap") {
                    $sub['status_pesanan'] = '<select class="form-control" data-nomor="' . $sub['nomor'] . '" id="status_pesanan">
                            <option value="Dalam Proses" >Dalam Proses</option>
                            <option value="Barang Sudah Siap" selected>Barang Sudah Siap</option>
                            <option value="Barang Telah Diambil">Barang Telah Diambil</option>
                        </select>';
                } else {
                    $sub['status_pesanan'] = '<select class="form-control" data-nomor="' . $sub['nomor'] . '" id="status_pesanan">
                            <option value="Dalam Proses" >Dalam Proses</option>
                            <option value="Barang Sudah Siap" >Barang Sudah Siap</option>
                            <option value="Barang Telah Diambil" selected>Barang Telah Diambil</option>
                        </select>';
                }

                $sub['action'] = '<button type="button" data-nomor="' . $sub['nomor'] . '" class="btn btn-info btnshow">Show</button>';

                $arr[] = $sub;
            }
            $count = count($arr);
            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $arr
            ];
            return response()->json($output);
        }
    }

    public function updateStatus(Request $request)
    {
        if ($request->ajax()) {
            $nomor = $request->get('nomor');
            $trmutasihd = Trmutasihd::where('Nomor', $nomor)->where('LokasiAwal', auth()->user()->KodeLokasi)->first();
            $trmutasihd->UserUpdateSP = auth()->user()->UserLogin;
            $trmutasihd->LastUpdateSP = date('Y-m-d H:i:s');
            $trmutasihd->StatusPesanan = $request->get('status');
            $trmutasihd->save();

            return response()->json([
                'status' => 'true'
            ]);
        }
    }
}
