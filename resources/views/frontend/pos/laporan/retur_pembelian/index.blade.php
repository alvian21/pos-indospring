@extends('frontend.master')

@section('title', 'Laporan Retur Pembelian')

@section('poslaporan', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Laporan Retur Pembelian</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Laporan Retur Pembelian | Summary</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form method="POST" action="{{route('poslaporan.retur_pembelian.cetakpdf')}}" target="_blank">
                            @csrf

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
                                <label for="supplier">Supplier</label>
                                <select class="form-control" id="supplier" name="supplier">
                                    <option value="semua">Semua</option>
                                    @foreach ($mssupplier as $item)
                                    <option value="{{$item->Kode}}">{{$item->Kode}} | {{$item->Nama}}</option>
                                    @endforeach
                                </select>
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
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Laporan  Retur Pembelian | Detail</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form method="POST" action="{{route('poslaporan.retur_pembelian.cetakdetail')}}" target="_blank">
                            @csrf

                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <select class="form-control" id="lokasi-detail" name="lokasi">
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
                                <label for="supplier">Supplier</label>
                                <select class="form-control" id="supplier-detail" name="supplier">
                                    <option value="semua">Semua</option>
                                    @foreach ($mssupplier as $item)
                                    <option value="{{$item->Kode}}">{{$item->Kode}} | {{$item->Nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cetak">Cetak</label>
                                <select class="form-control" id="cetak-detail" name="cetak">
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
        $('#customer').select2()
        $('#transaksi').select2()
        $('#lokasi').select2()
        $('#cetak').select2()
        $('#supplier').select2()
        $('#supplier-detail').select2()
        $('#cetak-detail').select2()
        $('#customer-detail').select2()
        $('#transaksi-detail').select2()
        $('#lokasi-detail').select2()
     })
</script>
@endsection
