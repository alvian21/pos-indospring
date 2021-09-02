@extends('frontend.master')

@section('title', 'Master | Cicilan')

@section('koperasi', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Master | Cicilan</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Master | Cicilan</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="btnCicilan">
                                    Tambah Cicilan
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
                                        <th>Bulan</th>
                                        <th>Nominal</th>
                                        <th>Cicilan Total</th>
                                        <th>Cicilan Pokok</th>
                                        <th>Cicilan Bunga</th>
                                        <th>User Update</th>
                                        <th>Last Update</th>
                                        <th>Aksi</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($cicilan as $item)
                                    <tr>
                                        <td>{{$item->Bulan}}</td>
                                        <td>@rupiah($item->Nominal)</td>
                                        <td>@rupiah($item->CicilanTotal)</td>
                                        <td>@rupiah($item->CicilanPokok)</td>
                                        <td>@rupiah($item->CicilanBunga)</td>
                                        <td>{{$item->UserUpdate}}</td>
                                        <td>{{$item->LastUpdate}}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btnEdit"
                                                data-id="{{$item->id}}">Edit</button>
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

<div class="modal fade" id="modalCicilan" tabindex="-1" aria-labelledby="modalCicilanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCicilanLabel">Tambah Cicilan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCicilan">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div id="data-alert"></div>
                    <div class="form-group">
                        <label for="bulan">Bulan</label>
                        <select class="form-control" id="bulan" name="bulan">
                            <option value="">Pilih Bulan</option>
                            @for ($i = 1; $i <= 12; $i++) <option value="{{$i}}">{{$i}}</option>
                                @endfor
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="nominal">Nominal</label>
                        <input type="number" class="form-control" id="nominal" name="nominal" min="0">

                    </div>
                    <div class="form-group">
                        <label for="cicilan_total">Cicilan Total</label>
                        <input type="number" class="form-control" id="cicilan_total" name="cicilan_total" min="0">

                    </div>
                    <div class="form-group">
                        <label for="cicilan_pokok">Cicilan Pokok</label>
                        <input type="number" class="form-control" id="cicilan_pokok" name="cicilan_pokok" min="0">

                    </div>
                    <div class="form-group">
                        <label for="cicilan_bunga">Cicilan Bunga</label>
                        <input type="number" class="form-control" id="cicilan_bunga" name="cicilan_bunga" min="0">

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
    $(document).on('click','#btnCicilan',function () {
        $('#formCicilan').trigger('reset')
        $('#modalCicilan').modal('show')
        $('.btnModal').addClass('btnSimpan')
        $('.btnModal').text('Simpan')
        $('.modal-title').text('Tambah Cicilan')
        $('#kode').prop('readonly',false)
        $('#data-alert').empty()
     })

     $(document).on('click','.btnSimpan', function () {
         var form = $('#formCicilan').serialize()
         console.log(form);
         ajax()
            $.ajax({
                url:"{{route('koperasi.cicilan.store')}}",
                method:"POST",
                data:form
            }).done(function (response) {
                    if(response.status){
                        $('#formCicilan').trigger('reset')
                        $('#modalCicilan').modal('hide')
                        swal("Success!", "Cicilan Berhasil Disimpan!", "success");
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
          var id = $(this).data('id')
            $.ajax({
                url:"{{route('koperasi.cicilan.getdata')}}",
                method:"GET",
                data:{
                    'id':id
                }
            }).done(function (response) {

                if(response.status){
                    var data = response.data;
                    $('#id').val(data.id)
                    $('#bulan').val(data.Bulan).change()
                    $('#nominal').val(data.Nominal).change()
                    $('#cicilan_total').val(data.CicilanTotal)
                    $('#cicilan_pokok').val(data.CicilanPokok)
                    $('#cicilan_bunga').val(data.CicilanBunga)
                    $('#modalCicilan').modal('show')
                    $('.btnModal').text('Update')
                    $('.modal-title').text('Edit Cicilan')
                    $('#data-alert').empty()

                }

             })

       })

       $(document).on('click','.btnUpdate', function () {
        var form = $('#formCicilan').serialize()
        var id = $('#id').val()
         ajax()
            $.ajax({
                url:"{{url('admin/koperasi/cicilan/')}}/"+id,
                method:"PUT",
                data:form
            }).done(function (response) {
                    if(response.status){
                        $('#formCicilan').trigger('reset')
                        $('#modalCicilan').modal('hide')
                        swal("Success!", "Cicilan Berhasil Diupdate!", "success");
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
