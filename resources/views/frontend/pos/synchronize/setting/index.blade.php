@extends('frontend.master')

@section('title', 'Synchronize Setting')

@section('synchronize', 'active')


@section('content')

<section class="section">
    <div class="section-header">
        <h1>Synchronize Setting</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Synchronize Setting</h4>
                    </div>
                    <div class="card-body  justify-content-center align-items-center">
                        @include('frontend.include.alert')

                        <form method="POST">
                            @csrf

                            <div class="row mt-2">
                                <div class="col-md-12 text-center">
                                    <button type="button" id="btnsync" class="btn btn-primary btnsync">Sync Now</button>
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

        $('#kategori').select2()
        $('#cetak').select2()
        $('.progress').hide()
        var waktu;
        function ajax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        $('.btnsync').on('click', function(){
                 $('.progress').show()
                $(this).prop("disabled", true);
                // add spinner to button
                $(this).html(
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...`
                );

                ajax()
                $.ajax({
                    url:"{{route('synchronize.setting.store')}}",
                    method:"POST",
                }).done(function (response) {

                    if(response.status){
                        swal("Success!", response.message, "success");
                        $('.spinner-border').remove()
                        $('.btnsync').prop("disabled", false);
                        $('.btnsync').text('Sync Now')
                        // $('.progress').hide()
                        if(waktu != undefined){
                           setTimeout(function () {  clearTimeout(waktu) },10000)
                        }
                    }else{
                        swal(response.message);
                    }
                })


        })
     })
</script>
@endsection
