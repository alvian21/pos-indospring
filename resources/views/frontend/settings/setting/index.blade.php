@extends('frontend.master')

@section('title', 'Setting | Mssetting')

@section('setting', 'active')
@section('css')
<style>
    /* for sm */

    .custom-switch.custom-switch-sm .custom-control-label {
        padding-left: 1rem;
        padding-bottom: 1rem;
    }

    .custom-switch.custom-switch-sm .custom-control-label::before {
        height: 1rem;
        width: calc(1rem + 0.75rem);
        border-radius: 2rem;
    }

    .custom-switch.custom-switch-sm .custom-control-label::after {
        width: calc(1rem - 4px);
        height: calc(1rem - 4px);
        border-radius: calc(1rem - (1rem / 2));
    }

    .custom-switch.custom-switch-sm .custom-control-input:checked~.custom-control-label::after {
        transform: translateX(calc(1rem - 0.25rem));
    }

    /* for md */

    .custom-switch.custom-switch-md .custom-control-label {
        padding-left: 2rem;
        padding-bottom: 1.5rem;
    }

    .custom-switch.custom-switch-md .custom-control-label::before {
        height: 1.5rem;
        width: calc(2rem + 0.75rem);
        border-radius: 3rem;
    }

    .custom-switch.custom-switch-md .custom-control-label::after {
        width: calc(1.5rem - 4px);
        height: calc(1.5rem - 4px);
        border-radius: calc(2rem - (1.5rem / 2));
    }

    .custom-switch.custom-switch-md .custom-control-input:checked~.custom-control-label::after {
        transform: translateX(calc(1.5rem - 0.25rem));
    }

    /* for lg */

    .custom-switch.custom-switch-lg .custom-control-label {
        padding-left: 3rem;
        padding-bottom: 2rem;
    }

    .custom-switch.custom-switch-lg .custom-control-label::before {
        height: 2rem;
        width: calc(3rem + 0.75rem);
        border-radius: 4rem;
    }

    .custom-switch.custom-switch-lg .custom-control-label::after {
        width: calc(2rem - 4px);
        height: calc(2rem - 4px);
        border-radius: calc(3rem - (2rem / 2));
    }

    .custom-switch.custom-switch-lg .custom-control-input:checked~.custom-control-label::after {
        transform: translateX(calc(2rem - 0.25rem));
    }

    /* for xl */

    .custom-switch.custom-switch-xl .custom-control-label {
        padding-left: 4rem;
        padding-bottom: 2.5rem;
    }

    .custom-switch.custom-switch-xl .custom-control-label::before {
        height: 2.5rem;
        width: calc(4rem + 0.75rem);
        border-radius: 5rem;
    }

    .custom-switch.custom-switch-xl .custom-control-label::after {
        width: calc(2.5rem - 4px);
        height: calc(2.5rem - 4px);
        border-radius: calc(4rem - (2.5rem / 2));
    }

    .custom-switch.custom-switch-xl .custom-control-input:checked~.custom-control-label::after {
        transform: translateX(calc(2.5rem - 0.25rem));
    }
