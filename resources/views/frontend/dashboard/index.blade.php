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
                        <h4 class="text-center">Penjualan Toko Plant 1</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart"
                          class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Penjualan Online Plant 1</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart"
                          class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Status Pesanan Online Hari ini</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart"
                          class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Penjualan Toko Plant 1</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart"
                          class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Penjualan Toko Plant 1</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart"
                          class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Penjualan Online Plant 1</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart"
                          class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Status Pesanan Online Hari ini</h4>

                    </div>
                    <div class="card-body">

                        <canvas id="myChart"
                          class="chartjs-render-monitor"></canvas>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
