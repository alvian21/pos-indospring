@extends('frontend.master')

@section('title', 'Dashboard')

@section('dashboard', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        {{-- <h1>Selamat Datang, {{ Auth::guard('web')->user()->UserLogin }}!</h1> --}}
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card">

                    <div class="card-body">

                        <div id="penjualanoffline" style="width:100%; height:400px !important;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card ">
                    <div class="card-header d-flex justify-content-center ">
                        <h4 class="mb-0"><a href="{{route('poslaporan.minimumstok.index')}}">Minimum Stok</a></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="minimumstok" style="width:100%; height:329px !important;"></div>
                            </div>

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
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    var chartoffline;
    var chartonline;
    var chartpesanan;
    var chartemailstatus;
    var chartemailstatuswithout;
    function requestDataOffline() {
            $.ajax({
                url: "{{route('dashboard.penjualanoffline')}}",
                method:"GET",
                dataType: 'json',
                success: function(data){
                    var total = [];
                    var day = [];
                    data.forEach(element => {
                        total.push(element['total'])
                        day.push(element['day'])
                    });
                    chartoffline.series[0].setData(total, false);
                    chartoffline.xAxis[0].setCategories(day);
                    setTimeout(requestDataOffline, 30000);
                },
                cache: false
            })
    };


    function requestDataOnline() {
            $.ajax({
                url: "{{route('dashboard.penjualanonline')}}",
                method:"GET",
                dataType: 'json',
                success: function(data){
                    var total = [];
                    var day = [];
                    data.forEach(element => {
                        total.push(element['total'])
                        day.push(element['day'])
                    });
                    chartonline.series[0].setData(total, false);
                    chartonline.xAxis[0].setCategories(day);
                    setTimeout(requestDataOnline, 30000);
                },
                cache: false
            })
    };


    function requestDataPesanan() {
            $.ajax({
                url: "{{route('dashboard.statuspesanan')}}",
                method:"GET",
                dataType: 'json',
                success: function(data){
                    var status = [];
                    var total = [];
                    data.forEach(element => {
                        status.push(element['status'])
                        total.push(element['total'])
                    });
                    chartpesanan.series[0].setData(total, false);
                    chartpesanan.xAxis[0].setCategories(status);
                    setTimeout(requestDataPesanan, 30000);
                },
                cache: false
            })
    };


    function requestEmailStatus() {
            $.ajax({
                url: "{{route('dashboard.emailstatus')}}",
                method:"GET",
                dataType: 'json',
                success: function(data){
                    chartemailstatus.series[0].setData([{
                            name: 'Unverified',
                            y:data['unverified'],
                        }, {
                            name: 'Verified',
                            y: data['verified']
                        }]);

                    setTimeout(requestEmailStatus, 30000);
                },
                cache: false
            })
    };


    function requestEmailStatusWithout() {
            $.ajax({
                url: "{{route('dashboard.emailstatuswithout')}}",
                method:"GET",
                dataType: 'json',
                success: function(data){
                    chartemailstatuswithout.series[0].setData([{
                            name: 'Without',
                            y:data['without'],
                        }, {
                            name: 'Verified',
                            y: data['verified']
                        }]);

                    setTimeout(requestEmailStatusWithout, 30000);
                },
                cache: false
            })
    };


    $(document).ready(function(){


        chartoffline = Highcharts.chart('penjualanoffline', {
                    chart: {
                        type: 'column',
                        events: {
                            load: requestDataOffline
                        }
                    },
                    title: {
                        text: "Penjualan {{ucwords(strtolower($lokasi->Nama))}}",
                        style:{
                            fontSize: '16px'
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    xAxis: {
                        categories: []
                    },
                    yAxis: {
                        allowDecimals: false,
                        title: {
                            text: 'Total Harga'
                        }
                    },
                    series: [{
                        data: []
                    }]
                });

                $('#penjualanoffline .highcharts-legend').hide()




        $.ajax({
            url:"{{route('dashboard.minimumstok')}}",
            method:"GET",
            success:function(data){

                Highcharts.chart('minimumstok', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: "Barcode"
                    },
                    credits: {
                        enabled: false
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            size: 200,
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            },
                            shadow: false
                        }
                    },
                    series: [{
                        name: 'Stok',
                        colorByPoint: true,
                        innerSize: '20%',
                        data: [{
                            name: 'Stok Aman',
                            y:data['available'],
                        }, {
                            name: 'Minimum Stok',
                            y: data['minimum']
                        }]
                    }]
                });
                $('#minimumstok .highcharts-title').remove()
            }
        })
        $('#statuspesanan .highcharts-title').css('cursor','pointer');
        $('#statuspesanan .highcharts-title').on('click', function(){
            window.location.href="{{route('status.pesanan.index')}}"
        })
  })
</script>
@endsection
