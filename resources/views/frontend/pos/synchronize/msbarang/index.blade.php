@extends('frontend.master')

@section('title', 'Laporan Realtime Stok')

@section('poslaporan', 'active')


@section('content')

<section class="section">
    <div class="section-header">
        <h1>Synchronize Master Barang</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Synchronize Master Barang</h4>
                    </div>
                    <div class="card-body  justify-content-center align-items-center">
                        @include('frontend.include.alert')

                        <form method="POST" >
                            @csrf
                            {{-- <div class="form-group">
                                <label for="tangal">Tanggal</label>
                                <input type="date" class="form-control" id="tangal" name="tanggal">

                            </div> --}}

                            <div class="row">
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

        function ajax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        $('.btnsync').on('click', function(){

                $(this).prop("disabled", true);
                // add spinner to button
                $(this).html(
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
                );
                ajax()
                $.ajax({
                    url:"{{route('synchronize.msbarang.store')}}",
                    method:"POST",
                }).done(function (response) {

                    if(response.status){
                        swal("Success!", response.message, "success");
                        $('.spinner-border').remove()
                        $('.btnsync').prop("disabled", false);
                        $('.btnsync').text('Sync Now')
                    }
                })

        })
     })
</script>
@endsection
