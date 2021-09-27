@extends('frontend.master')

@section('title', 'Laporan Simpan Pinjam')

@section('laporan', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Laporan Simpan Pinjam</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Laporan Simpan Pinjam</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form method="POST" action="{{route('koperasi.simpan-pinjam.store')}}" target="_blank">
                            @csrf
                            <div class="form-group">
                                <label for="periode">Periode</label>
                                <input type="month" class="form-control" id="periode" required name="periode">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="Semua Status">Semua Status</option>
                                    <option value="VERIFIKASI">VERIFIKASI</option>
                                    <option value="DISETUJUI">DISETUJUI</option>
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

        $('#barang').select2()
        $('#cetak').select2()

        $('#periode').on('change', function () {
            $('.alert-danger').hide()
         })
     })
</script>
@endsection
