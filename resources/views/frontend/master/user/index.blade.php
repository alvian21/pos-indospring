@extends('frontend.master')
@section('title', 'Master | User')

@section('user', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Master | User
        </h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Master | User
                        </h4>
                        <div class="btn-group float-right" role="group">
                            <a href="{{route('master.user.detail.index')}}" class="btn btn-primary float-right"> Tambah
                                Detail</a>
                            <button type="button" class="btn btn-primary float-right addUser ml-1">Tambah User</button>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>User Login</th>
                                        <th>User Password </th>
                                        <th>Kode Anggota</th>
                                        <th>Level Approval</th>
                                        <th>Lokasi</th>
                                        <th>Boleh Backup</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user as $item)
                                    <tr>
                                        <td>{{$item->KodeUser}}</td>
                                        <td>{{$item->UserLogin}}</td>
                                        <td>{{$item->UserPassword}}</td>
                                        <td>{{$item->KodeAnggota}}</td>
                                        <td>{{$item->LevelApprovalPengajuan	}}</td>
                                        <td>{{$item->KodeLokasi}}</td>
                                        <td>@if($item->BolehBackup==0)Tidak @else Ya @endif</td>
                                        <td> <button type="button" class="btn btn-warning btnEdit"
                                                data-id="{{$item->KodeUser}}">Edit</button>
                                            <a href="{{route('master.user.show',[$item->KodeUser])}}"
                                                class="btn btn-info">Detail</a>
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
<!-- Modal -->
<div class="modal fade" id="ModalUser" tabindex="-1" role="dialog" aria-labelledby="ModalUserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formUser">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalUserLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div id="data-alert"></div>
                    <input type="hidden" name="status" id="status">
                    <div class="form-group">
                        <label for="kode">Kode</label>
                        <input type="text" class="form-control" id="kode" name="kode" value="{{$kodeuser}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="user_login">User Login</label>
                        <input type="text" class="form-control" name="user_login" id="user_login">
                    </div>
                    <div class="form-group">
                        <label for="user_password">User Password</label>
                        <input type="text" class="form-control" id="user_password" name="user_password">
                    </div>
                    <div class="form-group">
                        <label for="anggota">Anggota</label>
                        <select class="form-control" name="anggota" id="anggota">
                            <option value="">Tidak Ada</option>
                            @forelse ($msanggota as $item)
                            <option value="{{$item->Kode}}">{{$item->Kode}} | {{$item->Nama}}</option>
                            @empty

                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level_approval">Level Approval Pengajuan</label>
                        <select class="form-control" name="level_approval" id="level_approval">
                            <option value="">Tidak Ada</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <select class="form-control" id="lokasi" name="lokasi">
                            <option value="">Tidak Ada</option>
                            @forelse ($mslokasi as $item)
                            <option value="{{$item->Kode}}">{{$item->Kode}} | {{$item->Nama}}</option>
                            @empty

                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="boleh_backup">Boleh Backup</label>
                        <select class="form-control" name="boleh_backup" id="boleh_backup">

                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
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
    // function form_submit() {
    //         document.getElementById("formBarang").submit();
    //     }

        $(document).ready(function() {
            $('#table-1').DataTable()
            $('#anggota').select2()
            $('#level_approval').select2()
            $('#lokasi').select2()
            function ajax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
         }
             $(document).on('click','.addUser',function () {
                $('#formUser').trigger('reset')
                $('#ModalUser').modal('show')
                $('.btnModal').addClass('btnSimpan')
                $('.btnModal').text('Simpan')
                $('.modal-title').text('Tambah User')
                $('#data-alert').empty()
                $('#user_login').prop('readonly',false)
            })

            $(document).on('click','.btnSimpan', function () {
                var form = $('#formUser').serialize()

                ajax()
                    $.ajax({
                        url:"{{route('master.user.store')}}",
                        method:"POST",
                        data:form
                    }).done(function (response) {
                            if(response.status){
                                $('#formUser').trigger('reset')
                                $('#ModalUser').modal('hide')
                                swal("Success!", "User Berhasil Disimpan!", "success");
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
                        url:"{{route('master.user.getdata')}}",
                        method:"GET",
                        data:{
                            'id':id
                        }
                    }).done(function (response) {
                        console.log(response);
                        if(response.status){
                            var data = response.data;
                            $('#kode').val(data.KodeUser)
                            $('#user_login').prop('readonly',true)
                            $('#user_login').val(data.UserLogin)
                            $('#user_password').val(data.UserPassword)
                            $('#anggota').val(data.KodeAnggota).change()
                            $('#level_approval').val(data.LevelApprovalPengajuan).change()
                            $('#lokasi').val(data.KodeLokasi).change()
                            $('#boleh_backup').val(data.BolehBackup).change()

                            $('#ModalUser').modal('show')
                            $('.btnModal').text('Update')
                            $('.modal-title').text('Edit User')
                            $('#data-alert').empty()

                        }

                    })

            })

            $(document).on('click','.btnUpdate', function () {
                var form = $('#formUser').serialize()
                var kode = $('#kode').val()
                ajax()
                    $.ajax({
                        url:"{{url('admin/master/user/')}}/"+kode,
                        method:"PUT",
                        data:form
                    }).done(function (response) {
                            if(response.status){
                                $('#formUser').trigger('reset')
                                $('#ModalUser').modal('hide')
                                swal("Success!", "User Berhasil Diupdate!", "success");
                                $('.btnModal').removeClass('btnUpdate')
                                setTimeout(function () { location.reload(true) },1500)
                            }else{
                                $('#data-alert').html(response.data)
                            }
                    })
                })
        });

</script>
@endsection
