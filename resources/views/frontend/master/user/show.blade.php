@extends('frontend.master')
@section('title', 'Master | User')

@section('user', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail | User
        </h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Detail | User
                        </h4>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>User Update </th>
                                        <th>Last Update</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($detail as $item)
                                    <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->Nama}}</td>
                                            <td>{{$item->UserUpdate}}</td>
                                            <td>{{$item->LastUpdate}}</td>
                                            <td><button type="button" data-kode="{{$item->KodeUser}}" data-item="{{$item->ItemIndex}}" class="btn btn-danger btnHapus">Delete</button></td>
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
            $(document).on('click','.btnHapus', function () {
                var kode = $(this).data('kode')
                var item = $(this).data('item')

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                    if (willDelete) {
                        ajax()
                        $.ajax({
                            url:"{{route('master.user.deleteitem')}}",
                            method:"GET",
                            data:{
                                'item':item,
                                'kode':kode
                            }
                        }).done(function (response) {
                            if(response.status){
                                swal("Success! Detail has been deleted!", {
                                icon: "success",
                                });

                                setTimeout(function () { location.reload(true) },1500)
                            }
                         })
                    }
                    });
             })
        });

</script>
@endsection
