@extends('frontend.master')

@section('title', 'List Promo')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>List Promo</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> List Promo</h4>
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
                                    <td> <input type="text" id="min" class="form-control" data-date-format="d M yyyy">
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
                                        <th>Lokasi </th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal AKhir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($trmutasihd as $row)
                                    <tr>
                                        <td>{{$row->Transaksi}}</td>
                                        <td>{{$row->Nomor}}</td>
                                        <td>{{$row->Tanggal}}</td>
                                        <td>{{$row->LokasiAwal}}</td>
                                        <td>{{$row->TglAwal}}</td>
                                        <td>{{$row->TglAkhir}}</td>
                                        <td>
                                            <a href="{{route('pos.kasir.promo.show',[$row->Nomor])}}"
                                                class="btn btn-success">Detail</a>
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
        var parseDate1 = moment(data[4]).format('YYYY/MM/DD')
        var parseDate2 = moment(data[5]).format('YYYY/MM/DD')
        if (
            ( min == "" || max == "" )
                ||
                ( moment(parseDate1).isSameOrAfter(min) && moment(parseDate2).isSameOrBefore(max) )
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
        function ajax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

         }
        $('#min').datepicker('setDate',firstDay);
        $('#max').datepicker('setDate',lastDay);
            var table =  $('#trmutasihd').DataTable();
        table.draw();
        $('#min, #max').on('change', function () {
            table.draw();
        })

     })
</script>
@endsection


