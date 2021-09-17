@extends('frontend.master')

@section('title', 'Laporan Realtime Stok')

@section('poslaporan', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Laporan Realtime Stok</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Laporan Realtime Stok</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form method="POST" action="{{route('poslaporan.realtimestok.cetak')}}" target="_blank">
                            @csrf
                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <select class="form-control" id="kategori" name="kategori">
                                    <option value="semua">Semua</option>
                                    @foreach ($kategori as $item)
                                    <option value="{{$item->Kode}}">{{$item->Nama}}</option>
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
        </div>
    </div>
</section>

@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        $('#kategori').select2()
        $('#cetak').select2()
     })
</script>
@endsection
