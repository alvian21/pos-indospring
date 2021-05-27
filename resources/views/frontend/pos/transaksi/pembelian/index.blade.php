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
                                <button type="button" class="btn btn-primary float-right btnDetailBarang mr-2">Input
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
                                        <th>Total Harga + Pajak</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>

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
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Diskon Persen</th>
                                        <th>Diskon Tunai</th>

                                        <th>SubTotal</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                            <div class="float-right mt-3 btnSimpan">
                                <button type="button" class="btn btn-primary btnsimpan">Simpan</button>
                            </div>
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
                                <input type="text" class="form-control" id="diskon_persen" value="0"
                                    name="diskon_persen" required>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_rp">Diskon (Rp)</label>
                                <input type="text" class="form-control" id="diskon_rp" value="0" name="diskon_rp"
                                    required>
                            </div>

                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pajak">Pajak</label>
                                <input type="text" class="form-control" id="pajak" value="10" name="pajak" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <input type="text" class="form-control" id="lokasi" name="lokasi"
                                    value="{{auth()->user()->KodeLokasi}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="keterangan">keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ttl_harga">Total Harga</label>
                                <input type="text" class="form-control"  value="0" name="ttl_harga" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ttl_harga_pajak">Total Harga + Pajak</label>
                                <input type="text" class="form-control" value="0"  name="ttl_harga_pajak"
                                  readonly>
                            </div>
                        </div>
                    </div>
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
{{-- Modal Edit tr --}}
<div class="modal fade bd-example-modal-lg" id="editTrModal" tabindex="-1" role="dialog"
    aria-labelledby="editTrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTrModalLabel">Edit Transaksi Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('pos.update.trpembelian')}}">
                @csrf
                <div class="modal-body">

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
                                <input type="text" class="form-control" id="nomor" name="nomor" readonly>
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
                                <label for="supplier_edit">Supplier</label>
                                <select class="form-control js-example-basic-single" id="supplier_edit" name="supplier">
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
                                <input type="number" class="form-control" id="diskon_persen_edit" value="0"
                                    name="diskon_persen" required>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_rp">Diskon (Rp)</label>
                                <input type="number" class="form-control" id="diskon_rp_edit" value="0" name="diskon_rp"
                                    required>
                            </div>

                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pajak">Pajak</label>
                                <input type="text" class="form-control" id="pajak_edit" value="10" name="pajak" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <input type="text" class="form-control" id="lokasi" name="lokasi"
                                    value="{{auth()->user()->KodeLokasi}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="keterangan">keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ttl_harga">Total Harga</label>
                                <input type="text" class="form-control" id="ttl_harga" value="0" name="ttl_harga" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ttl_harga_pajak">Total Harga + Pajak</label>
                                <input type="text" class="form-control" value="0" id="ttl_harga_pajak" name="ttl_harga_pajak"
                                  readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary">Update</button>

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
            <form id="formDetail">
                <input type="hidden" name="id_urut" id="id_urut">
                <div class="modal-body">
                    @if(session()->has('transaksi_pembelian'))
                    <div id="alert-detail">
                        <div class="alert alert-danger" role="alert">

                        </div>
                        <div class="alert alert-success" role="alert">
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
                                <input type="number" class="form-control" name="harga" id="harga">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" class="form-control" name="qty" id="qty" value="1">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_persen">Diskon (%)</label>
                                <input type="number" class="form-control diskon_persen" name="diskon_persen"
                                    id="diskon_persen" value="0">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_rp">Diskon (Rp)</label>
                                <input type="number" class="form-control diskon_rp" name="diskon_rp" id="diskon_rp"
                                    value="0">
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keterangan">keterangan</label>
                                <textarea class="form-control keterangan" name="keterangan" id="keterangan"
                                    rows="3"></textarea>
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
                    <button type="button" class="btn btn-primary btnBarangModal">Insert</button>
                    {{-- <input type="submit" value="submit" class="btn btn-primary" name="submit"> --}}
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
    var subtotal;
     var qty;
     var harga;
     var ds_rp;
     var ds_persen;
     var ttl_harga=0;
    var table = $("#table-transaksi").DataTable({
        "columnDefs": [
            { "visible": false, "targets": 9}
        ],
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: "{{ route('pos.pembelian.transaksi') }}",
        columns: [
            {data: 'transaksi', name: 'transaksi'},
            {data: 'nomor', name: 'nomor'},
            {data: 'tanggal', name: 'tanggal'},
            {data: 'kode', name: 'kode'},
            {data: 'supplier', name: 'supplier'},
            {data: 'diskon_persen', name: 'diskon_persen'},
            {data: 'diskon_rp', name: 'diskon_rp'},
            {data: 'pajak', name: 'pajak'},
            {data: 'total_harga_setelah_pajak', name: 'total_harga_setelah_pajak'},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'action', name: 'action', orderable: false, searchable: false},

        ],
    });

    var table_detail = $("#table-detail").DataTable({
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: "{{ route('pos.pembelian.datadetail') }}",
        columns: [
            {data: 'urut', name: 'urut'},
            {data: 'barang', name: 'barang'},
            {data: 'nama_barang', name: 'nama_barang'},
            {data: 'qty', name: 'qty'},
            {data: 'harga', name: 'harga'},
            {data: 'diskon_persen', name: 'diskon_persen'},
            {data: 'diskon_rp', name: 'diskon_rp'},
            {data: 'subtotal', name: 'subtotal'},
            {data: 'action', name: 'action', orderable: false, searchable: false},

        ]
    });

    $('.js-example-basic-single').select2();

    $('.addTransaksi').on('click',function () {
        $('#transaksiModal').modal('show');
    })
    $('.btnDetailBarang').on('click',function () {
        $('#barangModal').modal('show');
        ds_persen = $('.diskon_persen').val();
        ds_rp = $('.diskon_rp').val();
        $('.btnBarangModal').removeClass('btnDetailUpdate').addClass('btnDetailInsert')
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

            }
        })
        }

     });

     function diskon_persen(sub_total, diskon){
        if(diskon >= 0 || diskon != ''){
            var hitung = (diskon/100) * sub_total;
            var hasil = sub_total - hitung;
            return hasil;
        }else{
            return sub_total;
        }
     }
     function diskon_rp(sub_total, diskon){
        if(diskon >= 0 || diskon != ''){
            var hasil = sub_total - diskon;
            return hasil;
        }else{
            return sub_total;
        }
     }

     function pajak(sub_total, pajak){
         if(pajak >= 0 || pajak != ''){
            var ht_pajak = sub_total + ((pajak/100)*sub_total);
            return ht_pajak;
         }else{
            return sub_total;
         }

     }


     $(document).on('keyup','#harga, #qty, .diskon_rp, .diskon_persen',function(){
        qty = $("#qty").val();
        harga = $('#harga').val();
        ds_persen = $('.diskon_persen').val();
        ds_rp = $('.diskon_rp').val();
        subtotal = qty * harga;
        console.log(subtotal);
        subtotal = diskon_persen(subtotal, ds_persen);
        subtotal = diskon_rp(subtotal, ds_rp);
        if(subtotal <=0){
            subtotal = 0;
        }
         $('#subtotal').val(subtotal);
     })

     $(document).on('click','.btnDetailInsert',function () {

        var barang = $('#barang').val();
        var qty = $('#qty').val();
            if(barang == '0'){
               $('.alert-danger').text('pilih barang terlebih dahulu')
                $('#alert-detail').show();
                $('.alert-success').hide();
            }else if(qty == undefined || qty == 0 || qty == ''){
                $('.alert-danger').text('qty harus diisi')
                $('#alert-detail').show();
                $('.alert-success').hide();
            }else{
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
               $.ajax({
                   url:"{{route('pos.detail_transaksi_pembelian.store')}}",
                   method: "POST",
                   data: $('#formDetail').serialize(),
                   success:function(data){
                       $('.alert-success').text('Data berhasil di tambahkan')
                       $('.alert-danger').hide()
                       $('[id=barang]').val('0').trigger('change');
                       $('#alert-detail').show();
                       setTimeout(function(){ $('#alert-detail').hide()},3000);
                       $('#formDetail').trigger("reset");
                       table_detail.ajax.reload();
                       table.ajax.reload();
                       $('.btnSimpan').show();
                       $('#barang').val('0');
                   }
               })
            }
      });

      $(document).on('click','.btnDetailUpdate',function () {
        var barang = $('#barang').val();
        var qty = $('#qty').val();
            if(barang == '0'){
            $('.alert-danger').text('pilih barang terlebih dahulu')
            $('.alert-success').hide();
                $('#alert-detail').show();

            }else if(qty == undefined || qty == 0 || qty == ''){
                $('.alert-danger').text('qty harus diisi')
                $('#alert-detail').show();
                $('.alert-success').hide();
            }else{
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $.ajax({
                url:"{{route('pos.detail_transaksi_pembelian.update')}}",
                method: "POST",
                data: $('#formDetail').serialize(),
                success:function(data){
                    $('#formDetail').trigger("reset");
                    table_detail.ajax.reload();
                    table.ajax.reload();
                    $('[id=barang]').val('0').trigger('change');
                    $('.btnSimpan').show();
                    $('.alert-success').text('Data berhasil di update')
                    $('.alert-danger').hide()
                    $('#alert-detail').show();
                    setTimeout(function(){ $('#alert-detail').hide()
                    $('#barangModal').modal('hide')
                    },3000);

                }
            })
            }
        });

      $('#barangModal').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                $('[id=barang]').val('0').trigger('change');
                $('.btnBarangModal').text('insert');
                $('#barangModalLabel').text('Input detail barang')
                $('.btnBarangModal').removeClass('btnDetailUpdate').addClass('btnDetailInsert')
            });


      $(document).on('click','.btnedittr',function(){

          var row = $(this).closest("tr");
          var data =  $('#table-transaksi').DataTable().row(row).data()
          $('#nomor').val(data['nomor']);
          var dataselect = "";
          $('#supplier_edit option').each(function(){
             if($(this).val()==data['kode'].trim()){
               dataselect = $(this).val();
             }
          })
          $('#ttl_harga').val(data['total_harga'])
          $('#ttl_harga_pajak').val(data['total_harga_setelah_pajak'])
          $('#keterangan').val(data['keterangan'])
          $('[id=supplier_edit]').val(dataselect).trigger('change');
          $('#diskon_persen_edit').val(data['diskon_persen']);
          $('#diskon_rp_edit').val(data['diskon_rp']);
          $('#pajak_edit').val(data['pajak']);
          ttl_harga = data['total_harga_sebelum']
          $('#editTrModal').modal('show');

      })

      $(document).on('keyup','#diskon_persen_edit, #diskon_rp_edit, #pajak_edit', function(){
          var ds_persen_edit = $('#diskon_persen_edit').val();
          var ds_rp_edit = $('#diskon_rp_edit').val();
          var ds_persen = diskon_persen(ttl_harga, ds_persen_edit);
          var ds_rp = diskon_rp(ds_persen, ds_rp_edit);
          var get_pajak = $('#pajak_edit').val();
          var ht_pajak = pajak(ds_rp, get_pajak);
          $('#ttl_harga').val(ds_rp);
          $('#ttl_harga_pajak').val(ht_pajak);
      })

      $(document).on('click', '.btnDetailBarangEdit', function(){
        var row = $(this).closest("tr");
          var data =  $('#table-detail').DataTable().row(row).data()
          $('#id_urut').val(data['urut']);
          $('#harga').val(data['harga']);
          $('#nama_barang').val(data['nama_barang']);
          console.log(data)
          var dataselect = "";
          $('#barang option').each(function(){
             if($(this).val()==data['barang'].trim()){
               dataselect = $(this).val();
             }
          })
          $('.btnBarangModal').text('update');
          $('.keterangan').val(data['keterangan']);
          $('.diskon_rp').val(data['diskon_rp']);
          $('.diskon_persen').val(data['diskon_persen']);
          $('#subtotal').val(data['subtotal']);
          $('#qty').val(data['qty']);
          $('[id=barang]').val(dataselect).trigger('change');
          $('#barangModalLabel').text('Edit detail barang')
          $('.btnBarangModal').removeClass('btnDetailInsert').addClass('btnDetailUpdate')
          $('#barangModal').modal('show')
      })

    $('.btnsimpan').on('click', function(){


        $.ajax({
            url:"{{route('pos.pembelian.check')}}",
            method:"GET",
            success:function(data){
                if(data['message']=='true'){
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
                }else{
                    swal("Detail Belum Diisi!");
                }
            }
        })


    })

    $(document).on('click','.btnDelete', function () {
       var urut = $(this).data('urut')
       console.log(urut);
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
               window.location.href="{{url('/admin/pos/pembelian/delete_detail/')}}/"+urut;
            } else {
                swal("Your imaginary file is safe!");
            }
            });
     })

})
</script>
@endsection
