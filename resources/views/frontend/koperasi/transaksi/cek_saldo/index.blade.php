@extends('frontend.master')

@section('title', 'Cek Saldo')

@section('transaksi', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Cek Saldo</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i>Cek Saldo</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form method="POST" action="{{route('poslaporan.tracestok.cetak')}}" target="_blank">
                            @csrf
                            <div class="form-group">
                                <label for="barcode">Barcode / Ekop</label>
                                <select class="form-control" id="barcode" name="barcode">
                                    <option value="">Pilih Barcode</option>
                                    @forelse ($traktifasi as $item)
                                    <option value="{{$item->Nomor}}">{{$item->NoEkop}}</option>
                                    @empty

                                    @endforelse
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode">Kode</label>
                                        <input type="text" class="form-control" id="kode" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control" id="nama" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="saldo">Saldo</label>
                                <input type="text" class="form-control" id="saldo" readonly>
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

        $('#barcode').select2()
        $('#cetak').select2()

        $('#barcode').on('change',function () {
            var id = $(this).find(':selected').val()
            if(id != ''){
                $.ajax({
                    url:"{{route('koperasi.saldo.cek')}}",
                    method:"GET",
                    data:{'id':id}
                }).done(function (response) {
                    if(response.status){
                        var data = response.data;
                        $('#kode').val(data.kode)
                        $('#nama').val(data.nama)
                        $('#saldo').val(data.saldo)

                    }
                 })
            }else{
                $('#kode').val('')
                $('#nama').val('')
                $('#saldo').val('')

            }
         })
     })
</script>
@endsection
