@extends('frontend.master')

@section('title', 'Dashboard')

@section('dashboard', 'class=active')

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

                        <div id="penjualanoffline" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">

                    <div class="card-body">

                        <div id="penjualanonline" style="width:100%; height:400px;"></div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div id="statuspesanan" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card" style="height: 95%">
                    <div class="card-header">
                        <h4 class="text-center">Status Pinjaman</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart" class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card ">
                    <div class="card-header d-flex justify-content-center ">
                        <h4 class="mb-0">Minimum Stok</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="minimumstok" style="width:100%; height:400px;"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card ">
                    <div class="card-header d-flex justify-content-center ">
                        <h4 class="mb-0"><a href="{{route('anggotaemail.index')}}">Email</a></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="emailstatus" style="width:100%; height:400px;"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="emailstatuswithout" style="width:100%; height:400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card ">
                    <div class="card-header d-flex justify-content-center ">
                        <h4 class="mb-0"><a href="http://31.220.50.154/koperasi/msbarang" target="_blank">Master Barang</a></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="barangpictures" style="width:100%; height:400px;"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="barangbarcode" style="width:100%; height:400px;"></div>
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
                    setTimeout(requestDataOffline, 1000);
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
                    setTimeout(requestDataOnline, 1000);
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
                    setTimeout(requestDataPesanan, 1000);
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


    chartonline = Highcharts.chart('penjualanonline', {
                    chart: {
                        type: 'column',
                        events: {
                            load: requestDataOnline
                        }
                    },
                    title: {
                        text: "Penjualan ONLINE {{ucwords(strtolower($lokasi->Grup))}}",
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

                $('#penjualanonline .highcharts-legend').hide()


    chartpesanan = Highcharts.chart('statuspesanan', {
                    chart: {
                        type: 'column',
                        events: {
                            load: requestDataPesanan
                        }
                    },
                    title: {
                        text: "Status Pesanan ONLINE Hari Ini",
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
                            text: 'jumlah'
                        }
                    },
                    series: [{
                        data: []
                    }]
                });

    $('#statuspesanan .highcharts-legend').hide()

        $.ajax({
            url:"{{route('dashboard.emailstatus')}}",
            method:"GET",
            success:function(data){

                Highcharts.chart('emailstatus', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
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
                        name: 'Email Status',
                        colorByPoint: true,
                        innerSize: '20%',
                        data: [{
                            name: 'Unverified',
                            y:data['unverified'],
                        }, {
                            name: 'Verified',
                            y: data['verified']
                        }]
                    }]
                });
                $('#emailstatus .highcharts-title').remove()
            }
        })


        $.ajax({
            url:"{{route('dashboard.emailstatuswithout')}}",
            method:"GET",
            success:function(data){

                Highcharts.chart('emailstatuswithout', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
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
                        name: 'Email Status',
                        colorByPoint: true,
                        innerSize: '20%',
                        data: [{
                            name: 'Without',
                            y:data['without'],
                        }, {
                            name: 'Verified',
                            y: data['verified']
                        }]
                    }]
                });
                $('#emailstatuswithout .highcharts-title').remove()
            }
        })



        $.ajax({
            url:"{{route('dashboard.barangpictures')}}",
            method:"GET",
            success:function(data){

                Highcharts.chart('barangpictures', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: "Pictures"
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
                        name: 'Picture Status',
                        colorByPoint: true,
                        innerSize: '20%',
                        data: [{
                            name: 'With',
                            y:data['with'],
                        }, {
                            name: 'Without',
                            y: data['without']
                        }]
                    }]
                });
                // $('#emailstatuswithout .highcharts-title').remove()
            }
        })


        $.ajax({
            url:"{{route('dashboard.barangbarcode')}}",
            method:"GET",
            success:function(data){

                Highcharts.chart('barangbarcode', {
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
                        name: 'Barcode Status',
                        colorByPoint: true,
                        innerSize: '20%',
                        data: [{
                            name: 'With',
                            y:data['with'],
                        }, {
                            name: 'Without',
                            y: data['without']
                        }]
                    }]
                });
                // $('#emailstatuswithout .highcharts-title').remove()
            }
        })


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
  })
</script>
@endsection
