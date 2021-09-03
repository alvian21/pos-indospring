@extends('frontend.master')

@section('title', 'Master | Lokasi')

@section('posmaster', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Master | Lokasi</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Master | Lokasi</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="btnLokasi">
                                    Tambah Lokasi
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
                                        <th>Grup</th>
                                        <th>Status</th>
                                        <th>User Update</th>
                                        <th>Last Update</th>
                                        <th>Aksi</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($lokasi as $item)
                                    <tr>
                                        <td>{{$item->Kode}}</td>
                                        <td>{{$item->Nama}}</td>
                                        <td>{{$item->Grup}}</td>
                                        <td>{{$item->Status}}</td>
                                        <td>{{$item->UserUpdate}}</td>
                                        <td>{{$item->LastUpdate}}</td>
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

<div class="modal fade" id="modalLokasi" tabindex="-1" aria-labelledby="modalLokasiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLokasiLabel">Tambah Lokasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formLokasi">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div id="data-alert"></div>

                    <div class="form-group">
                        <label for="kode">Kode</label>
                        <input type="text" class="form-control" id="kode" name="kode" min="0">

                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" min="0">

                    </div>
                    <div class="form-group">
                        <label for="grup">Grup</label>
                        <input type="text" class="form-control" id="grup" name="grup" min="0">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="Toko">Toko</option>
                            <option value="Caffe">Caffe</option>
                        </select>
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
    $(document).on('click','#btnLokasi',function () {
        $('#formLokasi').trigger('reset')
        $('#modalLokasi').modal('show')
        $('.btnModal').addClass('btnSimpan')
        $('.btnModal').text('Simpan')
        $('.modal-title').text('Tambah Lokasi')
        $('#kode').prop('readonly',false)
        $('#data-alert').empty()
     })

     $(document).on('click','.btnSimpan', function () {
         var form = $('#formLokasi').serialize()
         ajax()
            $.ajax({
                url:"{{route('pos.master.lokasi.store')}}",
                method:"POST",
                data:form
            }).done(function (response) {
                console.log(response);
                    if(response.status){
                        $('#formLokasi').trigger('reset')
                        $('#modalLokasi').modal('hide')
                        swal("Success!", "Lokasi Berhasil Disimpan!", "success");
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
        $('#kode').prop('readonly',true)
        $('#nama').val(data[1])
        $('#grup').val(data[2])
        $('#status').val(data[3])
        $('#modalLokasi').modal('show')
        $('.btnModal').text('Update')
        $('.modal-title').text('Edit Lokasi')
        $('#data-alert').empty()
       })

       $(document).on('click','.btnUpdate', function () {
        var form = $('#formLokasi').serialize()
        var id = $('#kode').val()
         ajax()
            $.ajax({
                url:"{{url('admin/pos/master/lokasi/')}}/"+id,
                method:"PUT",
                data:form
            }).done(function (response) {
                    if(response.status){
                        $('#formLokasi').trigger('reset')
                        $('#modalLokasi').modal('hide')
                        swal("Success!", "Lokasi Berhasil Diupdate!", "success");
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
