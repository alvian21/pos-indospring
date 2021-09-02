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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user">User</label>
                                        <select class="form-control" id="user" name="user">
                                            <option value="-" @if($datauser['user']=='-' ) selected
                                                @endif>Pilih User</option>
                                            @forelse ($user as $item)
                                            <option value="{{$item->KodeUser}}" @if($datauser['user']==$item->KodeUser)
                                                selected @endif>{{$item->KodeUser}} | {{$item->UserLogin}}</option>
                                            @empty

                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="menu">Menu</label>
                                        <select class="form-control js-example-basic-single" name="menu" id="menu">
                                            <option value="-" @if($datauser['menu']=='-' ) selected
                                            @endif>Pilih Menu</option>
                                            @forelse ($msmenu as $item)
                                            <option value="{{$item->ItemIndex}}"  @if($datauser['menu']==$item->ItemIndex)
                                                selected @endif>{{$item->Nama}} | Level {{$item->Lvl}}</option>
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

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-bordered display nowrap" id="table-detail" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User Login</th>
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



@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){

        $('#user').select2()
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
            {data: 'UserLogin', name: 'UserLogin'},
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


         $("#user, #menu").on('change', function () {
               var form = $("#headerUser").serialize()
               var menu = $('#menu').val()
               var user = $('#user').val()

               if(user != '-' && menu != '-'){
                    ajax()
                    $.ajax({
                        url:"{{route('master.user.saveheader')}}",
                        method:"POST",
                        data:form,
                        success:function(data){
                            console.log(data);
                            table_detail.ajax.reload()
                        }
                    })
               }

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
            url:"{{route('master.user.detail.store')}}",
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
                            url:"{{route('master.user.detail.store')}}",
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
