@extends('frontend.master')

@section('title', 'Transaksi | kasir')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi | Kasir</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Transaksi | Kasir
                            {{auth()->user()->KodeLokasi}}</h4>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form action="" method="post" id="formTransaksi">

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="barang">Barang</label>
                                        <select class="form-control js-example-basic-single" name="barang" id="barang">
                                            <option value="0">Pilih Barang</option>
                                            @forelse ($msbarang as $item)
                                            <option value="{{$item->Kode}}">{{$item->Kode}} |
                                                @if($item->KodeBarcode!=null)
                                                {{$item->KodeBarcode}} | @endif {{$item->Nama}}</option>
                                            @empty

                                            @endforelse
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nama_barang">Nama barang</label>
                                        <input type="text" class="form-control" name="nama_barang" readonly
                                            id="nama_barang">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="harga">Harga</label>
                                        <input type="number" class="form-control" name="harga" readonly id="harga">
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ttl_harga_pajak">Total Harga + Pajak</label>
                                        <input type="text" class="form-control ttl_harga_pajak" style="color: red"
                                            id="ttl_harga_pajak" value="{{$trkasir["total_harga_setelah_pajak"]}}"
                                            name="ttl_harga_pajak" readonly>
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
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Detail Transaksi | kasir</h4>

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



