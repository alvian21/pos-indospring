@extends('frontend.master')

@section('title', 'Status Pesanan')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Daftar Status Pesanan Online Hari Ini</h1>

    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Daftar Status Pesanan Online Hari Ini</h4>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{date('d-M-Y')}}" readonly >
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table table-striped" id="tablepesanan">
                                <thead>
                                    <tr>
                                        <td>Nomor</td>
                                        <td>Tanggal</td>
                                        <td>Kode Anggota</td>
                                        <td>Nama Anggota</td>
                                        <td>Status Pesanan</td>
                                        <td>Aksi</td>
                                    </tr>
                                </thead>
                                <tbody>

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
<script src="https://cdn.datatables.net/datetime/1.0.3/js/dataTables.dateTime.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
    integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ=="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
    integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
    crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        var table_pesanan = $("#tablepesanan").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('status.datapesanan') }}",
        columns: [
            {data: 'nomor', name: 'nomor'},
            {data: 'tanggal', name: 'tanggal'},
            {data: 'kode_anggota', name: 'kode_anggota'},
            {data: 'nama_anggota', name: 'nama_anggota'},
            {data: 'status_pesanan', name: 'status_pesanan', orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

        $(document).on('change','#status_pesanan', function(){
            var nomor = $(this).data('nomor');
            var status = $(this).val();
                swal({
                    title: "Apa kamu yakin mengubah status pesanan?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willUpdate) => {
                if (willUpdate) {

                    $.ajax({
                        url:"{{route('status.updatestatuspsn')}}",
                        method:"GET",
                        data:{
                            'nomor':nomor,
                            'status':status
                        },success:function(data){
                            swal("Status Pesanan Berhasil di Update", {
                                icon: "success",
                            });
                            table_pesanan.ajax.reload()
                        }
                    })

                }else{
                    table_pesanan.ajax.reload()
                }

            });
        })

        $(document).on('click','.btnshow', function(){
            var nomor = $(this).data('nomor');
            window.location.href = "{{url('admin/status/pesanan/')}}/"+nomor;
        })
    })
</script>
@endsection
