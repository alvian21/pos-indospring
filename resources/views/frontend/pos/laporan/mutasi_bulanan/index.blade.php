@extends('frontend.master')

@section('title', 'Laporan Mutasi Bulanan')

@section('poslaporan', 'active')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Laporan Mutasi Bulanan</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-6">
                    <div class="card card-dark">
                        <div class="card-header container-fluid d-flex justify-content-between">
                            <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Laporan Mutasi Bulanan</h4>
                        </div>
                        <div class="card-body">
                            @include('frontend.include.alert')
                            <form method="POST" action="{{ route('poslaporan.mutasibulanan.store') }}" id="formMutasi">
                                @csrf
                                <div class="form-group">
                                    <label for="periode">Periode</label>
                                    <input type="month" class="form-control" id="periode" required name="periode">
                                </div>
                                <div class="form-group">
                                    <label for="lokasi">Lokasi</label>
                                    <select class="form-control" id="lokasi" name="lokasi">
                                        {{-- <option value="Semua">Semua</option> --}}
                                        @foreach ($mslokasi as $item)
                                            <option value="{{ $item->Kode }}">{{ $item->Kode }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="cetak">Cetak</label>
                                    <select class="form-control" id="cetak" name="cetak">
                                        {{-- <option value="pdf">Pdf</option> --}}
                                        <option value="excel">Excel</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" id="btnCetak" class="btn btn-primary">Cetak</button>
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
        $(document).ready(function() {

            $('#lokasi').select2()
            $('#cetak').select2()

            $('#formMutasi').submit(function(e) {
                e.preventDefault()
                $('#btnCetak').prop('disabled', true)
                $('#btnCetak').html(
                    `<div class="data-spinner" > <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading... </div>`
                );

                var action = $(this).attr('action')
                var form = $(this).serialize()
                csrf_ajax()
                $.ajax({
                    url: action,
                    method: "POST",
                    data: form,
                    success: function(response, status, xhr) {
                        // console.log(response);

                        if (response.status === false) {
                            tampil_alert(response.data, 'error')
                            $('.data-spinner').remove()
                            $('#btnCetak').prop('disabled', false)
                            $('#btnCetak').text('Cetak')
                        } else {
                            var disposition = xhr.getResponseHeader('content-disposition');
                            var matches = /"([^"]*)"/.exec(disposition);
                            var filename = (matches != null && matches[1] ? matches[1] :
                                'laporan-mutasi-bulanan.xlsx');

                            // The actual download
                            var blob = new Blob([response], {
                                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            });
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = filename;

                            document.body.appendChild(link);

                            link.click();
                            document.body.removeChild(link);
                            $('.data-spinner').remove()
                            $('#btnCetak').prop('disabled', false)
                            $('#btnCetak').text('Cetak')
                        }
                    }
                })
            })
        })
    </script>
@endsection
