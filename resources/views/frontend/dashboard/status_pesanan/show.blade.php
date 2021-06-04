@extends('frontend.master')

@section('title', 'Detail Status Pesanan')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail Status Pesanan Online Hari Ini</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Detail Status Pesanan Online Hari Ini</h4>
                        <a href="{{route('status.pesanan.index')}}" class="btn btn-success">Close</a>
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
                                        <td>No</td>
                                        <td>Kode</td>
                                        <td>Nama</td>
                                        <td>Jumlah</td>
                                        <td>Gambar</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($barang as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->Kode}}</td>
                                        <td>{{$item->Nama}}</td>
                                        <td>{{$item->Jumlah}}</td>
                                        <td><img src="http://31.220.50.154/koperasi/storage/images/msbarang/{{$item->LokasiGambar}}" alt="" width="120" srcset=""></td>
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
    <script src="https://cdn.datatables.net/plug-ins/1.10.21/api/sum().js"></script>
<script>
    $(document).ready(function(){
        $("#tablepesanan").append('<tfoot><th>Total</th><th></th><th></th><th></th><th></th></tfoot>');
        var table_pesanan = $("#tablepesanan").DataTable({
             "drawCallback": function(data, row) {
                        //Get data here
                        var api = this.api();
                        var total = api.column(3, {
                            page: 'current'
                        }).data().sum();

                        total = total.toFixed(2);

                        $(api.column(3).footer()).html(
                            total
                        );

                    },
        });
    })
</script>
@endsection
