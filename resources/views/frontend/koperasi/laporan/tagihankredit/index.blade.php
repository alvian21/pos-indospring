@extends('frontend.master')

@section('title', 'Laporan Penjualan Kredit')

@section('laporan', 'active')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Laporan Penjualan Kredit</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-6">
                    <div class="card card-dark">
                        <div class="card-header container-fluid d-flex justify-content-between">
                            <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Laporan Penjualan Kredit</h4>
                        </div>
                        <div class="card-body">
                            @include('frontend.include.alert')
                            <form method="POST" action="{{ route('koperasi.tagihan-kredit.store') }}" target="_blank">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="periode_awal">Periode Awal</label>
                                            <input type="date" class="form-control" id="periode_awal" required
                                                name="periode_awal">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="periode_akhir">Periode Akhir</label>
                                            <input type="date" class="form-control" id="periode_akhir" required
                                                name="periode_akhir">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="cetak">Cetak</label>
                                    <select class="form-control" id="cetak" name="cetak">
                                        <option value="pdf">Pdf</option>
                                        <option value="excel">Excel</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Cetak</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#barang').select2()
            $('#cetak').select2()
        })
    </script>
@endsection
