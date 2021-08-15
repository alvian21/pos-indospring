@extends('frontend.master')

@section('title', 'Aktivasi e-kop
')

@section('transaksi', 'active')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
<link rel="stylesheet" href="{{asset('wizard/css/gsdk-bootstrap-wizard.css')}}">
<style>
    * {
        margin: 0;
        padding: 0
    }

    html {
        height: 100%
    }

    #msform {
        text-align: center;
        position: relative;
        margin-top: 20px
    }

    #msform fieldset .form-card {
        background: white;
        border: 0 none;
        border-radius: 0px;
        box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
        padding: 20px 40px 30px 40px;
        box-sizing: border-box;
        width: 94%;
        margin: 0 3% 20px 3%;
        position: relative
    }

    #msform fieldset {
        background: white;
        border: 0 none;
        border-radius: 0.5rem;
        box-sizing: border-box;
        width: 100%;
        margin: 0;
        padding-bottom: 20px;
        position: relative
    }

    #msform fieldset:not(:first-of-type) {
        display: none
    }

    #msform fieldset .form-card {
        text-align: left;
        color: #9E9E9E
    }

    #msform input,
    #msform textarea {
        padding: 0px 8px 4px 8px;
        border: none;
        border-bottom: 1px solid #ccc;
        border-radius: 0px;
        margin-bottom: 25px;
        margin-top: 2px;
        width: 100%;
        box-sizing: border-box;
        font-family: montserrat;
        color: #2C3E50;
        font-size: 16px;
        letter-spacing: 1px
    }


    #msform .action-button {
        width: 250px;
        background: skyblue;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px
    }

    #msform .action-button:hover,
    #msform .action-button:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
    }

    #msform .action-button-previous {
        width: 100px;
        background: #616161;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px
    }

    #msform .action-button-previous:hover,
    #msform .action-button-previous:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
    }

    select.list-dt {
        border: none;
        outline: 0;
        border-bottom: 1px solid #ccc;
        padding: 2px 5px 3px 5px;
        margin: 2px
    }

    select.list-dt:focus {
        border-bottom: 2px solid skyblue
    }

    .card {
        z-index: 0;
        border: none;
        border-radius: 0.5rem;
        position: relative
    }

    .fs-title {
        font-size: 25px;
        color: #2C3E50;
        margin-bottom: 10px;
        font-weight: bold;
        text-align: left
    }

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey;
        margin-left: 20%;
    }

    #progressbar .active {
        color: #000000
    }

    #progressbar li {
        list-style-type: none;
        font-size: 12px;
        width: 25%;
        float: left;
        position: relative;

    }

    #progressbar #account:before {
        font-family: FontAwesome;
        content: "\f023"
    }

    #progressbar #personal:before {
        font-family: FontAwesome;
        content: "\f007"
    }

    #progressbar #payment:before {
        font-family: FontAwesome;
        content: "\f09d"
    }

    #progressbar #confirm:before {
        font-family: FontAwesome;
        content: "\f00c"
    }

    #progressbar li:before {
        width: 50px;
        height: 50px;
        line-height: 45px;
        display: block;
        font-size: 18px;
        color: #ffffff;
        background: lightgray;
        border-radius: 50%;
        margin: 0 auto 10px auto;
        padding: 2px
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: 0;
        top: 25px;
        z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: skyblue
    }

    .radio-group {
        position: relative;
        margin-bottom: 25px
    }

    .radio {
        display: inline-block;
        width: 204;
        height: 104;
        border-radius: 0;
        background: lightblue;
        box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
        box-sizing: border-box;
        cursor: pointer;
        margin: 8px 2px
    }

    .radio:hover {
        box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
    }

    .radio.selected {
        box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
    }

    .fit-image {
        width: 100%;
        object-fit: cover
    }
