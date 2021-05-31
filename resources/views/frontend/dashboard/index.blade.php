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
                    <div class="card-header">
                        <h4 class="text-center">Penjualan {{$lokasi['Nama']}}</h4>

                    </div>
                    <div class="card-body">

                        <div id="penjualanoffline"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Penjualan Online {{$lokasi['Nama']}}</h4>

                    </div>
                    <div class="card-body">

                        <div id="penjualanonline"></div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Status Pesanan Online Hari ini</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart" class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Status Pinjaman</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart" class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Email</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart" class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Master Barang</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart" class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Minimum Stok</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart" class="chartjs-render-monitor"></canvas>

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
                        text: "Penjualan {{$lokasi['Nama']}}"
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
                        name: 'Penjualan Toko Plant 1',
                        data: total
                    }]
                });
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
                        text: "Penjualan {{$lokasi['Nama']}}"
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
                        name: 'Penjualan Toko Plant 1',
                        data: total
                    }]
                });
            }
        })
  })
</script>
@endsection
