@extends('frontend.master')

@section('title', 'Proses Bulanan')

@section('transaksi', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Proses Bulanan</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Proses Bulanan</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form method="POST" action="{{route('koperasi.tagihan-kredit.store')}}" target="_blank">
                            @csrf
                            <div class="form-group">
                                <label for="tanggal_akhir">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tanggal_akhir" required name="tanggal_akhir">
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary btnproses">Proses</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i>Simpan Pinjam</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form >
                            @csrf
                            <div class="form-group">
                                <label for="periode">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="periode" required name="periode">
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary btnprosessimpanpinjam">Proses</button>
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

        function ajax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
         }

         function BtnLoading(elem) {
        $(elem).attr("data-original-text", $(elem).html());
        $(elem).prop("disabled", true);
        $(elem).html('<i class="spinner-border spinner-border-sm"></i> Loading...');
    }

    function BtnReset(elem) {
        $(elem).prop("disabled", false);
        $(elem).text($(elem).attr("data-original-text"));
    }
        $('.btnproses').on('click', function(){
            var tanggal_akhir = $('#tanggal_akhir').val()
            if(tanggal_akhir != ''){
            var $this = $(this)
            swal({
                    title: "Apa anda yakin?",
                    text: "Apa anda yakin melakukan proses bulanan ini ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willInsert) => {
                    if (willInsert) {
                        BtnLoading($this)
                        ajax()
                        $.ajax({
                            url:"{{route('koperasi.proses-bulanan.store')}}",
                            method:"POST",
                            data:{
                                'tanggal_akhir':tanggal_akhir,
                                'status':'prosesulang'
                            }
                        }).done(function(response){
                            console.log(response);
                            if(response.status){
                                BtnReset($this)
                                $('#tanggal_akhir').val('')
                                swal("success!", "Proses Bulanan Berhasil dilakukan!", "success");
                            }else{
                                swal("Error!", response.data, "error");
                            }
                        })
                    }
              });

            }
        })

        $('.btnprosessimpanpinjam').on('click', function(){
            var periode = $('#periode').val()
            if(periode != ''){
            var $this = $(this)
            swal({
                    title: "Apa anda yakin?",
                    text: "Apa anda yakin melakukan proses bulanan ini ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willInsert) => {
                    if (willInsert) {
                        BtnLoading($this)
                        ajax()
                        $.ajax({
                            url:"{{route('koperasi.proses-bulanan.simpan.pinjam')}}",
                            method:"POST",
                            data:{
                                'periode':periode,
                                'status':'cek'
                            }
                        }).done(function(response){

                            if(response.status){
                                swal({
                                    text: "Data sudah ada, apakah akan diproses ulang?",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                    })
                                    .then((willInsertData) => {
                                    if (willInsertData) {
                                        $.ajax({
                                            url:"{{route('koperasi.proses-bulanan.simpan.pinjam')}}",
                                            method:"POST",
                                            data:{
                                                'periode':periode,
                                                'status':'insert'
                                            }
                                        }).done(function (responsedata) {
                                            if(responsedata.status){
                                                BtnReset($this)
                                                $('#periode').val('')
                                                swal("success!", "Proses Bulanan Simpan Pinjam Berhasil dilakukan!", "success");
                                            }else{
                                                swal("Error!", responsedata.data, "error");
                                            }
                                         })
                                    }else{
                                        BtnReset($this)
                                         $('#periode').val('')
                                    }
                                    });


                            }
                            else{
                                $.ajax({
                                    url:"{{route('koperasi.proses-bulanan.simpan.pinjam')}}",
                                    method:"POST",
                                    data:{
                                        'periode':periode,
                                        'status':'insert'
                                    }
                                }).done(function (responsedata) {
                                    console.log(responsedata);
                                    if(responsedata.status){
                                        BtnReset($this)
                                        $('#periode').val('')
                                        swal("success!", "Proses Bulanan Simpan Pinjam Berhasil dilakukan!", "success");
                                    }else{
                                        swal("Error!", responsedata.data, "error");
                                    }
                                    })
                            }

                        })
                    }
              });

            }
        })
     })
</script>
@endsection
