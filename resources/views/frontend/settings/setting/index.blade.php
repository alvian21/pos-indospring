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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card text-white bg-dark rounded" style="height: 14rem">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input type="text" class="form-control text-center"
                                                value="{{$MobileBelanjaOnOff->Nama}}" readonly>
                                        </div>
                                        <div class="form-group text-center">
                                            <div class="custom-control custom-switch custom-switch-md">
                                                <input type="checkbox" class="custom-control-input belanjaOnline1"
                                                    value="{{$MobileBelanjaOnOff->Kode}}" id="belanjaOnline1"
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" value="{{$MobileBelanjaJamOnOff->Nama}}"
                                                        class="text-center form-control" readonly>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group text-center">
                                                    <div class="custom-control custom-switch custom-switch-md">
                                                        <input type="checkbox" class="custom-control-input"
                                                            value="{{$MobileBelanjaJamOnOff->Kode}}"
                                                            @if($MobileBelanjaJamOnOff->aktif == 1 ) checked @endif
                                                        id="belanjaOnline2">
                                                        <label class="custom-control-label"
                                                            for="belanjaOnline2"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="text-center form-control"
                                                        value="Buka : {{$MobileBelanjaJamOnOff->MobileTimeOn}}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="text-center form-control"
                                                        value="Tutup : {{$MobileBelanjaJamOnOff->MobileTimeOff}}"
                                                        readonly>
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" class="text-center form-control" value="{{$MobilePengajuanfirst->Nama}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        @forelse ($MobilePengajuanDay as $item)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="text-center form-control" value="Tanggal : {{$item->Nilai}}" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group text-center">
                                                    <div class="custom-control custom-switch custom-switch-md">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="MobilePengajuanDay{{$loop->iteration}}" data-id="{{$item->id}}" data-nilai={{$item->Nilai}} value="{{$item->Kode}}" @if($item->aktif == 1 ) checked @endif>
                                                        <label class="custom-control-label" for="MobilePengajuanDay{{$loop->iteration}}"></label>
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
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="text-center form-control" value="{{$MobilePengajuanMaksAnggota->Nama}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="text-center form-control" value="{{$MobilePengajuanMaksAnggota->Nilai}}" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-switch custom-switch-md">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="MobilePengajuanMaksAnggota" value="{{$MobilePengajuanMaksAnggota->Kode}}" @if($MobilePengajuanMaksAnggota->aktif == 1 ) checked @endif>
                                                    <label class="custom-control-label" for="MobilePengajuanMaksAnggota"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-group">

                                                        <textarea class="form-control" readonly id="exampleFormControlTextarea1" rows="6">{{$MobileInfoPinjamanMulaiDari->Nama}}</textarea>
                                                      </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="text-center form-control" readonly value="{{$MobileInfoPinjamanMulaiDari->Nilai}}">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-switch custom-switch-md">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="MobileInfoPinjamanMulaiDari" value="{{$MobileInfoPinjamanMulaiDari->Kode}}" @if($MobileInfoPinjamanMulaiDari->aktif == 1 ) checked @endif>
                                                    <label class="custom-control-label" for="MobileInfoPinjamanMulaiDari"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

        function ajax() {
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
         }

        $(document).on('change',"input[type='checkbox']", function(e){
          var aktif;
          var cek = $(this).prop('checked');
          var kode = $(this).val();
          var nilai = $(this).data('nilai');
          var id = $(this).data('id');
          if(cek){
            aktif =1;
          }else{
            aktif =0;
          }

          ajax()
          $.ajax({
              url:"{{route('settings.mssetting.store')}}",
              method:"POST",
              data:{
                  'kode':kode,
                  'aktif':aktif,
                  'nilai':nilai,
                  'id':id
              }
          })
        })
    })
</script>
@endsection
