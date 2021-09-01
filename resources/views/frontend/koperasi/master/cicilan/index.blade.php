@extends('frontend.master')

@section('title', 'Master | Transaksi')

@section('koperasi', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Master | Transaksi</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Master | Transaksi</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="btnTransaksi">
                                    Tambah Transaksi
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
                                        <th>Cara Bayar</th>
                                        <th>Aktif</th>
                                        <th>User Update</th>
                                        <th>Last Update</th>
                                        <th>Aksi</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    {{-- @forelse ($transaksi as $item)
                                    <tr>
                                        <td>{{$item->Kode}}</td>
                                        <td>{{$item->Nama}}</td>
                                        <td>{{$item->CaraBayar}}</td>
                                        <td>@if($item->Aktif == 1)Ya @else Tidak @endif
                                        </td>
                                        <td>{{$item->UserUpdate}}</td>
                                        <td>{{$item->LastUpdate}}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btnEdit" data-kode="{{$item->Kode}}">Edit</button>
                                        </td>
                                    </tr>
                                    @empty

                                    @endforelse --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalTransaksi" tabindex="-1" aria-labelledby="modalTransaksiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTransaksiLabel">Tambah Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formTransaksi">
                <div class="modal-body">
                    <div id="data-alert"></div>
                    <div class="form-group">
                        <label for="kode">Kode</label>
                        <input type="text" class="form-control" id="kode" name="kode">

                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama">

                    </div>
                    <div class="form-group">
                        <label for="cara_bayar">Cara Bayar</label>
                        <select class="form-control" id="cara_bayar" name="cara_bayar">
                            <option value="">Pilih Cara Bayar</option>
                            <option value="Sekali Bayar">Sekali Bayar</option>
                            <option value="Tiap Bulan">Tiap Bulan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="aktif">Aktif</label>
                        <select class="form-control" id="aktif" name="aktif">
                            <option value="">Pilih Aktif</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
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

    function ajax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
         }
    $(document).on('click','#btnTransaksi',function () {
        $('#formTransaksi').trigger('reset')
        $('#modalTransaksi').modal('show')
        $('.btnModal').addClass('btnSimpan')
        $('.btnModal').text('Simpan')
        $('.modal-title').text('Tambah Transaksi')
        $('#kode').prop('readonly',false)
        $('#data-alert').empty()
     })

     $(document).on('click','.btnSimpan', function () {
         var form = $('#formTransaksi').serialize()
         ajax()
            $.ajax({
                url:"{{route('koperasi.transaksi.store')}}",
                method:"POST",
                data:form
            }).done(function (response) {
                    if(response.status){
                        $('#formTransaksi').trigger('reset')
                        $('#modalTransaksi').modal('hide')
                        swal("Success!", "Transaksi Berhasil Disimpan!", "success");
                        $('.btnModal').removeClass('btnSimpan')
                        setTimeout(function () { location.reload(true) },1500)
                    }else{
                        $('#data-alert').html(response.data)
                    }
             })
      })


      $(document).on('click','.btnEdit', function () {
        $('.btnModal').addClass('btnUpdate')
          var $tr = $(this).parents('tr')
          var data = $tr.children('td').map(function(){
            return $(this).text();
            }).get();
            console.log(data);
          $('#kode').val(data[0])
          $('#kode').prop('readonly',true)
          $('#nama').val(data[1])
          $('#cara_bayar').val(data[2]).change()
          $('#aktif').val(data[3].trim()).change()
          $('#modalTransaksi').modal('show')
          $('.btnModal').text('Update')
          $('.modal-title').text('Edit Transaksi')
          $('#data-alert').empty()
       })

       $(document).on('click','.btnUpdate', function () {
        var form = $('#formTransaksi').serialize()
        var kode = $('#kode').val()
         ajax()
            $.ajax({
                url:"{{url('admin/koperasi/transaksi/')}}/"+kode,
                method:"PUT",
                data:form
            }).done(function (response) {
                    if(response.status){
                        $('#formTransaksi').trigger('reset')
                        $('#modalTransaksi').modal('hide')
                        swal("Success!", "Transaksi Berhasil Diupdate!", "success");
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
