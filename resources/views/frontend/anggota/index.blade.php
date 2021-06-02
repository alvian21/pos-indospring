@extends('frontend.master')

@section('title', 'Msanggota')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Email</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Email</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">
                            <select id="select" class="form-control input-sm">
                                <option>Show All</option>
                                <option selected>Unverified</option>
                                <option>Verified</option>

                            </select>
                            <table class="table table-bordered display nowrap" id="table-1" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-center">Kode Anggota</th>
                                        <th rowspan="2" class="text-center">Nama</th>
                                        <th rowspan="2" class="text-center">Dept</th>
                                        <th rowspan="2" class="text-center">Email</th>
                                        <th colspan="3" class="text-center">Verification</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">User</th>
                                        <th class="text-center">Last Update</th>
                                        <th class="text-center">Verify</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($msanggota as $item)
                                    <tr>
                                        <td>{{$item->Kode}}</td>
                                        <td>{{$item->Nama}}</td>
                                        <td>{{$item->Dept}}</td>
                                        <td>{{$item->email}}</td>
                                        <td></td>
                                        <td>
                                            {{$item->verified_email_date}}</td>
                                        <td>
                                            @if($item->verified_email == 1)
                                            <p style="display:none">already_verified</p>
                                            <button type="button" class="btn btn-info">Sudah terverifikasi</button></td>
                                        @else
                                        <p style="display:none">not_verified</p>
                                        <button type="button" data-kode="{{$item->Kode}}"
                                            class="btn btn-info verify">Verifikasi</button></td>
                                        @endif

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
    $(document).ready(function() {
            var table = $("#table-1").DataTable({
                "scrollX": true,
                dom: "<'row'<'col-sm-9'l><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-9'i><'col-sm-3'p>>",
            } );

            $(".dataTables_filter").append(select);

            $('.dataTables_filter input').unbind().bind('keyup', function() {
                table.search(this.value).draw();
            });

            $('#select').ready(function() {
                table.columns(1).search("").draw();
                table.columns(6).search("not_verified").draw();
            });

            $('#select').change(function() {
                if (this.value == "Verified") {
                    table.columns(1).search("").draw();
                    table.columns(6).search("already_verified").draw();

                } else if (this.value == "Unverified") {
                    table.columns(1).search("").draw();
                    table.columns(6).search("not_verified").draw();
                }else if (this.value == "Show All") {
                    table.columns(6).search("").draw();
                    table.columns(1).search("").draw();

                }



            });

            $(document).on('click','.verify', function () {
                var kode = $(this).data('kode');
                swal({
                text: "Apa anda yakin verifikasi anggota ini ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willVerify) => {
                if (willVerify) {
                    $.ajax({
                        url:"{{route('anggotaemail.verify.user')}}",
                        method:"GET",
                        data:{
                            'kode':kode
                        },
                        success:function(data){
                            if(data['status'] == 'success'){
                                swal("Anggota berhasil di verifikasi !", {
                                     icon: "success",
                                });
                                setTimeout(function () { window.location.href="{{route('anggotaemail.index')}}" },1500)
                            }
                        }
                    })

                }
            });

             })
        });

</script>
@endsection
