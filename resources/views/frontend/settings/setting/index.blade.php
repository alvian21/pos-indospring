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
                        <button type="button" class="btn btn-primary btnapply">Apply</button>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form action="" method="post" id="formSetting">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card text-white bg-dark rounded" style="height: 14rem">
                                        <div class="card-body">
                                            <input type="hidden" name="name1" value="{{$MobileBelanjaOnOff->Kode}}">
                                            <div class="form-group">
                                                <input type="text" class="form-control text-center"
                                                    value="{{$MobileBelanjaOnOff->Nama}}" name="inputmblbelanjaonoff">
                                            </div>
                                            <div class="form-group text-center">
                                                <div class="custom-control custom-switch custom-switch-md">
                                                    <input type="checkbox" class="custom-control-input belanjaOnline1"
                                                        name="ckmblbelanjaonoff" id="belanjaOnline1"
                                                        @if($MobileBelanjaOnOff->aktif == 1 ) checked @endif>
                                                    <label class="custom-control-label" for="belanjaOnline1"></label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card text-white bg-dark rounded" style="height: 14rem">
                                        <div class="card-body">
                                            <input type="hidden" name="name2" value="{{$MobileBelanjaJamOnOff->Kode}}">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="text" value="{{$MobileBelanjaJamOnOff->Nama}}"
                                                            class="text-center form-control" name="inputmblbelanjajam">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group text-center">
                                                        <div class="custom-control custom-switch custom-switch-md ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                @if($MobileBelanjaJamOnOff->aktif == 1 ) checked @endif
                                                            id="belanjaOnline2" name="ckmblbelanjajam">
                                                            <label class="custom-control-label"
                                                                for="belanjaOnline2"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="time" id="inputmblbelanjajamon" class="text-center form-control"
                                                            value="{{$MobileBelanjaJamOnOff->MobileTimeOn}}"
                                                            name="inputmblbelanjajamon">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="time" class="text-center form-control"
                                                          id="inputmblbelanjajamoff"  value="{{$MobileBelanjaJamOnOff->MobileTimeOff}}"
                                                            name="inputmblbelanjajamoff">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card text-white bg-dark rounded" style="height: 14rem">
                                        <div class="card-body cardpengajuanday">
                                            <input type="hidden" name="name3" value="{{$MobilePengajuanfirst->Kode}}">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="text" class="text-center form-control"
                                                            value="{{$MobilePengajuanfirst->Nama}}"
                                                            name="inputmblpengajuan">
                                                    </div>
                                                </div>
                                            </div>
                                            @forelse ($MobilePengajuanDay as $item)
                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <select class="form-control" id="pengajuanday"
                                                        name="pengajuanday[]">
                                                        <option value="0">Pilih Tanggal</option>
                                                        @for ($x = 1; $x <= 31; $x++ )
                                                        <option value="{{$x}}" @if($item->Nilai == $x) selected @endif>{{$x}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group text-center">
                                                        <div class="custom-control custom-switch custom-switch-md float-right">
                                                            <input type="hidden" name="pengajuandayid[]" value="{{$item->id}}">
                                                            <input type="checkbox" class="custom-control-input"
                                                                name="ckmblpengajuanday[]"
                                                                id="MobilePengajuanDay{{$item->id}}"
                                                                data-id="{{$item->id}}" data-nilai={{$item->Nilai}}
                                                                @if($item->aktif == 1 ) checked
                                                            @endif>
                                                            <label class="custom-control-label"
                                                                for="MobilePengajuanDay{{$item->id}}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @empty

                                            @endforelse


                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card text-white bg-dark rounded" style="height: 14rem">
                                        <div class="card-body">
                                            <input type="hidden" name="name4" value="{{$MobilePengajuanMaksAnggota->Kode}}">
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="text-center form-control"
                                                            value="{{$MobilePengajuanMaksAnggota->Nama}}"
                                                            name="inputmblpengajuanmaksanggotanama">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" class="text-center form-control"
                                                        value="{{$MobilePengajuanMaksAnggota->Nilai}}"
                                                        name="inputmblpengajuanmaksanggotanilai">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch custom-switch-md float-right">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="MobilePengajuanMaksAnggota"
                                                            @if($MobilePengajuanMaksAnggota->aktif == 1 ) checked
                                                        @endif name="ckmblpengajuanmaksanggota">
                                                        <label class="custom-control-label"
                                                            for="MobilePengajuanMaksAnggota"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="name5" value="{{$MobileInfoPinjamanMulaiDari->Kode}}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-group">

                                                            <textarea class="form-control"
                                                                id="exampleFormControlTextarea1" rows="6"
                                                                name="inputmblinfopinjamannama">{{$MobileInfoPinjamanMulaiDari->Nama}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" class="text-center form-control"
                                                        value="{{$MobileInfoPinjamanMulaiDari->Nilai}}"
                                                        name="inputmblinfopinjamannilai">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch custom-switch-md float-right">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="MobileInfoPinjamanMulaiDari" name="ckmblinfopinjaman"
                                                            @if($MobileInfoPinjamanMulaiDari->aktif == 1 ) checked
                                                        @endif>
                                                        <label class="custom-control-label"
                                                            for="MobileInfoPinjamanMulaiDari"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="card text-white bg-dark rounded" style="height: 18rem">
                                        <div class="card-body">
                                            <div class="row mt-4">
                                                <input type="hidden" name="name6" value="{{$SaldoMinusMax->Kode}}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="text-center form-control"
                                                            value="{{$SaldoMinusMax->Nama}}"
                                                            name="inputmblSaldoMinusMaxnama">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" class="text-center form-control"
                                                        value="{{$SaldoMinusMax->Nilai}}"
                                                        name="inputmblSaldoMinusMaxnilai">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch custom-switch-md float-right">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="MobileSaldoMinusMax"
                                                            @if($SaldoMinusMax->aktif == 1 ) checked
                                                        @endif name="ckmblSaldoMinusMax">
                                                        <label class="custom-control-label"
                                                            for="MobileSaldoMinusMax"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="name7" value="{{$SaldoMinusBunga->Kode}}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-group">

                                                            <input type="text" class="text-center form-control"
                                                            value="{{$SaldoMinusBunga->Nama}}"
                                                            name="inputmblSaldoMinusBunganama">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" class="text-center form-control"
                                                        value="{{$SaldoMinusBunga->Nilai}}"
                                                        name="inputSaldoMinusBunganilai">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch custom-switch-md float-right">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="SaldoMinusBunga" name="ckSaldoMinusBunga"
                                                            @if($SaldoMinusBunga->aktif == 1 ) checked
                                                        @endif>
                                                        <label class="custom-control-label"
                                                            for="SaldoMinusBunga"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="name8" value="{{$SaldoMinusResetPerBulan->Kode}}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-group">

                                                            <textarea class="form-control"
                                                                id="exampleFormControlTextarea1" rows="6"
                                                                name="inputSaldoMinusResetPerBulannama">{{$SaldoMinusResetPerBulan->Nama}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="custom-control custom-switch custom-switch-md float-right">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="SaldoMinusResetPerBulan" name="ckSaldoMinusResetPerBulan"
                                                            @if($SaldoMinusResetPerBulan->aktif == 1 ) checked
                                                        @endif>
                                                        <label class="custom-control-label"
                                                            for="SaldoMinusResetPerBulan"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card text-white bg-dark rounded" style="height: 18rem">
                                        <div class="card-body">
                                            <div class="row mt-4">
                                                <input type="hidden" name="name9" value="{{$PajakPenjualan->Kode}}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="text-center form-control"
                                                            value="{{$PajakPenjualan->Nama}}"
                                                            name="inputmblPajakPenjualannama">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" class="text-center form-control"
                                                        value="{{$PajakPenjualan->Nilai}}"
                                                        name="inputmblPajakPenjualannilai">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch custom-switch-md float-right">
                                                        <input type="checkbox" class="custom-control-input"
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

                                                            <input type="text" class="text-center form-control"
                                                            value="{{$DiskonRpPenjualanReadOnly->Nama}}"
                                                            name="inputmblDiskonRpPenjualanReadOnlynama">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="custom-control custom-switch custom-switch-md float-right">
                                                        <input type="checkbox" class="custom-control-input"
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
                                                                id="exampleFormControlTextarea1" rows="6"
                                                                name="inputDiskonPersenPenjualanReadOnlynama">{{$DiskonPersenPenjualanReadOnly->Nama}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="custom-control custom-switch custom-switch-md float-right">
                                                        <input type="checkbox" class="custom-control-input"
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
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card text-white bg-dark rounded" style="height: 14rem">
                                        <div class="card-body">
                                            <input type="hidden" name="name12" value="{{$cetak->Kode}}">
                                            <div class="form-group">
                                                <input type="text" class="form-control text-center"
                                                    value="{{$cetak->Nama}}" readonly name="inputcetak">
                                            </div>
                                            <div class="form-group text-center">
                                                <div class="custom-control custom-switch custom-switch-md">
                                                    <input type="checkbox" class="custom-control-input cetak"
                                                        name="ckcetak" id="cetak"
                                                        @if($cetak->aktif == 1 ) checked @endif>
                                                    <label class="custom-control-label" for="cetak"></label>
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
