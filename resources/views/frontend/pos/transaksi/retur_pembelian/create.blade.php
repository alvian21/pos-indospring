@extends('frontend.master')

@section('title', 'Transaksi | RETUR PEMBELIAN')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi | Retur Pembelian</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Transaksi | Retur Pembelian</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form action="" method="post" id="formTransaksi">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="transaksi">Transaksi</label>
                                        <input type="text" class="form-control" id="transaksi" name="transaksi" readonly
                                            value="RETUR PEMBELIAN">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="keterangan_header">keterangan</label>
                                        <textarea class="form-control" id="keterangan_header" name="keterangan_header"
                                            rows="3">{{$trretur["keterangan"]}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="supplier">Supplier</label>
                                        <select class="form-control js-example-basic-single" id="supplier" name="supplier">
                                            <option value="0">Pilih Supplier</option>
                                            @forelse ($mssupplier as $item)
                                            <option @if($item->Kode == $trretur["kode"]) selected @endif value="{{$item->Kode}}">{{$item->Kode}} | {{$item->Nama}}</option>
                                            @empty
                                            <option value="">not found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="ttl_harga_pajak">Total Harga</label>
                                        <input type="text" class="form-control ttl_harga_pajak" id="ttl_harga_pajak"
                                            value="{{$trretur["total_harga_setelah_pajak"]}}" name="ttl_harga_pajak"
                                            readonly>
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
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Detail Transaksi |  Retur Pembelian</h4>
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
                                <div id="combobarang">
                                    <select class="form-control js-example-basic-single" name="barang" id="barang">
                                        <option value="0">Pilih Barang</option>
                                        @forelse ($msbarang as $item)
                                        <option value="{{$item->Kode}}">{{$item->Kode}} | @if($item->KodeBarcode!=null)
                                            {{$item->KodeBarcode}} | @endif {{$item->Nama}}</option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                                <div id="inputbarang">
                                    <input type="text" class="form-control" name="barang_edit" readonly id="barang_edit">
                                </div>
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
                                <input type="number" class="form-control" name="harga"  id="harga">
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
        $('#harga').mask('000.000.000.000', {
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
        ajax: "{{ route('pos.returpembelian.datadetail') }}",
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
        }
    });

    // $('.js-example-basic-single').select2();
    $('#barcode_cust').select2();
    $('#supplier').select2();
    $('#inputbarang').hide()
    // transaksi post
    $(document).on('keyup keydown change','#supplier, #pajak, #lokasi, #keterangan_header', async function(){
        var form = $('#formTransaksi').serialize();

        csrf_ajax();
       const result = await  $.ajax({
            url:"{{route('pos.transaksi_returpembelian.store')}}",
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
        $('.btnBarangModal').removeClass('btnDetailUpdate').addClass('btnDetailInsert')
    });

    $('#barang').on('change', function () {
        var kode_barang = $(this).val();
        $('#alert-detail').hide();
        var qty = $('#qty').val();
        if(kode_barang != 0){
            $.ajax({
            url:"{{route('pos.returpembelian.databarang')}}",
            method:"GET",
            data:{
                'kode_barang':kode_barang
            },
            success:function(data){
                $('#nama_barang').val(data["Nama"])
                $('#stok').val(data["Saldo"]);
                harga = data["HargaJual"];
                subtotal = parseInt(qty) * parseInt(harga);
                if(subtotal <=0){
                    subtotal = 0;
                }
                subtotal = convertToRupiah(subtotal)
                $('#subtotal').val(subtotal);
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
        return rupiah.split('',rupiah.length-1).reverse().join('');
        }

     $(document).on('keyup','#harga, #qty',function(){
         var barang = $('#barang').val();
         var modalbtn = $(".btnBarangModal").text()
         if(modalbtn != 'update'){
            if(barang != 0){
            qty = $("#qty").val();
            harga = $('#harga').val();
            harga = harga.replace('.','');
            subtotal = qty * harga;
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
         }else{
                qty = $("#qty").val();
                harga = $('#harga').val();
                harga = harga.replace('.','');
                subtotal = qty * harga;
                if(subtotal <=0){
                    subtotal = 0;
                }
                subtotal = convertToRupiah(subtotal)
                $('#subtotal').val(subtotal);
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
                        url:"{{route('pos.detail_transaksi_returpembelian.store')}}",
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
      });

      $(document).on('click','.btnDetailUpdate',function () {
        var barang = $('#barang').val();
        var stok = $('#stok').val();
        var qty = $('#qty').val();
         if(qty == undefined || qty == 0 || qty == ''){
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
                url:"{{route('pos.detail_transaksi_returpembelian.update')}}",
                method: "POST",
                data: $('#formDetail').serialize(),
                success:function(data){
                    $('#formDetail').trigger("reset");
                    table_detail.ajax.reload();
                    // setTimeout(function(){ $('#barang').select2('open');},500)

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
        });

      $('#barangModal').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                $('[id=barang]').val('0').trigger('change');
                $('.btnBarangModal').text('insert');
                $('#combobarang').show()
                $('#inputbarang').hide()
                $('#barangModalLabel').text('Input detail barang')
                $('.btnBarangModal').removeClass('btnDetailUpdate').addClass('btnDetailInsert')
            });

    $('#totalModal').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
        $('[id=barcode_cust]').val('UMUM').trigger('change');
    });

      $(document).on('click', '.btnDetailBarangEdit', function(){
        var row = $(this).closest("tr");
          var data =  $('#table-detail').DataTable().row(row).data()
          $('#id_urut').val(data['urut']);
          $('#combobarang').hide()
          $('#inputbarang').show()
          $('#barang_edit').val(data['barang']);
          $('#nama_barang').val(data['nama_barang']);
          var dataselect = "";
          $('#barang option').each(function(){
             if($(this).val()==data['barang'].trim()){
               dataselect = $(this).val();
             }
          })
          var harga = convertToRupiah(data['harga']);
          var subtotal = convertToRupiah(data['subtotal']);
          $('#harga').val(harga);
          $('.btnBarangModal').text('update');
          $('.keterangan').val(data['keterangan']);
          $('#subtotal').val(subtotal);
          $('#qty').val(data['qty']);
          $('[id=barang]').val(dataselect).trigger('change');
          $('#barangModalLabel').text('Edit detail barang')
          $('.btnBarangModal').removeClass('btnDetailInsert').addClass('btnDetailUpdate')
          $('#barangModal').modal('show')
      })


    $('.btnsimpan').on('click', function(){
        var ttl_belanja = $("#ttl_harga_pajak").val();
        var supplier = $('#supplier').val()
        if(supplier == '0'){
            swal("Supplier wajib dipilih!");
            return false;
        }
        $("#total_belanja").val(ttl_belanja);
        var cek = "";
        $.ajax({
            url:"{{route('pos.returpembelian.check')}}",
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
            swal({
                text: "Apa kamu yakin menyimpan data ini ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willInsert) => {
                if (willInsert) {
                    window.location.href = "{{route('pos.returpembelian.save')}}"
                }
            });
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
               window.location.href="{{url('/admin/pos/returpembelian/delete_detail/')}}/"+urut;
            } else {
                swal("Your imaginary file is safe!");
            }
            });
     })



     function replace_titik(cek){
         if(cek!=undefined){
            var datacek = cek.replace('.','');
            datacek = datacek.replace('.','');
            return datacek;
         }

     }



})
</script>
@endsection
