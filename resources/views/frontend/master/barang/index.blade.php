@extends('frontend.master')

@section('title', 'Msbarang')

@section('barang', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Daftar Barang</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Daftar Barang</h4>
                        <button type="button" class="btn btn-primary float-right addBarang">Tambah Barang</button>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">
                            <select id="select" class="form-control input-sm selectsearch">
                                <option>Show All</option>
                                <option>With Pictures</option>
                                <option>Without Pictures</option>
                                <option>With Barcode</option>
                                <option>Without Barcode</option>

                            </select>
                            <select id="select" class="form-control input-sm selectkategori mt-2">
                                <option value="Show All Kategori" selected>Show All Kategori</option>
                                @forelse ($mskategori as $item)
                                <option value="{{$item->Nama}}">{{$item->Nama}}</option>
                                @empty

                                @endforelse

                            </select>
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Kode Barcode</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Tampil Di Mobile</th>
                                        <th>Tampil Di Caffe</th>
                                        <th>Harga Jual Toko</th>
                                        <th>Harga Jual Caffe</th>
                                        <th>Gambar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($msbarang as $item)
                                    <tr>
                                        <td>{{ $item->Kode }}</td>
                                        <td>{{ $item->KodeBarcode }}
                                            @if ($item->KodeBarcode != null)
                                            <p style="display:none">ada_barcode</p>
                                            @else
                                            <p style="display:none">no_barcode</p>
                                            @endif
                                        </td>
                                        <td>{{ $item->Nama }}</td>
                                        <td>
                                            @foreach ($mskategori as $itemdata)
                                            @if ($item->KodeKategori == $itemdata->Kode && $itemdata->Kode != null)
                                            {{ $itemdata->Nama }}

                                            @endif
                                            @endforeach
                                        </td>
                                        <td>{{($item->TampilDiMobile == "1") ? 'Ya' : 'Tidak'}}</td>
                                        <td>{{$item->TampilDiCaffe}}</td>
                                        <td>@rupiah($item->HargaJual)</td>
                                        <td>@rupiah($item->HargaCaffe)</td>
                                        <td>
                                            @if ($item->LokasiGambar != null)
                                            <img width="120"
                                                src="http://31.220.50.154/koperasi/storage/images/msbarang/{{$item->LokasiGambar}}"
                                                alt="" srcset="">
                                            <p style="display:none">ada_gambar</p>
                                            @else
                                            <p style="display:none">belum ada gambar</p>
                                            @endif
                                        </td>
                                        <td><button type="button" data-kode="{{ $item->Kode }}"
                                                class="btn btn-info btnedit">Edit</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="ModalBarang" tabindex="-1" role="dialog" aria-labelledby="ModalBarangLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="formBarang" action="{{ route('master.barang.store') }}" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalBarangLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="status" id="status">
                    <div class="form-group">
                        <label for="kode">Kode</label>
                        <input type="text" class="form-control" id="kode" name="kode">
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" name="nama" id="nama">
                    </div>
                    <div class="form-group">
                        <label for="kode_barcode">Kode barcode</label>
                        <input type="text" class="form-control" id="kode_barcode" name="kode_barcode">
                    </div>
                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select class="form-control" name="kategori" id="kategori">
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tampildimobile">Tampil Di Mobile</label>
                                <select class="form-control" name="tampildimobile" id="tampildimobile">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tampildicaffe">Tampil Di Caffe</label>
                                <select class="form-control" name="tampildicaffe[]" multiple id="tampildicaffe">
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="harga">Harga Jual Toko</label>
                                <input type="text" required class="form-control" name="harga" id="harga">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hargacaffe">Harga Jual Caffe</label>
                                <input type="text" class="form-control" required name="hargacaffe" id="hargacaffe">
                            </div>
                        </div>
                    </div>



                    {{-- <div class="form-group">
                        <label for="gambar">Gambar <span class="badge badge-info" id="info_gambar"></span></label>
                        <input type="file" class="form-control" name="gambar" id="gambar">

                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnBarang">Save changes</button>
                </div>
        </form>
    </div>
</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    // function form_submit() {
    //         document.getElementById("formBarang").submit();
    //     }

        $(document).ready(function() {
            $('#harga').mask('000.000.000.000', {
                reverse: true
            });
            $('#hargacaffe').mask('000.000.000.000', {
                reverse: true
            });
            $('#tampildicaffe').select2()
            $('#kode').attr('readonly', true);
            $('#nama').attr('readonly', true);
            var table = $("#table-1").DataTable({
                dom: "<'row'<'col-sm-9'l><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-9'i><'col-sm-3'p>>",
                "scrollX": true
            });

            $(".dataTables_filter").append(select);
            $('.dataTables_filter input').unbind().bind('keyup', function() {
                // var colIndex = document.querySelector('#select').selectedIndex;
                table.search(this.value).draw();
            });

            $('.selectkategori').on('change', function () {
                var data = $(this).find(':selected').text()
                createItemEntryKategori(data);
                if(data != 'Show All Kategori'){
                    table.columns(1).search("").draw();
                    table.columns(3).search(data).draw();
                }else{
                    table.columns(1).search("").draw();
                    table.columns(3).search("").draw();
                }
            })

            function createItem(data) {
                sessionStorage.setItem("filter", data);
            }

            function createItemEntry(data){
                sessionStorage.setItem("entry", data);
            }
            function createItemKategori(data) {
                sessionStorage.setItem("kategori", data);
            }

            function createItemEntryKategori(data){
                sessionStorage.setItem("kategori", data);
            }

            if(sessionStorage.getItem("filter")!=null ){
                var filter = sessionStorage.getItem("filter");
                    $("select option").filter(function() {
                    //may want to use $.trim in here
                    return $(this).text() == filter;
                    }).prop('selected', true);
                if (filter == "With Pictures") {
                    table.columns(1).search("").draw();
                    table.columns(6).search("ada_gambar").draw();
                } else if (filter == "Without Pictures") {
                    table.columns(1).search("").draw();
                    table.columns(6).search("belum ada gambar").draw();
                }else if (filter == "With Barcode") {
                    table.columns(6).search("").draw();
                    table.columns(1).search("ada_barcode").draw();
                }else if (filter == "Without Barcode") {
                    table.columns(6).search("").draw();
                    table.columns(1).search("no_barcode").draw();
                } else  {
                    table.columns(6).search("").draw();
                    table.columns(1).search("").draw();
                }
            }

            if(sessionStorage.getItem("entry") != null){
                var filter = sessionStorage.getItem("entry");
                $('select[name="table-1_length"]').val(filter).change();
            }
            if(sessionStorage.getItem("kategori") != null){
                var filter = sessionStorage.getItem("kategori");
                $('.selectkategori').val(filter).change();
            }

            $('select[name="table-1_length"]').on('change', function(){
                createItemEntry($(this).val());
            });

            $('.selectsearch').change(function() {
                if (this.value == "With Pictures") {
                    table.columns(1).search("").draw();
                    table.columns(6).search("ada_gambar").draw();
                    createItem("With Pictures");
                } else if (this.value == "Without Pictures") {
                    table.columns(1).search("").draw();
                    table.columns(6).search("belum ada gambar").draw();
                    createItem("Without Pictures");
                }else if (this.value == "With Barcode") {
                    table.columns(6).search("").draw();
                    table.columns(1).search("ada_barcode").draw();
                    createItem("With Barcode");
                }else if (this.value == "Without Barcode") {
                    table.columns(6).search("").draw();
                    table.columns(1).search("no_barcode").draw();
                    createItem("Without Barcode");
                } else if (this.value == "Show All") {
                    table.columns(6).search("").draw();
                    table.columns(1).search("").draw();
                    createItem("Show All");
                }

            });


            $('#ModalBarang').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                $('#kode').attr('readonly', true);
                $('#nama').attr('readonly', true);
            });

            $(document).on('click','.addBarang', function(){
                $("#status").val("store");
                $('#btnBarang').text('Save');
                $('#btnBarang').val('create');
                $('#ModalBarangLabel').text('Tambah MsBarang')
                $('#kode').attr('required', true);
                $('#nama').attr('required', true);
                $('#kode').removeAttr('readonly');
                $('#nama').removeAttr('readonly');
                $('#tampildimobile').empty();
                $('#gambar').attr('required', true);
                $('#kategori').empty();
                $('#info_gambar').text("");
                $('#tampildimobile').append('<option value="1" >Ya</option>');
                $('#tampildimobile').append('<option value="0" >Tidak</option>');
                $.ajax({
                    url:'{{route("master.barang.getkategori")}}',
                    method:'GET',
                    success:function(data){
                        var kategori = data['kategori']
                        var lokasi = data['lokasi']
                        for (let index = 0; index < kategori.length; index++) {
                                $('#kategori').append(' <option value="'+kategori[index]["Kode"]+'">'+kategori[index]["Nama"]+'</option>');
                        }

                        for (let index = 0; index < lokasi.length; index++) {
                                $('#tampildicaffe').append(' <option value="'+lokasi[index]["Kode"]+'">'+lokasi[index]["Kode"]+'</option>');
                        }
                    }
                });

                $('#ModalBarang').modal('show');
            });

            $(document).on('submit','#formBarang',function () {
                var btn = $('#btnBarang').val();
                var kode = $('#kode').val();
                var kode_barcode = $('#kode_barcode').val();
                var tampildicaffe = $('#tampildicaffe').find(':selected').val();
                if(typeof(tampildicaffe) == 'undefined'){
                        swal({
                            title: "Error",
                            text: "Maaf, tampil di caffe wajib di pilih",
                            icon: "error",
                            dangerMode: true,
                        });
                        return false;
                    }

                if(btn === "create"){
                    var cek = false;
                    var cek_barcode = false;
                    $.ajax({
                        url:"{{route('master.barang.check.kodebarang')}}",
                        method:"GET",
                        async: false,
                        data:{
                            'kode':kode
                        }, success:function(data){
                            if(data["status"]==true){
                                cek = true;
                            }
                        }
                    });

                    if(cek){
                        swal({
                            title: "Error",
                            text: "Maaf, kode barang sudah digunakan barang lain",
                            icon: "error",
                            dangerMode: true,
                        });
                        return false;
                    }

                    $.ajax({
                        url:"{{route('master.barang.check.kodebarcode')}}",
                        method:"GET",
                        async: false,
                        data:{
                            'KodeBarcode':kode_barcode
                        }, success:function(data){
                            if(data["status"]==true){
                                cek_barcode = true;

                            }
                        }
                    });



                    if(cek_barcode){
                        swal({
                            title: "Error",
                            text: "Maaf, kode barcode sudah digunakan barang lain",
                            icon: "error",
                            dangerMode: true,
                        });
                        return false;
                    }



                    // if(!cek && !cek_barcode){
                    //     return true;
                    // }
                }

                // return false;
             });
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
            $(document).on('click', '.btnedit', function() {
                $("#status").val("update");
                $('#btnBarang').val('edit');
                $('#btnBarang').text('Save Changes');
                $('#gambar').removeAttr('required');
                $('#ModalBarangLabel').text('Edit MsBarang');
                var row = $(this).closest("tr");
                var data = row.children('td').map(function() {
                    return $(this).text();
                }).get();
                var kode = $(this).data('kode');
                // $('#kode_id').val(kode);
                $('#kode').val(kode);
                var barcode = data[1].match(/\d/g);;
                if(barcode != null){
                    barcode = barcode.join("");
                    $('#kode_barcode').val(barcode);
                }

                $('#nama').val(data[2]);
                // $('#harga').val(data[5]);
                var dataselect = data[3];
                $('#kategori').empty();
                $('#tampildimobile').empty();
                $('#tampildicaffe').empty();
                // console.log(data[4]);

                if(data[6].trim() ==="ada_gambar"){
                    $('#info_gambar').text("Sudah memiliki gambar");
                }else{
                    $('#info_gambar').text("Belum memiliki gambar");
                }
                $.ajax({
                    url:'{{route("master.barang.getkategori")}}',
                    data:{
                        'kode':kode
                    },
                    method:'GET',
                    success:function(data){

                        var kategori = data['kategori']
                        var lokasi = data['lokasi']
                        var barang = data['barang']
                        // var
                       for (let index = 0; index < kategori.length; index++) {
                        if(dataselect.trim()===kategori[index]["Nama"].trim()){
                            $('#kategori').append(' <option value="'+kategori[index]["Kode"]+'" selected >'+kategori[index]["Nama"]+'</option>');
                            }else{
                            $('#kategori').append(' <option value="'+kategori[index]["Kode"]+'">'+kategori[index]["Nama"]+'</option>');
                            }
                        }
                        for (let index = 0; index < lokasi.length; index++) {
                            var cek = false;
                           for (let k = 0; k < barang.length; k++) {
                               const databarang = barang[index];
                               if(databarang===lokasi[index]["Kode"]){
                                    cek = true
                                }
                           }

                           if(cek){
                            $('#tampildicaffe').append(' <option value="'+lokasi[index]["Kode"]+'" selected >'+lokasi[index]["Kode"]+'</option>');
                           }else{
                            $('#tampildicaffe').append(' <option value="'+lokasi[index]["Kode"]+'" >'+lokasi[index]["Kode"]+'</option>');
                           }
                        }

                        $('#hargacaffe').val(convertToRupiah(data['hargacaffe']))
                        $('#harga').val(convertToRupiah(data['hargatoko']))
                    }
                });

                if(data[4].trim() == "Ya"){
                    $('#tampildimobile').append('<option value="1" selected>Ya</option>');
                    $('#tampildimobile').append('<option value="0" >Tidak</option>');
                }else{
                    $('#tampildimobile').append('<option value="1" >Ya</option>');
                    $('#tampildimobile').append('<option value="0" selected>Tidak</option>');
                }

                $('#ModalBarang').modal('show');
            })
        });

</script>
@endsection
