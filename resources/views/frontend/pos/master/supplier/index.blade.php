@extends('frontend.master')

@section('title', 'Pembelian Baru')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Daftar Barang</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Daftar Barang</h4>
                        <button type="button" class="btn btn-primary float-right addBarang">Tambah Barang</button>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">
                            <select id="select" class="form-control input-sm">
                                <option>Show All</option>
                                <option>With Pictures</option>
                                <option>Without Pictures</option>
                                <option>With Barcode</option>
                                <option>Without Barcode</option>

                            </select>
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Kode Barcode</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>TampilDiMobile</th>
                                        <th>Harga Jual</th>
                                        <th>Gambar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts')
<script type="text/javascript">

</script>
@endsection
