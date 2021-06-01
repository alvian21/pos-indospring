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
                        <h4 class="mb-0">Email</h4>
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
                        <h4 class="mb-0">Master Barang</h4>
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
    $(document).ready(function(){

        $.ajax({
            url:"{{route('dashboard.penjualanoffline')}}",
            method:"GET",
            success:function(data){
                var total = [];
                var day = [];
                data.forEach(element => {
                    total.push(element['total'])
                    day.push(element['day'])
                });
                const chart = Highcharts.chart('penjualanoffline', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: "Penjualan {{ucwords(strtolower($lokasi->Nama))}}",
                        fontSize: '12px'
                    },
                    credits: {
                        enabled: false
                    },
                    xAxis: {
                        categories: day
                    },
                    yAxis: {
                        allowDecimals: false,
                        title: {
                            text: 'Total Harga'
                        }
                    },
                    series: [{
                        data: total
                    }]
                });

                $('#penjualanoffline .highcharts-legend').hide()
            }
        })

        $.ajax({
            url:"{{route('dashboard.penjualanonline')}}",
            method:"GET",
            success:function(data){
                var total = [];
                var day = [];
                data.forEach(element => {
                    total.push(element['total'])
                    day.push(element['day'])
                });
                const chart = Highcharts.chart('penjualanonline', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: "Penjualan Online {{ucwords(strtolower($lokasi->Grup))}}",
                        fontSize: '12px'
                    },
                    credits: {
                        enabled: false
                    },
                    xAxis: {
                        categories: day
                    },
                    yAxis: {
                        allowDecimals: false,
                        title: {
                            text: 'Total Harga'
                        }
                    },
                    series: [{
                        data: total
                    }]
                });

                $('#penjualanonline .highcharts-legend').hide()
            }
        })


        $.ajax({
            url:"{{route('dashboard.statuspesanan')}}",
            method:"GET",
            success:function(data){

                var status = [];
                var total = [];
                data.forEach(element => {
                    status.push(element['status'])
                    total.push(element['total'])
                });
                const chart = Highcharts.chart('statuspesanan', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: "Status Pesanan Online Hari Ini",
                        fontSize: '12px'
                    },
                    credits: {
                        enabled: false
                    },
                    xAxis: {
                        categories: status
                    },
                    yAxis: {
                        allowDecimals: false,
                        title: {
                            text: 'jumlah'
                        }
                    },
                    series: [{
                        data: total
                    }]
                });

                $('#statuspesanan .highcharts-legend').hide()
            }
        })

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
