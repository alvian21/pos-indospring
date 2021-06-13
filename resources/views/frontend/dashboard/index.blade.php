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
                <div class="card" style="height: 92%">
                    <div class="card-header d-flex justify-content-center">
                        <h4 class="text-center"><a href="{{route('pinjaman.index')}}">Status Pinjaman</a></h4>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card border-dark shadow p-3 mb-5 bg-white rounded"
                                            style="height: 10rem;">
                                            {{-- <div class="card-header text-dark d-flex justify-content-center">Level 2</div> --}}
                                            <div class="card-body  text-center text-dark">
                                                <p>QUOTA</p>
                                                <h3 class="mt-3">{{$kuota->Nilai}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card border-dark shadow p-3 mb-5 bg-white rounded"
                                            style="height: 10rem;">
                                            <div class="card-body text-center text-dark">
                                                <p style="font-size: 12px">ON PROGRESS</p>
                                                <h3 class="mt-3">{{$total}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow p-3 mb-5 bg-white rounded" style="height: 23rem;">

                                    <div class="card-body text-center text-dark">
                                       <h5>Level 1</h5>
                                        <p class="card-text" style="font-size: 12px">PENGAJUAN</p>
                                        <h5>{{$pinjaman[0]['total']}}</h5>
                                        <p class="card-text" style="font-size: 12px">TDK VERIFIKASI</p>
                                        <h5>{{$pinjaman[1]['total']}}</h5>
                                        <p class="card-text" style="font-size: 12px">VERIFIKASI</p>
                                        <h5>{{$pinjaman[2]['total']}}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow p-3 mb-5 bg-white rounded" style="height: 23rem;">

                                    <div class="card-body text-center text-dark">
                                        <h5>Level 2</h5>
                                        <p class="card-text" style="font-size: 12px">TDK DIPROSES</p>
                                        <h5>{{$pinjaman[3]['total']}}</h5>
                                        <p class="mt-5 card-text" style="font-size: 12px">DIPROSES</p>
                                        <h5>{{$pinjaman[4]['total']}}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow p-3 mb-5 bg-white rounded" style="height: 23rem;">

                                    <div class="card-body text-center text-dark">
                                        <h5>Level 3</h5>
                                        <p style="font-size: 12px">TDK DISETUJUI</p>
                                        <h5>{{$pinjaman[5]['total']}}</h5>
                                        <p class="mt-5" style="font-size: 12px">DISETUJUI</p>
                                        <h5>{{$pinjaman[6]['total']}}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <h4 class="mb-0"><a href="http://31.220.50.154/koperasi/msbarang" target="_blank">Master
                                Barang</a></h4>
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
                    colors: [
                        '#22c4e0',
                        '#e0d322',
                        '#22e038',
                        '#2287e0',
                        ],
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
                    plotOptions: {
                        series: {
                            colorByPoint: true
                        }
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

    chartemailstatus  = Highcharts.chart('emailstatus', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie',
                        events: {
                            load: requestEmailStatus
                        }
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
                        data: []
                    }]
                });
                $('#emailstatus .highcharts-title').remove()




  chartemailstatuswithout = Highcharts.chart('emailstatuswithout', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie',
                        events: {
                            load: requestEmailStatusWithout
                        }
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
                        data: []
                    }]
                });
                $('#emailstatuswithout .highcharts-title').remove()



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
        $('#statuspesanan .highcharts-title').css('cursor','pointer');
        $('#statuspesanan .highcharts-title').on('click', function(){
            window.location.href="{{route('status.pesanan.index')}}"
        })
  })
</script>
@endsection
