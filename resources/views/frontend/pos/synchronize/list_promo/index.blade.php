@extends('frontend.master')

@section('title', 'Synchronize List Promo')

@section('synchronize', 'active')


@section('content')

<section class="section">
    <div class="section-header">
        <h1>Synchronize List Promo</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Synchronize List Promo</h4>
                    </div>
                    <div class="card-body  justify-content-center align-items-center">
                        @include('frontend.include.alert')

                        <form method="POST">
                            @csrf
                            <div class="row mt-2">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary btnsync">Sync Now</button>
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
        function ajax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        function getProgress(tanggal)
        {
            ajax()
           $.ajax({
                url:"{{route('synchronize.listpromo.store')}}",
                method:"POST",
                async: true,
                data:{'status':'progress','tanggal':tanggal},
                success:function(response){
                    if(response.status){
                            $('.progress').html(response.data)
                    }
                }
            })
        }


        $('.btnsync').on('click', function(){

                $(this).prop("disabled", true);
                // add spinner to button
                $(this).html(
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...`
                );
                $('.progress').show()
                ajax()
                $.ajax({
                    url:"{{route('synchronize.listpromo.store')}}",
                    method:"POST",
                }).done(function (response) {
                    if(response.status){
                        swal("Success!", response.message, "success");
                        $('.spinner-border').remove()
                        $('.btnsync').prop("disabled", false);
                        $('.btnsync').text('Sync Now')
                    }else{
                        $('.spinner-border').remove()
                        $('.btnsync').prop("disabled", false);
                        $('.btnsync').text('Sync Now')
                        swal("Maaf ada yang error");
                    }
                })


        })
     })
</script>
@endsection