</style>
@endsection

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Mssetting</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Mssetting</h4>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form action="" method="post" id="formSetting">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card text-white bg-dark rounded" style="height: 18rem">
                                        <div class="card-body">
                                            <div class="row mt-4">
                                                <input type="hidden" name="name9" value="{{$PajakPenjualan->Kode}}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" readonly class="text-center form-control"
                                                            value="{{$PajakPenjualan->Nama}}"
                                                            name="inputmblPajakPenjualannama">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" readonly class="text-center form-control"
                                                        value="{{$PajakPenjualan->Nilai}}"
                                                        name="inputmblPajakPenjualannilai">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch custom-switch-md float-right">
                                                        <input type="checkbox" disabled class="custom-control-input"
                                                            id="MobilePajakPenjualan"
                                                            @if($PajakPenjualan->aktif == 1 ) checked
                                                        @endif name="ckmblPajakPenjualan">
                                                        <label class="custom-control-label"
                                                            for="MobilePajakPenjualan"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="name10" value="{{$DiskonRpPenjualanReadOnly->Kode}}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-group">

                                                            <input type="text" readonly class="text-center form-control"
                                                            value="{{$DiskonRpPenjualanReadOnly->Nama}}"
                                                            name="inputmblDiskonRpPenjualanReadOnlynama">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="custom-control custom-switch custom-switch-md float-right">
                                                        <input type="checkbox" disabled class="custom-control-input"
                                                            id="DiskonRpPenjualanReadOnly" name="ckDiskonRpPenjualanReadOnly"
                                                            @if($DiskonRpPenjualanReadOnly->aktif == 1 ) checked
                                                        @endif>
                                                        <label class="custom-control-label"
                                                            for="DiskonRpPenjualanReadOnly"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="name11" value="{{$DiskonPersenPenjualanReadOnly->Kode}}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-group">

                                                            <textarea class="form-control"
                                                                id="exampleFormControlTextarea1" rows="6" readonly
                                                                name="inputDiskonPersenPenjualanReadOnlynama">{{$DiskonPersenPenjualanReadOnly->Nama}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="custom-control custom-switch custom-switch-md float-right">
                                                        <input type="checkbox" disabled class="custom-control-input"
                                                            id="DiskonPersenPenjualanReadOnly" name="ckDiskonPersenPenjualanReadOnly"
                                                            @if($DiskonPersenPenjualanReadOnly->aktif == 1 ) checked
                                                        @endif>
                                                        <label class="custom-control-label"
                                                            for="DiskonPersenPenjualanReadOnly"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card text-white bg-dark rounded" style="height: 14rem">
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input type="hidden" name="name12" value="{{$cetak->Kode}}">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control text-center"
                                                            value="{{$cetak->Nama}}" readonly name="inputcetak">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group text-center">
                                                        <div class="custom-control custom-switch custom-switch-md">
                                                            <input type="checkbox" disabled class="custom-control-input cetak"
                                                                name="ckcetak" id="cetak"
                                                                @if($cetak->aktif == 1 ) checked @endif>
                                                            <label class="custom-control-label" for="cetak"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
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
<script>
    $(document).ready(function(){
        $('.cardpengajuanday').each(function () {
          var select = $(this).find('select')
            select.each(function () {
                $(this).select2()
            })
         })
        function ajax() {
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
         }

         $('.btnapply').on('click', function () {
             var form =$('#formSetting').serialize()
             var inputtext = $('input:text').val();
             var inputtime1 = $('#inputmblbelanjajamoff').val();
             var inputnumber = $('input[type=number]');
             var resnumber = false;
             var select = $('select');
             var resselect = false;
             select.each(function () {
                if($(this).val() == '0'){
                    resselect = true;
                }
              })

              inputnumber.each(function () {
                if($(this).val() == ''){
                    resnumber = true;
                }
              })
            //  console.log(select)
             if(inputtext == '' || inputtext== undefined){
                swal("form harus diisi semua!");
                return false;
             }else if(resselect){
                swal("Tanggal pinjaman harus dipilih!");
                return false;
             }else if(inputtime1 == '' || inputtime1== undefined){
                swal("form waktu harus diisi!");
                return false;
             }else if(resnumber){
                swal("form harus diisi semua!");
                return false;
             }

             swal({
                text: "Apa kamu yakin apply data ini ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willInsert) => {
                if (willInsert) {
                    ajax();
                    $.ajax({
                        url:"{{route('settings.mssetting.store')}}",
                        method:"POST",
                        data:form,
                        success:function(data){
                           if(data['message']=='true'){
                            swal("Data berhasil di apply!", {
                                icon: "success",
                                });
                           }
                        }
                    })


                }
                });

          })

    })
</script>
@endsection
