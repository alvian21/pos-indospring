@extends('frontend.master')

@section('title', 'Pembelian Baru')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi | Pembelian Baru</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Pembelian Baru</h4>
                        <a href="{{route('pos.pembelianbaru.create')}}"  class="btn btn-primary float-right addBarang"> Tambah Pembelian</a>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <table border="0" cellspacing="5" cellpadding="5" class="mb-3">
                            <tbody>
                                <tr>
                                    <td colspan="2"><b>Filter Periode :</b> </td>
                                </tr>
                                <tr class=" input-daterange">
                                    <td>Minimum date:</td>
                                    <td> <input type="text" id="min" class="form-control" data-date-format="d M yyyy" >
                                    </td>
                                    <td>Maximum date:</td>
                                    <td> <input type="text" id="max" class="form-control" data-date-format="d M yyyy">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="table-responsive">

                            <table class="table table-striped" id="trmutasihd">
                                <thead>
                                    <tr>
                                        <th>Transaksi</th>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Lokasi Tujuan</th>
                                        <th>Total Harga</th>
                                        <th>Total Harga Setelah Pajak</th>

                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($trmutasihd as $row)
                                       <tr>
                                           <td>{{$row->Transaksi}}</td>
                                           <td>{{$row->Nomor}}</td>
                                           <td>{{$row->Tanggal}}</td>
                                           <td>{{$row->KodeSuppCust}}</td>
                                           <td>{{$row->LokasiTujuan}}</td>
                                           <td>@rupiah($row->TotalHarga)</td>
                                           <td>@rupiah($row->TotalHargaSetelahPajak)</td>
                                           <td>
                                            <a href="{{route('pos.pembelianbaru.show',[$row->Nomor])}}" class="btn btn-success">Detail</a>
                                            <button type="button" data-nomor="{{$row->Nomor}}" class="btn btn-danger btnpost" @if($row->StatusPesanan == 'POST' ) disabled @endif>@if($row->StatusPesanan == 'POST' ) POSTED @else POST @endif</button>
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
<script type="text/javascript">
     $.fn.dataTable.ext.search.push(
     function( settings, data, dataIndex ) {
        var min = $('#min').val();
        var max = $('#max').val();
        var parseDate = moment(data[2]).format('YYYY/MM/DD')
        var date = new Date( parseDate );
        if (
            ( min == "" || max == "" )
                ||
                ( moment(parseDate).isSameOrAfter(min) && moment(parseDate).isSameOrBefore(max) )
        ) {
            return true;
        }
        return false;
    }
    );
    $(document).ready(function () {
        var date = new Date();
        var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        $('.input-daterange input').each(function() {
            $(this).datepicker('clearDates');

        });

        $('#min').datepicker('setDate',firstDay);
        $('#max').datepicker('setDate',lastDay);
            var table =  $('#trmutasihd').DataTable();
        table.draw();
        $('#min, #max').on('change', function () {
            table.draw();
        })

        $(".btnpost").on('click', function () {
            var nomor = $(this).data('nomor');
            swal({
                title: "Apa anda yakin ?",
                text:"setelah proses ini, data tidak dapat di EDIT",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willSave) => {
                if (willSave) {
                    $.ajax({
                            url:"{{route('pos.pembelianbaru.updatestatus')}}",
                            method:"GET",
                            data:{'nomor':nomor,'update':1},
                            success:function(data){
                                window.location.href = "{{route('pos.pembelianbaru.index')}}"
                        }
                    })
            }
            });

         })
     })
</script>
@endsection