</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Aktivasi e-kop
        </h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-10">
                <div class="card card-dark">

                    <div class="card-body">
                        @include('frontend.include.alert')

                        <div class="row justify-content-center" style="margin-top:-50px">
                            <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-3 mb-2">
                                <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                                    <h3><strong>Aktivasi e-kop</strong></h3>
                                    <p>Fill all form field to go to next step</p>
                                    <div class="row">
                                        <div class="col-md-12 mx-0">
                                            <form id="msform">
                                                <!-- progressbar -->
                                                <ul id="progressbar" class="text-center">
                                                    <li class="active" id="account"><strong>Account</strong></li>
                                                    <li id="personal"><strong>Aktivasi</strong></li>

                                                    <li id="confirm"><strong>Finish</strong></li>
                                                </ul> <!-- fieldsets -->
                                                <fieldset>
                                                    <div class="form-card">
                                                        <div id="data-alert"></div>
                                                        <h2 class="fs-title">Account Information</h2>
                                                        <div class="form-group">
                                                            <label for="barcode">Barcode Karyawan</label>
                                                            <select class="form-control" id="barcode">
                                                                <option value="">Pilih Barcode Karyawan</option>
                                                                @forelse ($anggota as $item)
                                                                <option value="{{$item->Kode}}">{{$item->Kode}} |
                                                                    {{$item->Nama}}</option>

                                                                @empty

                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="data" id="data" class="data"
                                                        value="account">
                                                    <input type="hidden" name="utama" id="utama" class="utama"
                                                        value="account">
                                                    <input type="button" name="next"
                                                        class="next action-button btn btn-primary" value="Next Step" />
                                                </fieldset>
                                                <fieldset>
                                                    <div class="form-card">
                                                        <div id="data-alert-scan"></div>
                                                        <h2 class="fs-title">Scan NFC</h2>
                                                        <div class="form-group">
                                                            <label for="kode_nfc">Scan NFC / Kartu eKop</label>
                                                            <input type="text" class="form-control kode_nfc"
                                                                name="kode_nfc" id="kode_nfc">
                                                        </div>
                                                    </div>
                                                    {{-- <input type="button" name="previous"
                                                        class="previous action-button-previous" value="Previous" /> --}}
                                                    <input type="hidden" name="data" id="data" class="data"
                                                        value="personal">
                                                    <input type="hidden" name="data" id="dataaktivasi"
                                                        class="dataaktivasi" value="aktivasi">
                                                    <input type="button" name="next" class="next action-button"
                                                        value="Next Step" />
                                                </fieldset>

                                                <fieldset>
                                                    <div class="form-card">
                                                        <input type="hidden" name="data" id="datalabel" value="finish">
                                                        <h2 class="fs-title text-center">Success !</h2> <br><br>
                                                        <div class="row justify-content-center">
                                                            <div class="col-3"> <img
                                                                    src="https://img.icons8.com/color/96/000000/ok--v2.png"
                                                                    class="fit-image"> </div>
                                                        </div> <br><br>
                                                        <div class="row justify-content-center">
                                                            <div class="col-7 text-center">
                                                                <h5>Kartu eKop anda telah Aktif</h5>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <input type="hidden" name="data" id="data" class="data"
                                                        value="finish">

                                                    <input type="button" name="next" class="next action-button"
                                                        value="Kembali Ke menu utama" />
                                                </fieldset>
                                            </form>
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
<script src="{{asset('wizard/js/jquery.bootstrap.wizard.js')}}"></script>
<script src="{{asset('wizard/js/gsdk-bootstrap-wizard.js')}}"></script>
<script src="{{asset('wizard/js/jquery.validate.min.js')}}"></script>

