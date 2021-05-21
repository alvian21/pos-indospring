@extends('frontend.master')

@section('title', 'Transaksi | Pembelian')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi | Pembelian</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Transaksi | Pembelian</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary float-right addTransaksi ">Transaksi
                                    Pembelian</button>
                                <button type="button" class="btn btn-primary float-right adddetailBarang mr-2">Input
                                    Detail Barang</button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-bordered display nowrap" id="table-transaksi" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Transaksi</th>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Kode</th>
                                        <th>Supplier</th>
                                        <th>Diskon Persen</th>
                                        <th>Diskon Tunai</th>
                                        <th>Pajak</th>
                                        <th>Total Harga</th>
                                        <th>Keterangan</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @if(session()->has('transaksi_pembelian'))
                                    <tr>
                                        <td>{{$trpembelian->transaksi}}</td>
                                        <td>{{$trpembelian->nomor}}</td>
                                        <td>{{$trpembelian->tanggal}}</td>
                                        <td>{{$trpembelian->kode}}</td>
                                        <td>{{$trpembelian->supplier}}</td>
                                        <td>{{$trpembelian->diskon_persen}}</td>
                                        <td>{{$trpembelian->diskon_rp}}</td>
                                        <td>{{$trpembelian->pajak}}</td>
                                        <td>{{$trpembelian->total_harga}}</td>
                                        <td>{{$trpembelian->keterangan}}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Detail Transaksi | Pembelian</h4>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-bordered display nowrap" id="table-detail" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Urut</th>
                                        <th>Kode Barang</th>
                                        <th>Barang</th>
                                        <th>Diskon Persen</th>
                                        <th>Diskon Tunai</th>
                                        <th>Harga</th>
                                        <th>SubTotal</th>
                                        <th>Keterangan</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @if(session()->has('detail_transaksi_pembelian'))
                                    @foreach ($datadetail as $row )
                                    <tr>
                                        <td>{{$row->urut}}</td>
                                        <td>{{$row->barang}}</td>
                                        <td>{{$row->nama_barang}}</td>
                                        <td>{{$row->diskon_persen}}</td>
                                        <td>{{$row->diskon_rp}}</td>
                                        <td>{{$row->harga}}</td>
                                        <td>{{$row->subtotal}}</td>
                                        <td>{{$row->keterangan}}</td>
                                    </tr>
                                    @endforeach

                                    @endif
                                </tbody>

                            </table>
                            @if(session()->has('detail_transaksi_pembelian'))
                            <div class="float-right mt-3">
                                <button type="button" class="btn btn-primary btnsimpan">Simpan</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- modal transaksi --}}
