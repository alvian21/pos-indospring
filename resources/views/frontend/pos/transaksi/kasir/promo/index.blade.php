@extends('frontend.master')

@section('title', 'List Promo')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>List Promo</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> List Promo</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form action="" method="post" id="formTransaksi">

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="barang">Barang</label>
                                        <select class="form-control js-example-basic-single" name="barang" id="barang">
                                            <option value="0">Pilih Barang</option>
                                            @forelse ($msbarang as $item)
                                            <option value="{{$item->Kode}}">{{$item->Kode}} |
                                                @if($item->KodeBarcode!=null)
                                                {{$item->KodeBarcode}} | @endif {{$item->Nama}}</option>
                                            @empty

                                            @endforelse
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nama_barang">Nama barang</label>
                                        <input type="text" class="form-control" name="nama_barang" readonly
                                            id="nama_barang">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="harga">Harga</label>
                                        <input type="number" class="form-control" name="harga" readonly id="harga">
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ttl_harga_pajak">Total Harga + Pajak</label>
                                        <input type="text" class="form-control ttl_harga_pajak" style="color: red"
                                            id="ttl_harga_pajak" value="{{$trkasir["total_harga_setelah_pajak"]}}"
                                            name="ttl_harga_pajak" readonly>
                                    </div>
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
    $(document).ready(function(e){


})
</script>
@endsection
