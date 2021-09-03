@extends('frontend.master')

@section('title', 'Master | Kategori')

@section('posmaster', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Master | Kategori</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Master | Kategori</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="btnKategori">
                                    Tambah Kategori
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-bordered display nowrap" id="table-anggota" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($kategori as $item)
                                    <tr>
                                        <td>{{$item->Kode}}</td>
                                        <td>{{$item->Nama}}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btnEdit"
                                                data-id="{{$item->Kode}}">Edit</button>
                                        </td>
                                    </tr>
                                    @empty

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalKategori" tabindex="-1" aria-labelledby="modalKategoriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKategoriLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formKategori">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div id="data-alert"></div>

                    <div class="form-group">
                        <label for="kode">Kode</label>
                        <input type="text" class="form-control" id="kode" name="kode" readonly value="{{$kode}}">

                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btnModal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){

    var table = $("#table-anggota").DataTable({
        "scrollX": true,
    });

    $('#bulan').select2()
    function ajax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
         }
    $(document).on('click','#btnKategori',function () {
        $('#formKategori').trigger('reset')
        $('#modalKategori').modal('show')
        $('.btnModal').addClass('btnSimpan')
        $('.btnModal').text('Simpan')
        $('.modal-title').text('Tambah Kategori')
        $('#data-alert').empty()
     })

     $(document).on('click','.btnSimpan', function () {
         var form = $('#formKategori').serialize()
         ajax()
            $.ajax({
                url:"{{route('pos.master.kategori.store')}}",
                method:"POST",
                data:form
            }).done(function (response) {
                console.log(response);
                    if(response.status){
                        $('#formKategori').trigger('reset')
                        $('#modalKategori').modal('hide')
                        swal("Success!", "Kategori Berhasil Disimpan!", "success");
                        $('.btnModal').removeClass('btnSimpan')
                        setTimeout(function () { location.reload(true) },1500)
                    }else{
                        $('#data-alert').html(response.data)
                    }
             })
      })


      $(document).on('click','.btnEdit', function () {
        $('.btnModal').addClass('btnUpdate')
        $('.btnModal').removeClass('btnSimpan')
        var $tr = $(this).parents('tr')
        var data = $tr.children('td').map(function(){
            return $(this).text();
        }).get();
        console.log(data);
        $('#kode').val(data[0])
        $('#nama').val(data[1])
        $('#modalKategori').modal('show')
        $('.btnModal').text('Update')
        $('.modal-title').text('Edit Kategori')
        $('#data-alert').empty()
       })

       $(document).on('click','.btnUpdate', function () {
        var form = $('#formKategori').serialize()
        var id = $('#kode').val()
         ajax()
            $.ajax({
                url:"{{url('admin/pos/master/kategori/')}}/"+id,
                method:"PUT",
                data:form
            }).done(function (response) {
                    if(response.status){
                        $('#formKategori').trigger('reset')
                        $('#modalKategori').modal('hide')
                        swal("Success!", "Kategori Berhasil Diupdate!", "success");
                        $('.btnModal').removeClass('btnUpdate')
                        setTimeout(function () { location.reload(true) },1500)
                    }else{
                        $('#data-alert').html(response.data)
                    }
             })
        })
})
</script>
@endsection
