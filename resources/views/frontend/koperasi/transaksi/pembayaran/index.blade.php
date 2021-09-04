@extends('frontend.master')

@section('title', 'TopUp Pembayaran')

@section('transaksi', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>TopUp Pembayaran</h1>
    </div>
    <div class="section-body">
        <form id="formTopUp">
            <div class="row">
                <div class="col-6">
                    <div class="card card-dark">
                        <div class="card-header container-fluid d-flex justify-content-between">
                            <h4 class="text-dark"><i class="fas fa-list pr-2"></i>TopUp Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            @include('frontend.include.alert')
                            <div id="data-alert"></div>

                            @csrf
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal">
                            </div>

                            <div class="form-group">
                                <label for="barcode">Barcode / Ekop</label>
                                <input type="text" class="form-control" id="barcode">
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
                                <label for="jumlah_topup">Nilai</label>
                                <input type="number" class="form-control" id="jumlah_topup">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var normalsaldo = 0;



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
            return parseFloat(rupiah.replace(/,^[+-]?\d+$/g, ''), 10);
        }


        function resetForm() {
            $('#kode').val('')
                        $('#nama').val('')
                        $('#saldo').val('')
                        $('#topup').val(0)
                       $('#saldo_akhir').val(0)
                       $('#saldo_awal').val(0)
         }
        $('#barcode').on('keyup',function () {
            var barcode = $(this).val()
            if(barcode != ''){
                $.ajax({
                    url:"{{route('koperasi.pembayaran.cek')}}",
                    method:"GET",
                    async:false,
                    data:{'cari':barcode}
                }).done(function (response) {

                    if(response.status){
                        var data = response.data;
                        $('#kode').val(data.kode)
                        $('#nama').val(data.nama)
                        $('#saldo_awal').val(formatRupiah(data.normalsaldo))
                        normalsaldo = data.normalsaldo
                        totalSaldo()
                    }else{
                        resetForm()
                    }
                 })
            }else{
                $('#kode').val('')
                $('#nama').val('')
                $('#saldo').val('')
            }
         })

         function totalSaldo()
         {
            var topup = $('#jumlah_topup').val()
             topup = convertToAngka(topup);
                var saldo_awal =normalsaldo
                var tambah = 0;
                if(saldo_awal >= 0 && topup > 0){
                    var tambah = parseInt(saldo_awal) + topup
                }

                if(!topup){
                    topup = 0
                }else if(topup < 0){
                    topup = 0
                }

                $('#topup').val(formatRupiah(topup))
                $('#saldo_akhir').val(formatRupiah(tambah))
         }

         $('#jumlah_topup').on('keyup',function () {
            totalSaldo()
          })


          $('.btnpost').on('click', function () {
            var topup = $("#jumlah_topup").val()
            var barcode = $("#barcode").val()
            var tanggal = $("#tanggal").val()
            var cvtopup = formatRupiah(convertToAngka(topup))
            topup = convertToAngka(topup)

            if(topup < 0 ){
                swal("Error!", "Form nilai tidak boleh minus!", "error");
            }
            if(barcode != '' &&  topup != '' && tanggal != ''){
                swal({
                title: "Apa anda yakin?",
                text: "Anda akan TopUp pembayaran Senilai "+cvtopup,
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willInsert) => {
                    if (willInsert) {
                            ajax()
                            $.ajax({
                                url:"{{route('koperasi.pembayaran.store')}}",
                                method:"POST",
                                data:{
                                    'jumlah_topup':topup,
                                    'barcode':barcode,
                                    'tanggal':tanggal,
                                }
                            }).done(function (response) {

                                if(response.status){
                                    $('#formTopUp').trigger('reset')

                                    swal("Success!", "TopUp pembayaran Berhasil Dilakukan!", "success");
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
