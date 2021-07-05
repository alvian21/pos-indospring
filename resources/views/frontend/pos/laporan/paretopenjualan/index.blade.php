@extends('frontend.master')

@section('title', 'Laporan Penjualan')

@section('poslaporan', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Laporan Pareto Penjualan</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Laporan Penjualan | Pareto</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form method="POST" action="{{route('poslaporan.paretopenjualan.cetakpdf')}}" target="_blank">
                            @csrf
                            <div class="form-group">
                                <label for="transaksi">Transaksi</label>
                                <select class="form-control" id="transaksi" name="transaksi">
                                    <option value="semua">Semua</option>
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <select class="form-control" id="lokasi" name="lokasi">
                                    @foreach ($mslokasi as $item)
                                    <option value="{{$item->Kode}}">{{$item->Kode}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="periode" class="text-dark">Periode</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="date" required class="form-control" id="periode" required
                                            name="periode1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="date" required class="form-control" id="periode" required
                                            name="periode2">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jumlah">Jumlah Data</label>
                                <select class="form-control" id="jumlah" name="jumlah">
                                    @for ($i=1; $i <= count($msbarang); $i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cetak">Cetak</label>
                                <select class="form-control" id="cetak" name="cetak">
                                    <option value="pdf">Pdf</option>
                                    {{-- <option value="excel">Excel</option> --}}
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
    $(document).ready(function () {
        $('#jumlah').select2()
        $('#transaksi').select2()
        $('#lokasi').select2()
        $('#cetak').select2()
        $('#cetak-detail').select2()
        $('#customer-detail').select2()
        $('#transaksi-detail').select2()
        $('#lokasi-detail').select2()
     })
</script>
@endsection