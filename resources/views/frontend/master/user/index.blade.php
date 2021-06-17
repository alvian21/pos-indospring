@extends('frontend.master')

@section('title', 'Master | User')

@section('user', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Master | User</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Master | User</h4>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form action="" id="headerUser">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="KodeUser">Kode User</label>
                                        <input type="text" class="form-control" id="KodeUser" name="KodeUser"
                                            value="{{$datauser['KodeUser']}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="UserLogin">User Login</label>
                                        <input type="text" class="form-control" id="UserLogin" name="UserLogin"
                                            value="{{$datauser['UserLogin']}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="UserPassword">User Password</label>
                                        <input type="text" class="form-control" id="UserPassword" name="UserPassword"
                                            value="{{$datauser['UserPassword']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="anggota">Anggota</label>
                                        <select class="form-control" id="anggota" name="anggota">
                                            <option value="Tidak Ada" @if($datauser['anggota']=='Tidak Ada' ) selected
                                                @endif>Tidak Ada</option>
                                            @forelse ($msanggota as $item)
                                            <option value="{{$item->Kode}}" @if($datauser['anggota']==$item->Kode)
                                                selected @endif>{{$item->Kode}} | {{$item->Nama}}</option>
                                            @empty

                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="level">Level Approval Pengajuan</label>
                                        <select class="form-control" id="level" name="level">
                                            <option value="Tidak Ada" @if($datauser['level']=='Tidak Ada' ) selected
                                                @endif>Tidak Ada</option>
                                            <option value="1" @if($datauser['level']=='1' ) selected @endif>1</option>
                                            <option value="2" @if($datauser['level']=='2' ) selected @endif>2</option>
                                            <option value="3" @if($datauser['level']=='3' ) selected @endif>3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lokasi">Lokasi</label>
                                        <select class="form-control" id="lokasi" name="lokasi">
                                            <option value="Tidak Ada" @if($datauser['lokasi']=='Tidak Ada' ) selected
                                                @endif>Tidak Ada</option>
                                            @forelse ($mslokasi as $item)
                                            <option value="{{$item->Kode}}" @if($datauser['lokasi']==$item->Kode)
                                                selected @endif>{{$item->Kode}} | {{$item->Nama}}</option>
                                            @empty

                                            @endforelse
                                        </select>
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
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Detail Master | User</h4>
                        <button type="button" class="btn btn-primary float-right btnDetailUser mr-2">Input
                            Detail User</button>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-bordered display nowrap" id="table-detail" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Item Index</th>
                                        <th>Level</th>
                                        <th>Group</th>
                                        <th>Nama</th>

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

{{-- modal user --}}
<div class="modal fade bd-example" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Input Detail User</h5>
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="menu">Menu</label>
                                <select class="form-control js-example-basic-single" name="menu" id="menu">
                                    <option value="-">Pilih Menu</option>
                                    @forelse ($msmenu as $item)
                                    <option value="{{$item->ItemIndex}}">{{$item->Nama}} | Level {{$item->Lvl}}</option>
                                    @empty

                                    @endforelse


                                </select>
                            </div>

                        </div>


                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btnDetailModal">Insert</button>
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

        $('#anggota').select2()
        $('#lokasi').select2()
        $('#level').select2()
        $('#menu').select2()
        $('#alert-detail').hide();
        function ajax(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        var table_detail = $("#table-detail").DataTable({
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: "{{ route('master.user.datadetail') }}",
        columns: [
            {data: 'no', name: 'no'},
            {data: 'item', name: 'item'},
            {data: 'level', name: 'level'},
            {data: 'grup', name: 'grup'},
            {data: 'nama', name: 'nama'},
            {data: 'action', name: 'action', orderable: false, searchable: false},

        ],

    });

    $('#detailModal').on('hidden.bs.modal', function() {
                // $(this).find('form').trigger('reset');
                $('[id=menu]').val('-').trigger('change');
                $('.btnDetailModal').text('insert');
                $('#detailModalLabel').text('Input detail user')
                $('.btnDetailModal').removeClass('btnDetailUpdate').addClass('btnDetailInsert')
            });

        $(document).on('click','.btnDetailUser', function () {
            var inputHeader = $('#headerUser').find('input');
            var cek = false;
            var name = '';
            inputHeader.each(function () {

                if($(this).val() == '' || $(this).val() ==undefined){
                    cek = true;
                    name = $(this).attr('name');
                }
             })

             if(cek){
                swal('form '+name+' harus diisi');
                return false;
             }

             var userlogin = $('#UserLogin').val();

             $.ajax({
                 url:"{{route('master.user.checkuser')}}",
                 method:"get",
                 data:{
                     'UserLogin':userlogin
                 },success:function(data){
                     if(data['message']=='true'){
                        swal('User Login sudah digunakan user lain');
                     }else{

                        $("#detailModal").modal('show')
                    setTimeout(function(){ $('#menu').select2('open');},500)
                    $('.btnDetailModal').removeClass('btnDetailUpdate').addClass('btnDetailInsert')
                     }
                 }
             })


         })

         $("#UserLogin, #UserPassword, #anggota, #level, #lokasi").on('change keyup', function () {
               var form = $("#headerUser").serialize()
               ajax()
               $.ajax({
                   url:"{{route('master.user.saveheader')}}",
                   method:"POST",
                   data:form,
                   success:function(data){
                       console.log(data);
                   }
               })
        })

        $(document).on('click','.btnDetailInsert' ,function () {
                var menu = $("#menu").val();
                if(menu == '-'){
                    $('.alert-danger').text('Menu harus dipilih')
                        $('.alert-success').hide()
                        $('#alert-detail').show();
                        setTimeout(function(){ $('#alert-detail').hide()
                    },2000);
                     return false;
                }
                ajax()
                $.ajax({
                    url:"{{route('master.user.savedetail')}}",
                    method:"POST",
                    data:{'item_index':menu},
                    success:function(data){
                        table_detail.ajax.reload()
                        $('.alert-success').text('Data berhasil di tambahkan')
                        $('.alert-danger').hide()
                        $('#alert-detail').show();
                        setTimeout(function(){ $('#alert-detail').hide()
                    },2000);
                    setTimeout(function(){ $('#menu').select2('open');},500)
                    }
                })
         })

        $(document).on('click','.btnDetailEdit', function () {
            var row = $(this).closest("tr");
            var data =  $('#table-detail').DataTable().row(row).data()
            $('#menu').val(data['item']).trigger('change');
            $('.btnDetailModal').removeClass('btnDetailInsert').addClass('btnDetailUpdate')
            $('#detailModal').modal('show')
            $('.btnDetailModal').text('update');
            $('#detailModalLabel').text('Edit detail user')

         })

         $(document).on('click','.btnDelete', function () {
       var menu = $(this).data('item')

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
               window.location.href="{{url('admin/master/user/delete_detail/')}}/"+menu;
            }
            });
     })


     $('.btnsimpan').on('click', function(){
         ajax()
         var cek = true;
        $.ajax({
            url:"{{route('master.user.store')}}",
            method:"POST",
            async:false,
            data:{
                'status':'check'
            },
            success:function(data){
                if(data['message']=='false'){
                    cek = false;
                }
            }
        })

        if(!cek){
            swal("Detail Belum Diisi!");
            return false;
        }

        swal({
            title: "Apa anda yakin menyimpan data user ini?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willSave) => {
            if (willSave) {
                    $.ajax({
                            url:"{{route('master.user.store')}}",
                            method:"POST",
                            async:false,
                            data:{
                                'status':'save'
                            },
                            success:function(data){
                                if(data['message']=='true'){
                                    window.location.href = "{{route('master.user.index')}}"
                                }
                            }
                        })


                }
            });

        })

})
</script>
@endsection
