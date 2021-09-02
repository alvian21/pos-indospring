@extends('frontend.master')
@section('title', 'Transaksi | Cek Harga')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Cek Harga</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i>Cek Harga</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form method="POST" action="{{route('poslaporan.tracestok.cetak')}}" target="_blank">
                            @csrf
                            <div class="form-group">
                                <label for="barang">Pilih Barang</label>
                                <select class="form-control" id="barang" name="barang">
                                  <option value="">Pilih Barang</option>
                                    @forelse ($barang as $item)
                                    <option value="{{$item->Kode}}">{{$item->Kode}} | {{$item->Nama}}</option>
                                    @empty

                                    @endforelse
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="text" class="form-control" id="harga" readonly>
                            </div>
                            <div class="form-group">
                                <label for="stok">Stok</label>
                                <input type="text" class="form-control" id="stok" readonly>
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
        $('#barang').on('change',function () {
            var barang = $(this).val()
            if(barang != ''){
                $.ajax({
                    url:"{{route('pos.harga.index')}}",
                    method:"GET",
                    data:{'barang':barang}
                }).done(function (response) {
                    if(response.status){
                        console.log(response);
                        var data = response.data;
                        $('#harga').val(data.harga)
                        $('#stok').val(data.stok)
                    }else{
                        $('#harga').val('')
                        $('#stok').val('')

                    }
                 })
            }else{
                $('#harga').val('')
                $('#stok').val('')

            }
         })
     })
</script>
@endsection
