@extends('frontend.master')

@section('title', 'TopUp e-kop')

@section('transaksi', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>TopUp e-kop</h1>
    </div>
    <div class="section-body">
        <form id="formTopUp">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i>TopUp e-kop</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div id="data-alert"></div>

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
                                <label for="jumlah_topup">TopUp</label>
                                <input type="text" class="form-control" id="jumlah_topup" >
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary btnpost">Post</button>
                                </div>
                            </div>

                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card card-dark">

                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form>

                            <div class="form-group">
                                <label for="saldo_awal">Saldo Awal</label>
                                <input type="text" class="form-control" id="saldo_awal" value="0" readonly>
                            </div>
                            <div class="form-group">
                                <label for="topup">TopUp</label>
                                <input type="text" class="form-control" id="topup" value="0" readonly>
                            </div>
                            <div class="form-group">
                                <label for="saldo_akhir">Saldo Akhir</label>
                                <input type="text" class="form-control" id="saldo_akhir" value="0" readonly>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
</section>

@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var normalsaldo = 0;
        $('#barcode').select2()
        $('#cetak').select2()
        $('#jumlah_topup').mask('000.000.000.000', {
            reverse: true
        });

        const formatRupiah = (money) => {
            return new Intl.NumberFormat('id-ID',
                { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }
            ).format(money);
        }

        function ajax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
         }
        function convertToAngka(rupiah)
        {
            return parseFloat(rupiah.replace(/,.*|[^0-9]/g, ''), 10);
        }
        $('#barcode').on('change',function () {
            var id = $(this).find(':selected').val()
            if(id != ''){
                $.ajax({
                    url:"{{route('koperasi.topup.cek')}}",
                    method:"GET",
                    async:false,
                    data:{'id':id}
                }).done(function (response) {
                    if(response.status){
                        var data = response.data;
                        $('#kode').val(data.kode)
                        $('#nama').val(data.nama)
                        $('#saldo_awal').val(formatRupiah(data.normalsaldo))
                        normalsaldo = data.normalsaldo
                    }
                 })
            }else{
                $('#kode').val('')
                $('#nama').val('')
                $('#saldo').val('')
            }
         })

         $('#jumlah_topup').on('keyup',function () {
             var topup = $(this).val()
             topup = convertToAngka(topup);
             var saldo_awal =normalsaldo

             if(saldo_awal > 0){
                var tambah = parseInt(saldo_awal) + topup
             }else{
                var tambah = topup
             }

             $('#topup').val(formatRupiah(topup))
             $('#saldo_akhir').val(formatRupiah(tambah))
          })


          $('.btnpost').on('click', function () {
            var topup = $("#jumlah_topup").val()
            var barcode = $("#barcode").val()
            var cvtopup = formatRupiah(convertToAngka(topup))
            topup = convertToAngka(topup)
            if(barcode != '' &&  topup > 0){
                swal({
                title: "Apa anda yakin?",
                text: "Anda akan TopUp Senilai "+cvtopup,
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willInsert) => {
                    if (willInsert) {
                            ajax()
                            $.ajax({
                                url:"{{route('koperasi.topup.store')}}",
                                method:"POST",
                                data:{
                                    'jumlah_topup':topup,
                                    'barcode':barcode
                                }
                            }).done(function (response) {
                                console.log(response);
                                if(response.status){
                                    $('#formTopUp').trigger('reset')
                                    $('#barcode').val('').change()
                                    swal("Success!", "TopUp Berhasil Dilakukan!", "success");
                                }else{
                                    $('#data-alert').html(response.data)
                                }
                             })
                    }
                });
            }else{
                swal("Error!", "Semua form harus diisi!", "error");
            }

             })

     })
</script>
@endsection
