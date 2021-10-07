@extends('frontend.master')

@section('title', 'Laporan Laba Rugi')

@section('poslaporan', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Laporan Laba Rugi</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Laporan Laba Rugi</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form method="POST" action="{{route('poslaporan.labarugi.store')}}" target="_blank">
                            @csrf
                            <div class="form-group">
                                <label for="periode">Periode</label>
                                <select class="form-control" id="periode" name="periode">
                                    @foreach ($years as $item)
                                    <option value="{{$item}}">{{$item}}</option>
                                    @endforeach
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
                            <div class="form-group">
                                <label for="cetak">Cetak</label>
                                <select class="form-control" id="cetak" name="cetak">
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
    $(document).ready(function () {

        $('#lokasi').select2()
        $('#cetak').select2()
        $('#periode').select2()
     })
</script>
@endsection
