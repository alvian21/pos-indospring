@extends('frontend.master')

@section('title', 'Pembelian Baru')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail Pembelian</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i>Detail Pembelian</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-striped" id="tabel">
                                <thead>
                                    <tr>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Harga (setelah diskon)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($trmutasidt as $row)
                                    @php
                                        $total = $row->Jumlah * $row->Harga;
                                        $diskon = $row->DiskonTunai + ($row->DiskonPersen/100 * $total);
                                        $res = $total - $diskon;
                                    @endphp
                                    <tr>
                                        <td>{{$row->KodeBarang}}</td>
                                        <td>{{$row->Nama}}</td>
                                        <td>{{$row->Jumlah}}</td>
                                        <td>{{$res}}</td>
                                    </tr>
                                    @empty

                                    @endforelse
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
    $(document).ready(function () {
        $('#tabel').DataTable();
     })
</script>
@endsection
