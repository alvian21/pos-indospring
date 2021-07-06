@extends('frontend.master')

@section('title', 'Pembelian Baru')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi | Pembelian Baru</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Pembelian Baru</h4>
                        <a href="{{route('pos.pembelianbaru.create')}}"  class="btn btn-primary float-right addBarang"> Tambah Pembelian</a>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">
                            {{-- <select id="select" class="form-control input-sm">
                                <option>Show All</option>
                                <option>With Pictures</option>
                                <option>Without Pictures</option>
                                <option>With Barcode</option>
                                <option>Without Barcode</option>

                            </select> --}}
                            <table class="table table-striped" id="trmutasihd">
                                <thead>
                                    <tr>
                                        <th>Transaksi</th>
                                        <th>Nomor</th>
                                        <th>Supplier</th>
                                        <th>Lokasi Tujuan</th>
                                        <th>Total Harga</th>
                                        <th>Total Harga Setelah Pajak</th>

                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($trmutasihd as $row)
                                       <tr>
                                           <td>{{$row->Transaksi}}</td>
                                           <td>{{$row->Nomor}}</td>
                                           <td>{{$row->KodeSuppCust}}</td>
                                           <td>{{$row->LokasiTujuan}}</td>
                                           <td>{{$row->TotalHarga}}</td>
                                           <td>{{$row->TotalHargaSetelahPajak}}</td>
                                           <td><button type="button" class="btn btn-success">Detail</button></td>
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
        $('#trmutasihd').DataTable();
     })
</script>
@endsection
