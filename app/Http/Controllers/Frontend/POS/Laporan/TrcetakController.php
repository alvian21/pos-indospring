<?php

namespace App\Http\Controllers\Frontend\POS\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trcetak;
use App\Msbarang;
use Illuminate\Http\Response;
use PDF;
use Illuminate\Support\Facades\Validator;

class TrcetakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $msbarang = Msbarang::all();
        $cetak = Trcetak::join('msbarang', 'msbarang.Kode', 'trcetak.KodeBarang')->get();
        return view('frontend.pos.laporan.trcetak.index', ['msbarang' => $msbarang, 'cetak' => $cetak]);
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
            'barang' => 'required|unique:trcetak,KodeBarang'
        ]);

        if ($validator->fails()) {
            $html = '';
            foreach ($validator->errors()->all() as $fail) {
                $html .= '<div class="alert alert-danger" role="alert">
                ' . $fail . '
              </div>';
            }

            return response()->json([
                'status' => false,
                'data' => $html
            ]);
        } else {
            $trcetak = new Trcetak();
            $trcetak->KodeBarang = $request->get('barang');
            $trcetak->LastUpdate = date('Y-m-d H:i:s');
            $trcetak->UserUpdate = auth()->user()->UserLogin;
            $trcetak->save();

            $html = '<div class="alert alert-success" role="alert">
            Cetak Label Berhasil Disimpan
          </div>';

            return response()->json([
                'status' => true,
                'data' => $html
            ]);
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
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $cetak = Trcetak::findOrFail($id);
            $cetak->delete();
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function cetak()
    {
        $cetak = Trcetak::join('msbarang', 'msbarang.Kode', 'trcetak.KodeBarang')->get()->toArray();
        $count = Trcetak::count();
        $bagi = $count / 4;

        // dd($bagi);
        $arr = [];
        $length = 4;
        $from  = 0;
        for ($i = 0; $i < $bagi; $i++) {
            $x = array_slice($cetak, $from, $length);
            $from = $from + 4;
            // $length = $length + 4;
            array_push($arr, $x);
        }

        // dd($arr);
        $pdf = PDF::loadview(
            "frontend.pos.laporan.trcetak.pdf",
            [
                'cetak' => $arr
            ]
        )->setPaper('a4', 'potrait');
        return $pdf->stream('laporan-cetaklabel-pdf', array('Attachment' => 0));
    }
}