{{-- modal total --}}
<div class="modal fade " id="totalModal" tabindex="-1" role="dialog" aria-labelledby="totalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="totalModalLabel">Total Belanja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formTotal" method="POST" action="{{route('pos.kasir.save')}}">
                @csrf
                <div class="modal-body">
                    <div id="alert-total">
                        <div class="alert alert-danger alertdangertotal" role="alert">

                        </div>
                        <div class="alert alert-success alertsuccesstotal" role="alert">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_qty">Total Qty</label>
                                <input type="number" class="form-control" name="total_qty" id="total_qty" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                                    <option value="UMUM" selected>000000000 | UMUM</option>
                                    @foreach ($msanggota as $item)
                                    <option value="{{$item->Kode}}">{{$item->Kode}} | {{$item->Nama}}
                                        @if($item->NoEkop!=null) | {{$item->NoEkop}} @endif</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <label for="pembayaran_ekop" class="text-dark">Ekop</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pembayaran_ekop">Pembayaran </label>
                                        <input type="text" class="form-control" value="0" name="pembayaran_ekop"
                                            id="pembayaran_ekop">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="saldo_ekop">Saldo</label>
                                        <input type="text" class="form-control" value="0" readonly name="saldo_ekop"
                                            id="saldo_ekop">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <label for="pembayaran_kredit" class="text-dark">Kredit</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pembayaran_kredit">Pembayaran</label>
                                        <input type="text" class="form-control" @if($SaldoMinusMax->aktif ==0) readonly="true"
                                        @endif value="0" name="pembayaran_kredit"
                                        id="pembayaran_kredit">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="saldo_kredit">Saldo</label>
                                        <input type="text" class="form-control" value="0" readonly name="saldo_kredit"
                                            id="saldo_kredit">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ttl_pembayaran_tunai">Total Pembayaran Tunai</label>
                                <input type="text" class="form-control" readonly name="ttl_pembayaran_tunai" value="0"
                                    id="ttl_pembayaran_tunai">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-7">
                                        <label for="pembayaran_tunai" class="text-dark">Pembayaran Tunai</label>
                                    </div>
                                    <div class="col-md-5" id="datacktunai">
                                        <input class="form-check-input" type="checkbox" name="chk_tunai" id="chk_tunai" checked>
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="pembayaran_tunai" value="0"
                                    id="pembayaran_tunai">
                            </div>
                        </div>
                        <div class="col-md-4">
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
    $(document).ready(function(e){
        setTimeout(function(){ $('#barang').select2('open');},500)
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
    var get_kredit = 0;
    var input_kredit = 0;
     var TotalQty = 0;
    var table_detail = $("#table-detail").DataTable({
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: "{{ route('pos.kasir.datadetail') }}",
        columns: [
            {data: 'urut', name: 'urut'},
            {data: 'barang', name: 'barang'},
            {data: 'nama_barang', name: 'nama_barang'},
            {data: 'qty', name: 'qty'},
            {data: 'harga', name: 'harga'},
            {data: 'subtotal', name: 'subtotal'},
            {data: 'action', name: 'action', orderable: false, searchable: false},

        ],
        fnRowCallback:function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
            $('td:eq(4)', nRow).html(convertToRupiah(aData["harga"]));
            $('td:eq(5)', nRow).html(convertToRupiah(aData["subtotal"]));
        },
        "footerCallback": function (row, data, start, end, display) {

                for (var i = 0; i < data.length; i++) {
                    TotalQty += parseInt(data[i]['qty']);
                }
       },
       createdRow:function (row, data, dataIndex) {
        //    console.log(data)
        $(row).find('td:eq(3)').addClass('updateqty');
        $(row).find('td:eq(3)').attr('data-idbarang',data['barang']);
        $.each($('td:eq(3)', row), function (colIndex) {

                $(this).attr('contenteditable', 'true');
        });
        }
    });

    // $('.js-example-basic-single').select2();
    $('#barcode_cust').select2();

    $(document).on('blur change', '.updateqty', function(){
        var id_barang = $(this).data('idbarang');
        var qty = $(this).text()
        csrf_ajax();
        TotalQty = 0;
        swal({
            text: "Apa anda yakin mengubah qty barang ?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willUpdate) => {
            if (willUpdate) {
                $.ajax({
                        url:"{{route('pos.detail_transaksi_kasir.update')}}",
                        method:"POST",
                        async:false,
                        data:{
                            'id_barang':id_barang,
                            'qty':qty
                        },success:function(data){

                            TotalQty = data['TotalQty']
                                $('#ttl_harga_pajak').val(convertToRupiah(data['Hasil']))
                                table_detail.ajax.reload();
                        }
                    })
            }else{
                      table_detail.ajax.reload();
            }
        });

    });

    // transaksi post
    $(document).on('keyup keydown','#supplier, #diskon_persen, #diskon_rp, #pajak, #lokasi, #keterangan', async function(){
        var form = $('#formTransaksi').serialize();

        csrf_ajax();
       const result = await  $.ajax({
            url:"{{route('pos.transaksi_kasir.store')}}",
            method:"post",
            data:form
        });
      $('#ttl_harga').val(convertToRupiah(result['total_harga']));
      $('#ttl_harga_pajak').val(convertToRupiah(result['total_harga_setelah_pajak']));
    })


    var select2=  $('#barang').select2();

    $('#barang').on('change', function () {
        var kode_barang = $(this).val();
        if(kode_barang != 0){
            TotalQty = 0;
            $.ajax({
            url:"{{route('pos.kasir.databarang')}}",
            method:"GET",
            async:false,
            data:{
                'kode_barang':kode_barang
            },
            success:function(data){
                $('#nama_barang').val(data["Nama"])
                $('#stok').val(data["Saldo"]);
                harga = data["HargaJual"];
                table_detail.ajax.reload();
                TotalQty = data['TotalQty']
                setTimeout(function(){ $('#barang').select2('open');},500)
                $('[id=barang]').val('0').trigger('change');
                $('#ttl_harga_pajak').val(convertToRupiah(data['Hasil']))
                $('#harga').val(convertToRupiah(harga));
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

            var res = rupiah.split('',rupiah.length-1).reverse().join('');
            return res;
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


     function insertDetail()
     {
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
                    $('.alert-success').text('Data berhasil di tambahkan')
                $.ajax({
                    url:"{{route('pos.detail_transaksi_kasir.store')}}",
                    method: "POST",
                    data: $('#formDetail').serialize(),
                    success:function(data){
                        TotalQty = 0;
                        $('.alert-danger').hide()
                        $('[id=barang]').val('0').trigger('change');
                        $('#alert-detail').show();
                        setTimeout(function(){ $('#alert-detail').hide()},3000);
                        $('#formDetail').trigger("reset");
                        table_detail.ajax.reload();
                        setTimeout(function(){ $('#barang').select2('open');},500)
                        $('.btnSimpan').show();
                        $('#barang').val('0');
                        var ttl_harga = convertToRupiah(String(data['total_harga']));
                        var ttl_harga_pajak = convertToRupiah(String(data['total_harga_setelah_pajak']));
                        $("#ttl_harga").val(ttl_harga);
                            $("#ttl_harga_pajak").val(ttl_harga_pajak);
                    }
                })
                }
     }



     function updateDetail()
     {
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
                url:"{{route('pos.detail_transaksi_kasir.update')}}",
                method: "POST",
                data: $('#formDetail').serialize(),
                success:function(data){
                    TotalQty = 0;
                    $('#formDetail').trigger("reset");
                    table_detail.ajax.reload();
                    // setTimeout(function(){ $('#barang').select2('open');},500)
                    $('[id=barang]').val('0').trigger('change');
                    $('.btnSimpan').show();
                    $('.alert-success').text('Data berhasil di update')
                    $('.alert-danger').hide()
                    $('#alert-detail').show();
                    setTimeout(function(){ $('#alert-detail').hide()
                    $('#barangModal').modal('hide')
                    },2000);
                    var ttl_harga = convertToRupiah(String(data['total_harga']));
                       var ttl_harga_pajak = convertToRupiah(String(data['total_harga_setelah_pajak']));
                       $("#ttl_harga").val(ttl_harga);
                       $("#ttl_harga_pajak").val(ttl_harga_pajak);
                }
            })
            }
     }


     $(document).on('keypress','#qty', function(e){
        var key = e.which;
        var btn = $(".btnBarangModal").attr('class')

        if (key == 13) {
            var insert = btn.search('btnDetailInsert');
            var update = btn.search('btnDetailUpdate');
            if(insert != -1){
                 insertDetail()
            }

            if(update != -1){
                updateDetail()
            }

         }
     })

     $(document).on('click','.btnDetailInsert',function () {
        insertDetail()
      });

      $(document).on('click','.btnDetailUpdate',function () {
            updateDetail()
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
        $('[id=barcode_cust]').val('UMUM').trigger('change');
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


      function simpan() {
        var ttl_belanja = $("#ttl_harga_pajak").val();
        $("#total_belanja").val(ttl_belanja);
        var cek = "";
        var totalQty = 0;
        $.ajax({
            url:"{{route('pos.kasir.check')}}",
            async:false,
            method:"GET",
            success:function(data){
                if(data['message']=='true'){
                    cek = true;
                    totalQty = data['TotalQty']
                }else{
                    cek = false;
                }
            }
        })
        if(cek){
            $('#alert-total').hide();
            $('#datacktunai').hide()
             $('#totalModal').modal('show');
             $('#total_qty').val(totalQty)
             $('#ttl_pembayaran_tunai').val(ttl_belanja);
             $('#pembayaran_ekop').attr('readonly', true);
             $('#pembayaran_kredit').attr('readonly', true);
             setTimeout(function(){  $('#barcode_cust').select2('open');},500)
        }else{
            swal("Detail Belum Diisi!");
        }
       }

    $('.btnsimpan').on('click', function(){

        simpan()
    })


    $('.btnIconClose, .btnClose').on('click',function () {
        setTimeout(function(){
            if(TotalQty > 0){
                simpan()
            }

        },1200)

     })

     $("#barangModal").keyup(function(e) {
        if (e.key === "Escape") {
                setTimeout(function(){
                    if(TotalQty > 0){
                        simpan()
                    }

            },1800)
        }
    });

    $(document).on('click','.btnDelete', function () {
       var urut = $(this).data('urut')

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
               window.location.href="{{url('/admin/pos/kasir/delete_detail/')}}/"+urut;
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
             url:"{{route('pos.kasir.ceksaldo')}}",
             method:'GET',
             async:false,
             data:{
                 'kode':kode,
                 'ttl_belanja':ttl_belanja
             }, success:function(data){
                    var saldo = data['Saldo'];
                    var ekop = 0;
                    var pembayaran_tunai  = 0;
                    var ttl_pembayaran_tunai = 0;
                    // console.log(data)

                    if(kode != 'UMUM'){

                        $('#pembayaran_ekop').attr('readonly', false);
                        $('#pembayaran_kredit').attr('readonly', false);
                        $('#datacktunai').show()
                        $("#chk_tunai").prop('checked', false);
                        $('#pembayaran_tunai').attr('readonly', true);
                    }else{
                        $('#pembayaran_kredit').attr('readonly', true);
                        $('#pembayaran_ekop').attr('readonly', true);
                        $('#datacktunai').hide()
                        $("#chk_tunai").prop('checked', true);
                        $('#pembayaran_tunai').attr('readonly', false);
                        $('#ttl_pembayaran_tunai').val(ttl_belanja);
                    }

                    if(data['status']=='minus'){
                        // console.log(data)
                        $('#saldo_kredit').val(saldo);
                        $('#saldo_ekop').val(0);
                        if(convertToAngka(saldo) > parseInt(data['Total'])){
                            $('#pembayaran_tunai').val(0);
                            $('#ttl_pembayaran_tunai').val(0);
                            $('#pembayaran_ekop').val(0);
                            $('#pembayaran_kredit').val(data['Total'])
                            $('#pembayaran_tunai').attr('readonly', true);
                        }else{
                            $('#ttl_pembayaran_tunai').val(ttl_belanja);
                            $('#pembayaran_ekop').val(0);
                            $('#pembayaran_kredit').val(0)
                            $('#datacktunai').hide()
                            $('#pembayaran_tunai').attr('readonly', false);
                        }
                        $('#pembayaran_ekop').attr('readonly', true);

                        $('#pembayaran_kredit').attr('readonly', true);
                        get_kredit = data['Saldo']
                        get_kredit = convertToAngka(get_kredit)
                        input_kredit = data['Total']
                        $('#datacktunai').show()
                    }else if(data['status']=='plus'){
                        $('#saldo_ekop').val(convertToRupiah(data['Saldo']));
                        $('#saldo_kredit').val(0);
                        if(parseInt(saldo) > parseInt(data['Total'])){
                            $('#pembayaran_tunai').val(0);
                            $('#ttl_pembayaran_tunai').val(0);
                            $('#pembayaran_ekop').val(data['Total']);
                        }else{
                            $('#ttl_pembayaran_tunai').val(convertToRupiah(ttl_belanja));
                            $('#pembayaran_ekop').val(0);
                        }
                        $('#datacktunai').hide()
                        $('#pembayaran_tunai').attr('readonly', false);
                        $('#pembayaran_ekop').attr('readonly', false);
                        $('#pembayaran_kredit').attr('readonly', true);
                    }else if(data['status']=='nol'){
                        $('#pembayaran_ekop').attr('readonly', true);
                        $('#pembayaran_kredit').attr('readonly', true);
                        $('#pembayaran_ekop').val(0);
                        $('#pembayaran_kredit').val(0);
                        $('#datacktunai').hide()
                        $('#pembayaran_tunai').val(0);
                        $('#saldo_ekop').val(0);
                        $('#kembalian').val(0);
                        $('#saldo_kredit').val(0)
                        $('#ttl_pembayaran_tunai').val(convertToRupiah(ttl_belanja));
                        $('#pembayaran_tunai').attr('readonly', false);
                    }

             }
         })
         }

     })




     function replace_titik(cek){
         if(cek!=undefined){
            var datacek = cek.replace('.','');
            datacek = datacek.replace('.','');
            return datacek;
         }

     }

     function convertToAngka(rupiah)
    {
        return parseInt(rupiah.replace(/,.*|[^0-9]/g, ''), 10);
    }

    function alertdata(data){
        $('.alertdangertotal').text(data);
        $('.alertsuccesstotal').hide();
        $('#alert-total').show();
        return false;
    }


     $(document).on('click','#chk_tunai',function () {
         var data = $(this).is(':checked');
         var ttl_belanja=$('#total_belanja').val();
        if(data == true){
            $('#pembayaran_tunai').attr('readonly', false);
            $('#ttl_pembayaran_tunai').val(ttl_belanja);
            $('#pembayaran_kredit').val(0)
        }else{
            if(get_kredit > 0 ){
                $('#pembayaran_kredit').val(input_kredit)
                $('#ttl_pembayaran_tunai').val(0);
                $('#kembalian').val(convertToRupiah(0));
            }
            $('#pembayaran_tunai').val(0);
            $('#pembayaran_tunai').attr('readonly', true);
        }
      })



     $(document).on('keyup keypress', '#pembayaran_ekop,#pembayaran_tunai', function(){
         var ekop = $('#pembayaran_ekop').val();
         var ttl_belanja=$('#total_belanja').val();
         var tunai = $("#pembayaran_tunai").val();
        var saldo_ekop = $('#saldo_ekop').val();
        var barcode_cust = $('#barcode_cust').val();
        var bayar_kredit = $('#pembayaran_kredit').val()
        var chk_tunai = $("#chk_tunai").is(':checked');
        var kode = $('#barcode_cust').val();
        var att_kredit = $('#pembayaran_kredit').prop('readonly');
        var att_ekop = $('#pembayaran_ekop').prop('readonly');
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
            ekop = convertToAngka(ekop);
            ttl_belanja = convertToAngka(ttl_belanja);
            tunai = convertToAngka(tunai);
            saldo_ekop = convertToAngka(saldo_ekop);

            var hasil_total = 0;
            // console.log(kode)
            // console.log(tunai)
            if(chk_tunai == true){
                hasil_total = tunai - ttl_belanja;
                    if(tunai < ttl_belanja){
                                hasil_total = 0;
                    }
                    if(hasil_total >= 0){
                                $('#kembalian').val(convertToRupiah(hasil_total));
                    }
            }else{
                    if(parseInt(ekop) > parseInt(saldo_ekop)){
                        alertdata('Maaf saldo ekop tidak cukup')
                    }else{
                        if(ekop == ttl_belanja){
                            $('#kembalian').val(0);
                            hasil_total = 0;
                        }else if(ekop > 0 && ekop < ttl_belanja  ){
                            hasil_total = ttl_belanja - ekop;
                            $('#ttl_pembayaran_tunai').val(convertToRupiah(hasil_total))
                            hasil_total = tunai - hasil_total;
                        }else if((ekop == 0 || ekop ==  '') && tunai > 0){
                            hasil_total = tunai - ttl_belanja;
                        }else if(ekop == 0 ){

                            $('#ttl_pembayaran_tunai').val(convertToRupiah(ttl_belanja))
                        }
                        if(att_ekop == true && tunai < ttl_belanja){
                            hasil_total = 0;
                        }

                        if(hasil_total >= 0){
                            $('#kembalian').val(convertToRupiah(hasil_total));
                        }

                    }
                }
            if(kode ==="UMUM"){
                hasil_total = tunai - ttl_belanja;
                if(tunai < ttl_belanja){
                            hasil_total = 0;
                }
                if(hasil_total >= 0){
                            $('#kembalian').val(convertToRupiah(hasil_total));
                }
            }
            // if(parseInt(ekop) > parseInt(saldo_ekop)){
            //     $('.alertdangertotal').text('Maaf saldo ekop tidak cukup');
            //     $('.alertsuccesstotal').hide();
            //     $('#alert-total').show();
            //     return false;
            // }else{
            //     //tunai
            //     if(ekop == ttl_belanja || bayar_kredit == ttl_belanja){
            //         $('#kembalian').val(0);
            //         hasil_total = 0;
            //     }else if(ekop > 0 && ekop < ttl_belanja  ){
            //         hasil_total = ttl_belanja - ekop;
            //         $('#ttl_pembayaran_tunai').val(convertToRupiah(hasil_total))
            //         hasil_total = tunai - hasil_total;

            //     }else if((ekop == 0 || ekop ==  '') && tunai > 0){
            //         hasil_total = tunai - ttl_belanja;
            //     }else if(ekop == 0 ){

            //         $('#ttl_pembayaran_tunai').val(convertToRupiah(ttl_belanja))
            //     } if(hasil_total >= 0){
            //         $('#kembalian').val(convertToRupiah(hasil_total));
            //     }


            // }

        }


     });


     $(document).on('click','.btnTotalModal', function(){

        var ekop = $('#pembayaran_ekop').val();
         var ttl_belanja=$('#total_belanja').val();
         var tunai = $("#pembayaran_tunai").val();
        var saldo_ekop = $('#saldo_ekop').val();
        var barcode_cust = $('#barcode_cust').val();
        var bayar_kredit = $('#pembayaran_kredit').val()
        var att_kredit = $('#pembayaran_kredit').prop('readonly');
        var att_ekop = $('#pembayaran_ekop').prop('readonly');
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
            ekop = convertToAngka(ekop);
            bayar_kredit = replace_titik(bayar_kredit)
            bayar_kredit = bayar_kredit.replace(',','.')
            bayar_kredit = parseFloat(bayar_kredit)
            ttl_belanja = convertToAngka(ttl_belanja);
            tunai = convertToAngka(tunai);
            saldo_ekop = convertToAngka(saldo_ekop);
            var hasil_total = 0;
            // console.log(att_ekop)
            // return false;
            if(parseInt(ekop) > parseInt(saldo_ekop)){
                $('.alertdangertotal').text('Maaf saldo ekop tidak cukup');
                $('.alertsuccesstotal').hide();
                $('#alert-total').show();
                return false;
            }else if((parseInt(tunai) == 0 || tunai == '') && parseInt(ekop) < parseInt(ttl_belanja) && att_ekop==false){
                $('.alertdangertotal').text('Maaf pembayaran ekop kurang');
                $('.alertsuccesstotal').hide();
                $('#alert-total').show();
                return false;

            }else if((parseInt(ekop) == 0 || ekop == '') && parseInt(tunai) < parseInt(ttl_belanja) && att_ekop==false){
                $('.alertdangertotal').text('Maaf pembayaran tunai kurang');
                $('.alertsuccesstotal').hide();
                $('#alert-total').show();
                return false;
            }else if((parseInt(ekop) > 0 && parseInt(tunai) > 0 && att_ekop==false)){
                var hasil_total_ekop = parseInt(tunai) + parseInt(ekop);
                // var hasil_total_bayar_kredit = parseInt(tunai) + parseInt(bayar_kredit);
                if(hasil_total_ekop < ttl_belanja){
                    $('.alertdangertotal').text('Maaf pembayaran tunai dan ekop kurang');
                    $('.alertsuccesstotal').hide();
                    $('#alert-total').show();
                    return false;
                }else{
                    // $('#formTotal').submit();
                    return true
                }
            }else if(att_kredit == true && att_ekop == true && tunai==0 && bayar_kredit == 0){
                $('.alertdangertotal').text('Maaf pembayaran tunai kurang');
                $('.alertsuccesstotal').hide();
                $('#alert-total').show();
                return false;
            }else if(att_kredit == true && att_ekop == true  && bayar_kredit == 0 && (tunai < ttl_belanja)){
                $('.alertdangertotal').text('Maaf pembayaran tunai kurang');
                $('.alertsuccesstotal').hide();
                $('#alert-total').show();
                return false;
            }
            else{
                // console.log("mantap")
                $('#formTotal').submit();
                return true
            }

        }


     })

})
</script>
@endsection