<script>
    $(document).ready(function(){

    var current_fs, next_fs, previous_fs, finish, datanext; //fieldsets
    var opacity;
    var status;
    $('#barcode').select2()

    function ajax()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    $(".next").click(function(){

        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        var data = current_fs.find('.data')
        var id = $('#barcode').find(':selected').val()
        if(data.val() == 'account'){
            $.ajax({
                url:"{{route('koperasi.aktivasi.index')}}",
                method:"GET",
                async:false,
                data:{
                    'barcode':id
                }
            }).done(function (response) {
                    if(response.status){
                        status = response.datastatus
                    }else{
                        $('#data-alert').html(response.data)
                        return false;
                    }
             })

             if(status){
                swal({
                        text: "Ada kartu yang sudah teregistrasi, Apakah akan mengganti dengan kartu baru!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willUpdate) => {
                    if (willUpdate) {
                        $('#data-alert').empty()
                        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                        $('.kode_nfc').select();
                        //show the next fieldset
                        next_fs.show();
                        //hide the current fieldset with style
                        current_fs.animate({opacity: 0}, {
                            step: function(now) {
                            // for making fielset appear animation
                            opacity = 1 - now;

                            current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                            });
                            next_fs.css({'opacity': opacity});
                            },
                        duration: 600
                        });
                    }
                    });
             }else{
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                        $('.kode_nfc').select();
                        //show the next fieldset
                        next_fs.show();
                        //hide the current fieldset with style
                        current_fs.animate({opacity: 0}, {
                            step: function(now) {
                            // for making fielset appear animation
                            opacity = 1 - now;

                            current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                            });
                            next_fs.css({'opacity': opacity});
                            },
                        duration: 600
                        });
             }
        }else if(data.val()=='personal'){
            var kode_nfc = $('#kode_nfc').val()
            ajax()
            $.ajax({
                url:"{{route('koperasi.aktivasi.store')}}",
                method:"POST",
                data:{
                    'barcode':id,
                    'kode_nfc':kode_nfc
                }
            }).done(function(response){
                    if(response.status){
                        $('#kode_nfc').val('')
                        $('#barcode').val('').change()
                        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                        $('#data-alert-scan').empty()
                        //show the next fieldset
                            next_fs.show();
                            //hide the current fieldset with style
                            current_fs.animate({opacity: 0}, {
                                step: function(now) {
                                // for making fielset appear animation
                                opacity = 1 - now;

                                current_fs.css({
                                'display': 'none',
                                'position': 'relative'
                                });
                                next_fs.css({'opacity': opacity});
                                },
                            duration: 600
                        });
                    }else{
                        $('#data-alert-scan').html(response.data)
                    }
            })
        }else if(data.val() == 'finish'){
            var utama = $('.utama').parent()
            var aktivasi = parseInt(utama) + 1;
            var finish = parseInt(aktivasi) + 1;
                 $('#confirm').removeClass("active")
                 $("#progressbar li").eq($("fieldset").index(utama)).addClass("active");
                 $("#progressbar li").eq($("fieldset").index(aktivasi)).removeClass("active");
                 $("#progressbar li").eq($("fieldset").index(finish)).removeClass("active");
                        //show the next fieldset
                            utama.show();
                            //hide the current fieldset with style
                            current_fs.animate({opacity: 0}, {
                                step: function(now) {
                                // for making fielset appear animation
                                opacity = 1 - now;

                                current_fs.css({
                                'display': 'none',
                                'position': 'relative'
                                });
                                utama.css({'opacity': opacity});
                                },
                            duration: 600
                        });
        }

        // $("#progressbar li").eq($("fieldset").index(next_fs-1)).addClass("active");

    });

    $(".previous").click(function(){

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //Remove class active
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();

        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
        step: function(now) {
        // for making fielset appear animation
        opacity = 1 - now;

        current_fs.css({
        'display': 'none',
        'position': 'relative'
        });
        previous_fs.css({'opacity': opacity});
        },
        duration: 600
        });
    });

    $('.radio-group .radio').click(function(){
        $(this).parent().find('.radio').removeClass('selected');
        $(this).addClass('selected');
    });

    $(".submit").click(function(){
         return false;
    })

});
</script>
@endsection
