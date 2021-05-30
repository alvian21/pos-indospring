@extends('frontend.master')

@section('title', 'Transaksi | penjualan')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi | penjualan</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Transaksi | penjualan</h4>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form action="" method="post" id="formTransaksi">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="transaksi">Transaksi</label>
                                        <input type="text" class="form-control" id="transaksi" name="transaksi" readonly
                                            value="PENJUALAN">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nomor">Nomor</label>
                                        <input type="text" class="form-control" id="nomor" name="nomor"
                                            value="{{$formatNomor}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="text" class="form-control" id="tanggal" name="tanggal" readonly
                                            value="{{date('d M y H:i')}}">
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="lokasi">Lokasi</label>
                                        <input type="text" class="form-control" id="lokasi" name="lokasi"
                                            value="{{auth()->user()->KodeLokasi}}" readonly>
                                    </div>
                                </div>

                            </div>
                            <div class="row">


                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="diskon_persen">Diskon (%)</label>
                                        <input type="number" class="form-control" id="diskon_persen" min="0"
                                            value="{{$trpenjualan["diskon_persen"]}}" name="diskon_persen" required>
                                    </div>

                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="diskon_rp">Diskon (Rp)</label>
                                        <input type="text" class="form-control" id="diskon_rp" min="0"
                                            value="{{$trpenjualan["diskon_rp"]}}" name="diskon_rp" required>
                                    </div>

                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="pajak">Pajak</label>
                                        <input type="number" class="form-control" id="pajak" name="pajak" min="10"
                                            value="{{$trpenjualan["pajak"]}}" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="ttl_harga">Total Harga</label>
                                        <input type="text" class="form-control" id="ttl_harga"
                                            value="{{$trpenjualan["total_harga"]}}" name="ttl_harga" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="ttl_harga_pajak">Total Harga + Pajak</label>
                                        <input type="text" class="form-control ttl_harga_pajak" id="ttl_harga_pajak"
                                            value="{{$trpenjualan["total_harga_setelah_pajak"]}}" name="ttl_harga_pajak"
                                            readonly>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="keterangan">keterangan</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan"
                                            rows="3">{{$trpenjualan["keterangan"]}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Detail Transaksi | penjualan</h4>
                        <button type="button" class="btn btn-primary float-right btnDetailBarang mr-2">Input
                            Detail Barang</button>
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
                                <input type="number" class="form-control" name="harga" readonly id="harga">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stok">Stok</label>
                                <input type="number" class="form-control" readonly name="stok" id="stok">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" class="form-control" name="qty" id="qty" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="diskon_persen">Diskon (%)</label>
                                <input type="number" class="form-control diskon_persen" name="diskon_persen" value="0">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="diskon_rp">Diskon (Rp)</label>
                                <input type="number" class="form-control diskon_rp" name="diskon_rp" value="0">
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

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btnBarangModal">Insert</button>
                    {{-- <input type="submit" value="submit" class="btn btn-primary" name="submit"> --}}

                </div>
            </form>
        </div>
    </div>
</div>

{{-- modal total --}}
<div class="modal fade " id="totalModal" tabindex="-1" role="dialog" aria-labelledby="totalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="totalModalLabel">Total Belanja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formTotal" method="POST" action="{{route('pos.penjualan.save')}}">
                @csrf
                <div class="modal-body">
                    <div id="alert-total">
                        <div class="alert alert-danger alertdangertotal" role="alert">

                        </div>
                        <div class="alert alert-success alertsuccesstotal" role="alert">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="total_belanja">Total Belanja</label>
                                <input type="number" class="form-control" name="total_belanja" id="total_belanja"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="barcode_cust">Customer (Tempelkan Ekop/ scan ID Barcode)</label>
                                <select class="form-control" id="barcode_cust" name="barcode_cust">
                                    <option value="0">Pilih Customer</option>
                                    @foreach ($msanggota as $item)
                                    <option value="{{$item->Kode}}">{{$item->Kode}} | {{$item->Nama}} |
                                        {{$item->NoEkop}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pembayaran_ekop">Pembayaran Ekop</label>
                                <input type="text" class="form-control" name="pembayaran_ekop" id="pembayaran_ekop">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="saldo_ekop">Saldo Ekop</label>
                                <input type="text" class="form-control" readonly name="saldo_ekop" id="saldo_ekop">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ttl_pembayaran_tunai">Total Pembayaran Tunai</label>
                                <input type="text" class="form-control" readonly name="ttl_pembayaran_tunai" value="0"
                                    id="ttl_pembayaran_tunai">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pembayaran_tunai">Pembayaran Tunai</label>
                                <input type="text" class="form-control" name="pembayaran_tunai" value="0"
                                    id="pembayaran_tunai">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kembalian">Kembalian</label>
                                <input type="text" class="form-control" readonly name="kembalian" value="0"
                                    id="kembalian">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btnTotalModal">Ok</button>
                    {{-- <input type="submit" value="submit" class="btn btn-primary" name="submit"> --}}

                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){

        $('#diskon_rp').mask('000.000.000.000', {
            reverse: true
        });
        $('.diskon_rp').mask('000.000.000.000', {
            reverse: true
        });
        $('#ttl_harga').mask('000.000.000.000', {
            reverse: true
        });
        $('#ttl_harga_pajak').mask('000.000.000.000', {
            reverse: true
        });
        $('#subtotal').mask('000.000.000.000', {
            reverse: true
        });
        $('#pembayaran_ekop').mask('000.000.000.000', {
            reverse: true
        });
        $('#pembayaran_tunai').mask('000.000.000.000', {
            reverse: true
        });
        $('#kembalian').mask('000.000.000.000', {
            reverse: true
        });
    $('#alert-detail').hide();
    var subtotal;
     var qty;
     var harga;
     var ds_rp;
     var ds_persen;
     var ttl_harga=0;


    var table_detail = $("#table-detail").DataTable({
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: "{{ route('pos.penjualan.datadetail') }}",
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

        ],
        fnRowCallback:function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
            $('td:eq(4)', nRow).html(convertToRupiah(aData["harga"]));
            $('td:eq(6)', nRow).html(convertToRupiah(aData["diskon_rp"]));
            $('td:eq(7)', nRow).html(convertToRupiah(aData["subtotal"]));
        }
    });

    // $('.js-example-basic-single').select2();
    $('#barcode_cust').select2();

    // transaksi post
    $(document).on('keyup keydown','#supplier, #diskon_persen, #diskon_rp, #pajak, #lokasi, #keterangan', async function(){
        var form = $('#formTransaksi').serialize();

        csrf_ajax();
       const result = await  $.ajax({
            url:"{{route('pos.transaksi_penjualan.store')}}",
            method:"post",
            data:form
        });
      $('#ttl_harga').val(convertToRupiah(result['total_harga']));
      $('#ttl_harga_pajak').val(convertToRupiah(result['total_harga_setelah_pajak']));
    })

    $('.addTransaksi').on('click',function () {
        $('#transaksiModal').modal('show');
    })
    var select2=  $('#barang').select2();

    $('.btnDetailBarang').on('click',function () {
        $('#barangModal').modal('show');
        setTimeout(function(){ $('#barang').select2('open');},500)
        ds_persen = $('.diskon_persen').val();
        ds_rp = $('.diskon_rp').val();
        $('.btnBarangModal').removeClass('btnDetailUpdate').addClass('btnDetailInsert')
    });

    $('#barang').on('change', function () {
        var kode_barang = $(this).val();
        $('#alert-detail').hide();
        if(kode_barang != 0){
            $.ajax({
            url:"{{route('pos.penjualan.databarang')}}",
            method:"GET",
            data:{
                'kode_barang':kode_barang
            },
            success:function(data){
                $('#nama_barang').val(data["Nama"])
                $('#stok').val(data["Saldo"]);
                var harga = convertToRupiah(data["HargaJual"]);

                $('#harga').val(harga);
            }
        })
        }

     });

     function csrf_ajax(){
        $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
     }

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

     function convertToRupiah(angka) {
        var rupiah = '';
        var angkarev = angka.toString().split('').reverse().join('');
        for (var i = 0; i < angkarev.length; i++) {
            if (i%3 == 0) {
            rupiah += angkarev.substr(i,3)+'.';
            }
        }
        return rupiah.split('',rupiah.length-1).reverse().join('');
        }

     $(document).on('keyup','#harga, #qty, .diskon_rp, .diskon_persen',function(){
         var barang = $('#barang').val();
         if(barang != 0){
            qty = $("#qty").val();
            harga = $('#harga').val();
            ds_persen = $('.diskon_persen').val();
            ds_rp = $('.diskon_rp').val();
            ds_rp = ds_rp.replace('.','');
            harga = harga.replace('.','');
            subtotal = qty * harga;

            subtotal = diskon_persen(subtotal, ds_persen);
            subtotal = diskon_rp(subtotal, ds_rp);
            if(subtotal <=0){
                subtotal = 0;
            }
            subtotal = convertToRupiah(subtotal)
            $('#subtotal').val(subtotal);
         }else{
            $('.alert-danger').text('pilih barang terlebih dahulu')
                $('#alert-detail').show();
                $('.alert-success').hide();
         }

     })

     $(document).on('click','.btnDetailInsert',function () {

        var barang = $('#barang').val();
        var stok = $('#stok').val();
        var qty = $('#qty').val();
            if(barang == '0'){
               $('.alert-danger').text('pilih barang terlebih dahulu')
                $('#alert-detail').show();
                $('.alert-success').hide();
            }else if(qty == undefined || qty == 0 || qty == ''){
                $('.alert-danger').text('qty harus diisi')
                $('#alert-detail').show();
                $('.alert-success').hide();
            }else if(parseInt(qty) > parseInt(stok)){
                $('.alert-danger').text('maksimal qty adalah '+stok)
                $('#alert-detail').show();
                $('.alert-success').hide();
            }
            else{
                csrf_ajax();
               $.ajax({
                   url:"{{route('pos.detail_transaksi_penjualan.store')}}",
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

                       $('.btnSimpan').show();
                       $('#barang').val('0');
                       var ttl_harga = convertToRupiah(String(data['total_harga']));
                       var ttl_harga_pajak = convertToRupiah(String(data['total_harga_setelah_pajak']));
                       $("#ttl_harga").val(ttl_harga);
                        $("#ttl_harga_pajak").val(ttl_harga_pajak);
                   }
               })
            }
      });

      $(document).on('click','.btnDetailUpdate',function () {
        var barang = $('#barang').val();
        var stok = $('#stok').val();
        var qty = $('#qty').val();
            if(barang == '0'){
            $('.alert-danger').text('pilih barang terlebih dahulu')
            $('.alert-success').hide();
                $('#alert-detail').show();

            }else if(qty == undefined || qty == 0 || qty == ''){
                $('.alert-danger').text('qty harus diisi')
                $('#alert-detail').show();
                $('.alert-success').hide();
            }else if(parseInt(qty) > parseInt(stok)){
                $('.alert-danger').text('maksimal qty adalah '+stok)
                $('#alert-detail').show();
                $('.alert-success').hide();
            }else{
                csrf_ajax();
            $.ajax({
                url:"{{route('pos.detail_transaksi_penjualan.update')}}",
                method: "POST",
                data: $('#formDetail').serialize(),
                success:function(data){
                    $('#formDetail').trigger("reset");
                    table_detail.ajax.reload();

                    $('[id=barang]').val('0').trigger('change');
                    $('.btnSimpan').show();
                    $('.alert-success').text('Data berhasil di update')
                    $('.alert-danger').hide()
                    $('#alert-detail').show();
                    setTimeout(function(){ $('#alert-detail').hide()
                    $('#barangModal').modal('hide')
                    },3000);
                    var ttl_harga = convertToRupiah(String(data['total_harga']));
                       var ttl_harga_pajak = convertToRupiah(String(data['total_harga_setelah_pajak']));
                       $("#ttl_harga").val(ttl_harga);
                        $("#ttl_harga_pajak").val(ttl_harga_pajak);
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

    $('#totalModal').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
        $('[id=barcode_cust]').val('0').trigger('change');
    });



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
          $('#nama_barang').val(data['nama_barang']);
          var dataselect = "";
          $('#barang option').each(function(){
             if($(this).val()==data['barang'].trim()){
               dataselect = $(this).val();
             }
          })
          var harga = convertToRupiah(data['harga']);
          var diskon_rp = convertToRupiah(data['diskon_rp']);
          var subtotal = convertToRupiah(data['subtotal']);
          setTimeout(function(){ $('#barang').select2('open');},500)
          $('#harga').val(harga);
          $('.btnBarangModal').text('update');
          $('.keterangan').val(data['keterangan']);
          $('.diskon_rp').val(diskon_rp);
          $('.diskon_persen').val(data['diskon_persen']);
          $('#subtotal').val(subtotal);
          $('#qty').val(data['qty']);
          $('[id=barang]').val(dataselect).trigger('change');
          $('#barangModalLabel').text('Edit detail barang')
          $('.btnBarangModal').removeClass('btnDetailInsert').addClass('btnDetailUpdate')
          $('#barangModal').modal('show')
      })


    $('.btnsimpan').on('click', function(){
        var ttl_belanja = $("#ttl_harga_pajak").val();
        $("#total_belanja").val(ttl_belanja);
        var cek = "";
        $.ajax({
            url:"{{route('pos.penjualan.check')}}",
            async:false,
            method:"GET",
            success:function(data){
                if(data['message']=='true'){
                    cek = true;
                }else{
                    cek = false;
                }
            }
        })
        if(cek){
            $('#alert-total').hide();
             $('#totalModal').modal('show');
        }else{
            swal("Detail Belum Diisi!");
        }

    })

    $(document).on('click','.btnDelete', function () {
       var urut = $(this).data('urut')

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
               window.location.href="{{url('/admin/pos/penjualan/delete_detail/')}}/"+urut;
            } else {
                swal("Your imaginary file is safe!");
            }
            });
     })

     $(document).on('change','#barcode_cust', function(){
         var kode = $(this).val();
         var ttl_belanja=$('#total_belanja').val();
         ttl_belanja = ttl_belanja.replace('.','');
         if(kode != 0){
            $('#alert-total').hide();
            $.ajax({
             url:"{{route('pos.penjualan.ceksaldo')}}",
             method:'GET',
             data:{
                 'kode':kode
             }, success:function(data){
                    var saldo = data['Saldo'];
                    var ekop = 0;
                    var ttl_belanja=$('#total_belanja').val();
                    var pembayaran_tunai  = 0;
                    if(parseInt(saldo) > parseInt(replace_titik(ttl_belanja))){
                        $('#pembayaran_tunai').val(0);
                        $('#ttl_pembayaran_tunai').val(0);
                        $('#pembayaran_ekop').val(convertToRupiah(replace_titik(ttl_belanja)));
                    }else{
                        $('#ttl_pembayaran_tunai').val(ttl_belanja);
                        $('#pembayaran_ekop').val(0);
                    }
                    $('#saldo_ekop').val(convertToRupiah(data['Saldo']));

             }
         })
         }

     })

    //  $(document).on('keyup', '#pembayaran_tunai', function(){
    //      var tunai = $(this).val();
    //      tunai = tunai.replace('.','');
    //      var ekop = $('#pembayaran_ekop').val();
    //      ekop = ekop.replace('.','');
    //      var ttl_belanja=$('#total_belanja').val();
    //      ttl_belanja = ttl_belanja.replace('.','');
    //      var hasil_total_belanja ;
    //     if(ttl_belanja != ekop){
    //         hasil_total_belanja = parseInt(ttl_belanja)-parseInt(ekop);
    //     }else{
    //         hasil_total_belanja = ttl_belanja;
    //     }

    //     var saldo_ekop = $('#saldo_ekop').val();
    //     saldo_ekop = saldo_ekop.replace('.','');
    //     if(ekop > saldo_ekop ){
    //         $('.alertdangertotal').text('Maaf saldo ekop tidak cukup');
    //         $('.alertsuccesstotal').hide();
    //         $('#alert-total').show();
    //         return false;
    //     }

    //      if(tunai >= hasil_total_belanja){
    //         var last_result = tunai-hasil_total_belanja;
    //         $('#kembalian').val(convertToRupiah(last_result));
    //      }

    //  });


     function replace_titik(cek){
         if(cek!=undefined){
            var datacek = cek.replace('.','');
            return datacek;
         }

     }


     $(document).on('keyup change', '#pembayaran_ekop, #pembayaran_tunai', function(){
         var ekop = $('#pembayaran_ekop').val();
         var ttl_belanja=$('#total_belanja').val();
         var tunai = $("#pembayaran_tunai").val();
        var saldo_ekop = $('#saldo_ekop').val();
        var barcode_cust = $('#barcode_cust').val();
        if(barcode_cust == '0'){
            $('.alertdangertotal').text('Pilih customer terlebih dahulu');
            $('.alertsuccesstotal').hide();
            $('#alert-total').show();
            return false;
        }else if(parseInt(ekop.replace('.','')) > parseInt(ttl_belanja.replace('.',''))){
            $('.alertdangertotal').text('Maaf pembayaran maksimal adalah '+ttl_belanja);
            $('.alertsuccesstotal').hide();
            $('#alert-total').show();
            return false;
        }else{
            $('#alert-total').hide();
            ekop = replace_titik(ekop);

            ttl_belanja = replace_titik(ttl_belanja);
            tunai = replace_titik(tunai);
            saldo_ekop = replace_titik(saldo_ekop);
            var hasil_total = 0;

            if(parseInt(ekop) > parseInt(saldo_ekop) ){
                $('.alertdangertotal').text('Maaf saldo ekop tidak cukup');
                $('.alertsuccesstotal').hide();
                $('#alert-total').show();
                return false;
            }else{
                //tunai
                if(ekop == ttl_belanja){
                    $('#kembalian').val(0);
                    hasil_total = 0;
                }else if(ekop > 0 && ekop < ttl_belanja  ){
                    hasil_total = ttl_belanja - ekop;
                    $('#ttl_pembayaran_tunai').val(convertToRupiah(hasil_total))
                    hasil_total = tunai - hasil_total;

                }else if((ekop == 0 || ekop ==  '') && tunai > 0){
                    hasil_total = tunai - ttl_belanja;
                }
                if(hasil_total >= 0){
                    $('#kembalian').val(convertToRupiah(hasil_total));
                }


            }

        }


     });


     $(document).on('click','.btnTotalModal', function(){

        var ekop = $('#pembayaran_ekop').val();
         var ttl_belanja=$('#total_belanja').val();
         var tunai = $("#pembayaran_tunai").val();
        var saldo_ekop = $('#saldo_ekop').val();
        var barcode_cust = $('#barcode_cust').val();
        if(barcode_cust == '0'){
            $('.alertdangertotal').text('Pilih customer terlebih dahulu');
            $('.alertsuccesstotal').hide();
            $('#alert-total').show();
            return false;
        }else if(parseInt(ekop.replace('.','')) > parseInt(ttl_belanja.replace('.',''))){
            $('.alertdangertotal').text('Maaf pembayaran maksimal adalah '+ttl_belanja);
            $('.alertsuccesstotal').hide();
            $('#alert-total').show();
            return false;
        }else{
            $('#alert-total').hide();
            ekop = replace_titik(ekop);

            ttl_belanja = replace_titik(ttl_belanja);
            tunai = replace_titik(tunai);
            saldo_ekop = replace_titik(saldo_ekop);
            var hasil_total = 0;

            if(parseInt(ekop) > parseInt(saldo_ekop) ){
                $('.alertdangertotal').text('Maaf saldo ekop tidak cukup');
                $('.alertsuccesstotal').hide();
                $('#alert-total').show();
                return false;
            }else if((parseInt(tunai) == 0 || tunai == '') && parseInt(ekop) < parseInt(ttl_belanja)){
                $('.alertdangertotal').text('Maaf pembayaran ekop kurang');
                $('.alertsuccesstotal').hide();
                $('#alert-total').show();
                return false;

            }else if((parseInt(ekop) == 0 || ekop == '') && parseInt(tunai) < parseInt(ttl_belanja)){
                $('.alertdangertotal').text('Maaf pembayaran tunai kurang');
                $('.alertsuccesstotal').hide();
                $('#alert-total').show();
                return false;
            }else if(parseInt(ekop) > 0 && parseInt(tunai) > 0){
               hasil_total = parseInt(tunai) + parseInt(ekop);
                if(hasil_total < ttl_belanja){
                    $('.alertdangertotal').text('Maaf pembayaran tunai dan ekop kurang');
                    $('.alertsuccesstotal').hide();
                    $('#alert-total').show();
                    return false;
                }else{
                    $('#formTotal').submit();
                    return true
                }
            }
            else{
                $('#formTotal').submit();
                return true
            }

        }


     })

})
</script>
@endsection
