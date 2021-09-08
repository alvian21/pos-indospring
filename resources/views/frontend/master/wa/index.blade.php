@extends('frontend.master')

@section('title', 'Master | Test Wa')

@section('wa', 'active')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Test Wa</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-6">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Test Wa</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form method="POST" action="{{route('master.wa.store')}}" >
                            @csrf
                            <div class="form-group">
                                <label for="nomor_wa">Nomor Wa</label>
                                <input type="text" class="form-control" id="nomor_wa" name="nomor_wa" required placeholder="6287865091234">
                            </div>

                            <div class="form-group">
                                <label for="opsi">Opsi</label>
                                <select class="form-control" id="opsi" name="opsi">
                                    <option value="otp">Otp</option>
                                    <option value="text">Text</option>

                                </select>
                            </div>

                            <div class="form-group" id="textarea">
                                <label for="text">Text</label>
                                <textarea class="form-control" id="text" name="text" rows="3"></textarea>
                              </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary">Proses</button>
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
            $('#textarea').hide()

            $('#opsi').on('change', function () {
                var opsi = $(this).val();

                if(opsi == 'text'){
                    $('#textarea').show()
                    $('#text').prop('required',true)
                }else{
                    $('#textarea').hide()
                    $('#text').prop('required',false)
                }
             })
     })
</script>
@endsection
