@extends('frontend.master')

@section('title', 'Laporan Mutasi Bulanan')

@section('poslaporan', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Laporan Mutasi Bulanan</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Laporan Mutasi Bulanan</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form method="POST" action="{{route('poslaporan.mutasibulanan.store')}}" target="_blank">
                            @csrf
                            <div class="form-group">
                                <label for="periode">Periode</label>
                                <input type="month" class="form-control" id="periode" required name="periode">
                            </div>
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <select class="form-control" id="lokasi" name="lokasi">
                                    {{-- <option value="Semua">Semua</option> --}}
                                    @foreach ($mslokasi as $item)
                                    <option value="{{$item->Kode}}">{{$item->Kode}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cetak">Cetak</label>
                                <select class="form-control" id="cetak" name="cetak">
                                    {{-- <option value="pdf">Pdf</option> --}}
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
     })
</script>
@endsection