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
                            <select id="select" class="form-control input-sm">
                                <option selected>Dalam Proses</option>
                                <option>Barang Sudah Siap</option>
                                <option>Barang Telah Diambil</option>

                            </select>
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
                                    @forelse ($trmutasihd as $item)
                                         <tr>
                                             <td>{{$item->Nomor}}</td>
                                             <td>{{$item->Tanggal}}</td>
                                             <td>{{$item->KodeSuppCust}}</td>
                                             <td>{{$item->Nama}}</td>
                                             <td>
                                                <select class="form-control" data-nomor="{{$item->Nomor}}" id="status_pesanan">
                                                    <option value="Dalam Proses" @if($item->StatusPesanan == "Dalam Proses") selected  @endif>Dalam Proses</option>
                                                    <option value="Barang Sudah Siap" @if($item->StatusPesanan == "Barang Sudah Siap") selected data-text="barang_sudah_siap" @endif >Barang Sudah Siap</option>
                                                    <option value="Barang Telah Diambil" @if($item->StatusPesanan == "Barang Telah Diambil") selected data-text="barang_telah_diambil" @endif>Barang Telah Diambil</option>
                                                </select>

                                                @if ($item->StatusPesanan == "Dalam Proses")
                                                        <p style="display: none">dalam_proses</p>
                                                @elseif ($item->StatusPesanan == "Barang Sudah Siap")
                                                        <p style="display: none">barang_sudah_siap</p>
                                                @elseif ($item->StatusPesanan == "Barang Telah Diambil")
                                                <p style="display: none">barang_telah_diambil</p>
                                                @endif
                                             </td>
                                             <td>
                                                <button type="button" data-nomor="{{$item->Nomor}}" class="btn btn-info btnshow">Show</button>
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
        dom: "<'row'<'col-sm-9'l><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-9'i><'col-sm-3'p>>",
    });

    $('#tablepesanan thead th').each(function () {
            var title = $(this).text();
            $(this).html(title+' <input type="text" class="col-search-input" placeholder="Search ' + title + '" />');
        });

        table_pesanan.columns().every(function () {
            var table = this;
            $('input', this.header()).on('keyup change', function () {
                if (table.search() !== this.value) {
                	   table.search(this.value).draw();
                }
            });
        });
        $(".dataTables_filter").append(select);

        $('.dataTables_filter input').unbind().bind('keyup', function() {
            table_pesanan.search(this.value).draw();
        });

        $('#select').ready(function() {
                table_pesanan.columns(1).search("").draw();
                table_pesanan.columns(4).search("dalam_proses").draw();
            });

            $('#select').change(function() {
                if (this.value == "Dalam Proses") {
                    table_pesanan.columns(1).search("").draw();
                    table_pesanan.columns(4).search("dalam_proses").draw();

                } else if (this.value == "Barang Telah Diambil") {
                    table_pesanan.columns(1).search("").draw();
                    table_pesanan.columns(4).search("barang_telah_diambil").draw();
                }else if (this.value == "Barang Sudah Siap") {
                    table_pesanan.columns(4).search("barang_sudah_siap").draw();
                    table_pesanan.columns(1).search("").draw();

                }
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
                           window.location.href="{{route('status.pesanan.index')}}"
                        }
                    })

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
