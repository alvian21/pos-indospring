@extends('frontend.master')

@section('title', 'Laporan Trcetak')

@section('poslaporan', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Daftar Cetak Label Harga</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Daftar Cetak Label Harga</h4>
                        <button type="button" class="btn btn-primary addcetak" data-toggle="modal"
                            data-target="#modalcetak">Tambah Trcetak</button>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Kode Barcode</th>
                                        <th>Nama</th>
                                        <th>Harga Jual</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>
                        </div>
                        <div class="float-right mt-3">
                            <a href="{{route('poslaporan.cetak.label')}}" target="blank" class="btn btn-primary btncetak">Cetak</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<div class="modal fade" id="modalcetak" tabindex="-1" aria-labelledby="modalcetakLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100" id="modalcetakLabel">Tambah Cetak Label</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formLabel">
                    <div id="alert-data">

                    </div>
                    <div class="form-group">
                        <label for="barang">Barang</label>
                        <select class="form-control" id="barang" name="barang">
                            <option value="0" selected>Pilih Barang</option>
                            @forelse ($msbarang as $item)
                            <option value="{{$item->Kode}}">{{$item->Kode}} | @if($item->KodeBarcode!=null) {{$item->KodeBarcode}} | @endif {{$item->Nama}}</option>

                            @empty

                            @endforelse
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btnSimpan">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function () {
    $('#barang').select2()
    var table = $("#table-1").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('poslaporan.trcetak.index') }}",
        columns: [
            {data: 'KodeBarang', name: 'KodeBarang'},
            {data: 'KodeBarcode', name: 'KodeBarcode'},
            {data: 'Nama', name: 'Nama'},
            {data: 'HargaJual', name: 'HargaJual'},
            {data: 'action', name: 'action', orderable: false, searchable: false},

        ],
    });

    function ajax(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    $('.addcetak').on('click', function(){
        $('#alert-data').empty()
        $('#barang').val(0).change()
    })

    $('#barang').on('change', function(){
        $('#alert-data').empty()
    })

    $(document).on('click','.hapusLabel', function () {
        var kode = $(this).data('kode');
        console.log(kode);
        swal({
            title: "Apa kamu yakin?",
            text: "ketika dihapus, data tidak bisa dikembalikan lagi!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                ajax()
                $.ajax({
                    url:"{{url('admin/pos/laporan/trcetak/')}}/"+kode,
                    method:"DELETE"
                }).done(function(response){
                    console.log(response);
                        if(response.status){
                            swal("Data berhasil dihapus!", {
                                icon: "success",
                                });
                            setTimeout(function(){location.reload(true)},1000)
                        }
                }).fail(function(response){
                        console.log(response);
                })

            }
            });
     })

    $('.btnSimpan').on('click', function () {
            var form = $('#formLabel').serialize()
            ajax()
            $.ajax({
                url:"{{route('poslaporan.trcetak.store')}}",
                method:"POST",
                data:form
            }).done(function (response) {
                    if(response.status){
                        $('#alert-data').html(response.data)
                        setTimeout(function(){$('#alert-data').empty()},1500)
                    }else{
                        $('#alert-data').html(response.data)
                    }
            }).fail(function (response) {
                console.log(response);
            })
     })

 })
</script>
@endsection