<div class="modal fade bd-example-modal-lg" id="transaksiModal" tabindex="-1" role="dialog"
    aria-labelledby="transaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transaksiModalLabel">Transaksi Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('pos.transaksi_pembelian.store')}}">
                @csrf
                <div class="modal-body">
                    @if(session()->has('transaksi_pembelian'))
                    <h3 class="text-center">Anda sudah menambahkan transaksi, mohon buat data detail barang</h3>
                    @else
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="transaksi">Transaksi</label>
                                <input type="text" class="form-control" id="transaksi" name="transaksi" readonly
                                    value="PEMBELIAN">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nomor">Nomor</label>
                                <input type="text" class="form-control" id="nomor" name="nomor" value="{{$formatNomor}}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="text" class="form-control" id="tanggal" name="tanggal" readonly
                                    value="{{date('d M y H:i')}}">
                            </div>

                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <select class="form-control js-example-basic-single" id="supplier" name="supplier">
                                    @forelse ($mssupplier as $item)
                                    <option value="{{$item->Kode}}">{{$item->Kode}} | {{$item->Nama}}</option>
                                    @empty
                                    <option value="">not found</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_persen">Diskon (%)</label>
                                <input type="text" class="form-control" id="diskon_persen" name="diskon_persen"
                                    required>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pajak">Pajak</label>
                                <input type="text" class="form-control" id="pajak" name="pajak" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_rp">Diskon (Rp)</label>
                                <input type="text" class="form-control" id="diskon_rp" name="diskon_rp" required>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <select class="form-control js-example-basic-single" id="lokasi" name="lokasi">
                                    @forelse ($mslokasi as $item)
                                    <option value="{{$item->Kode}}">{{$item->Kode}} | {{$item->Nama}}</option>
                                    @empty
                                    <option value="">not found</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="keterangan">keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="total">Total</label>
                                <input type="text" class="form-control" id="total" readonly>
                            </div>
                        </div>
                    </div> --}}
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    @if(!session()->has('transaksi_pembelian'))
                    <button type="submit" class="btn btn-primary">Post</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
{{-- modal barang --}}
<div class="modal fade bd-example-modal-lg" id="barangModal" tabindex="-1" role="dialog"
    aria-labelledby="barangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="barangModalLabel">Input Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('pos.detail_transaksi_pembelian.store')}}" id="formDetail">
                @csrf
                <div class="modal-body">
                    @if(session()->has('transaksi_pembelian'))
                    <div id="alert-detail">
                        <div class="alert alert-danger" role="alert">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="barang">Barang</label>
                                <select class="form-control js-example-basic-single" name="barang" id="barang">
                                    <option value="0">Pilih Barang</option>
                                    @forelse ($msbarang as $item)
                                    <option value="{{$item->Kode}}">{{$item->Kode}} | @if($item->KodeBarcode!=null)
                                        {{$item->KodeBarcode}} | @endif {{$item->Nama}}</option>
                                    @empty

                                    @endforelse
                                </select>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nama_barang">Nama barang</label>
                                <input type="text" class="form-control" name="nama_barang" readonly id="nama_barang">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="text" class="form-control" name="harga" readonly id="harga">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" class="form-control" name="qty" id="qty">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_persen">Diskon (%)</label>
                                <input type="text" class="form-control" name="diskon_persen" id="diskon_persen">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_rp">Diskon (Rp))</label>
                                <input type="text" class="form-control" name="diskon_rp" id="diskon_rp">
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keterangan">keterangan</label>
                                <textarea class="form-control" name="keterangan" id="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subtotal">SubtTotal</label>
                                <input type="text" class="form-control" name="subtotal" id="subtotal" readonly>
                            </div>

                        </div>

                    </div>

                    @else
                    <h3 class="text-center">Input Data Transaksi Pembelian terlebih dahulu</h3>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @if(session()->has('transaksi_pembelian'))
                    {{-- <button type="submit" class="btn btn-primary">Insert</button> --}}
                    <input type="submit" value="submit" class="btn btn-primary" name="submit">
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){

    $('#alert-detail').hide();

    var table = $("#table-transaksi").DataTable({
        "scrollX": true,
    });

    var table_detail = $("#table-detail").DataTable({
        "scrollX": true,
    });

    $('.js-example-basic-single').select2();

    $('.addTransaksi').on('click',function () {
        $('#transaksiModal').modal('show');
    })
    $('.adddetailBarang').on('click',function () {
        $('#barangModal').modal('show');
    });

    $('#barang').on('change', function () {
        var kode_barang = $(this).val();
        $('#alert-detail').hide();
        if(kode_barang != 0){
            $.ajax({
            url:"{{route('pos.pembelian.databarang')}}",
            method:"GET",
            data:{
                'kode_barang':kode_barang
            },
            success:function(data){
                $('#nama_barang').val(data["Nama"])
                $('#harga').val(data["HargaJual"])
            }
        })
        }

     });

     $('#qty').on('keyup', function(){
         var qty = $(this).val();
         var harga = $('#harga').val();

         var subtotal = qty * harga;
         $('#subtotal').val(subtotal);
     });

     $('#formDetail').on('submit',function () {
        var barang = $('#barang').val();
        var qty = $('#qty').val();
            if(barang == '0'){
               $('.alert-danger').text('pilih barang terlebih dahulu')
                $('#alert-detail').show();
                return false;
            }else if(qty == undefined || qty == 0 || qty == ''){
                $('.alert-danger').text('qty harus diisi')
                $('#alert-detail').show();
                return false;
            }else{
                return true;
            }
      });

    $('.btnsimpan').on('click', function(){
        swal({
            title: "Apa anda yakin menyimpan data transaksi ini?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willSave) => {
            if (willSave) {
                window.location.href = "{{route('pos.pembelian.save')}}"
            }
            });
    })

})
</script>
@endsection
