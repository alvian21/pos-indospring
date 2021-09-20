@extends('frontend.master')

@section('title', 'Trpinjaman')
@section('transaksi', 'active')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Daftar Pinjaman</h1>

    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Daftar Pinjaman</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')

                        <table border="0" cellspacing="5" cellpadding="5" class="mb-3">
                            <tbody>
                                <tr>
                                    <td colspan="2"><b>Filter Periode :</b> </td>
                                </tr>
                                <tr class=" input-daterange">
                                    <td>Minimum date:</td>
                                    <td> <input type="text" id="min" class="form-control" data-date-format="d M yyyy" >
                                    </td>
                                    <td>Maximum date:</td>
                                    <td> <input type="text" id="max" class="form-control" data-date-format="d M yyyy">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="table-responsive">
                            <table class="table table-striped display nowrap" id="table-1">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Nomor</th>
                                        <th rowspan="2">Kode Anggota</th>
                                        <th rowspan="2">Nama</th>
                                        <th rowspan="2">Bagian</th>
                                        <th rowspan="2">Pinjaman</th>
                                        <th rowspan="2">Cicilan Total</th>
                                        <th rowspan="2">Berapa Kali Bayar</th>
                                        <th rowspan="2">Alasan</th>
                                        <th rowspan="2">Tanggal Pengajuan</th>
                                        <th rowspan="2">Approval Status</th>
                                        <th colspan="3" class="text-center">Petugas</th>
                                        <th colspan="3" class="text-center">Diketahui</th>
                                        <th colspan="3" class="text-center">Disetujui</th>
                                        {{-- <th rowspan="2">Kartu Jamsostek</th>
                                        <th rowspan="2">Saldo Jamsostek</th>
                                        <th rowspan="2">Jaminan Tambahan</th> --}}
                                        <th rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th>Petugas Nama</th>
                                        <th>Petugas Note</th>
                                        <th>Petugas Date</th>
                                        <th>Diketahui Nama</th>
                                        <th>Diketahui Note</th>
                                        <th>Diketahui Date</th>
                                        <th>Approval Nama</th>
                                        <th>Approval Note</th>
                                        <th>Approval Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trpinjaman as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->Nomor }}</td>
                                        <td>{{ $item->KodeAnggota }}</td>
                                        <td>{{ $item->Nama }}</td>
                                        <td>{{ $item->SubDept }}</td>
                                        <td>{{ str_replace(',','.', number_format($item->PengajuanPinjaman)) }}</td>
                                        <td>{{ str_replace(',','.', number_format($item->CicilanTotal)) }}</td>
                                        <td>{{ $item->BerapaKaliBayar }}</td>
                                        <td>{{ $item->Alasan }}</td>
                                        <td>{{ date('d M y',strtotime( $item->TanggalPengajuan)) }}</td>
                                        <td>{{ $item->ApprovalStatus }}</td>
                                        <td>{{ $item->PetugasNama }}</td>
                                        <td>{{ $item->PetugasNote }}</td>
                                        <td>{{ ($item->PetugasDate != null && date('Y',strtotime( $item->PetugasDate)) != '1970') ? date('d M y',strtotime( $item->PetugasDate)) : '' }}
                                        </td>
                                        <td>{{ $item->DiketahuiNama }}</td>
                                        <td>{{ $item->DiketahuiNote }}</td>
                                        <td>{{ ($item->DiketahuiDate != null && date('Y',strtotime( $item->DiketahuiDate)) != '1970') ? date('d M y',strtotime( $item->DiketahuiDate)) : '' }}
                                        </td>
                                        <td>{{ $item->ApprovalNama }}</td>
                                        <td>{{ $item->ApprovalNote }}</td>
                                        <td>{{ ($item->ApprovalDate !=null && date('Y',strtotime( $item->ApprovalDate)) != '1970') ? date('d M y',strtotime( $item->ApprovalDate)) : '' }}
                                        </td>
                                        {{-- <td> <button data-id="{{$item->Nomor}}"
                                                data-image="{{asset('storage/images/jaminan1/'.$item->Jaminan1)}}"
                                                data-cek="@if($item->Jaminan1!=null) {{$item->Jaminan1}} @endif"
                                                data-level="{{$item->level}}" data-title="Kartu Jamsostek"
                                                class="btn btn-primary btnkartu">@if($item->Jaminan1!=null) Kartu
                                                Jamsostek @else Belum
                                                di upload @endif</button>
                                        </td>
                                        <td> <button data-id="{{$item->Nomor}}"
                                                data-image="{{asset('storage/images/jaminan2/'.$item->Jaminan2)}}"
                                                data-cek="@if($item->Jaminan2!=null) {{$item->Jaminan2}} @endif"
                                                data-level="{{$item->level}}" data-title="Saldo Jamsostek"
                                                class="btn btn-primary btnsaldo">@if($item->Jaminan2!=null) Saldo
                                                Jamsostek @else Belum
                                                di upload @endif</button>
                                        </td>
                                        <td><button data-id="{{$item->Nomor}}"
                                                data-image="{{asset('storage/images/jaminan_resmi/'.$item->JaminanResmi)}}"
                                                data-cek="@if($item->JaminanResmi!=null) {{$item->JaminanResmi}} @endif"
                                                data-level="{{$item->level}}" data-title="Jaminan Tambahan"
                                                class="btn btn-primary btntambahan">@if($item->JaminanResmi!=null)
                                                Jaminan Tambahan @else
                                                Belum
                                                di upload @endif</button></td> --}}
                                        <td><button type="button" data-id="{{ $item->Nomor }}"
                                                class="btn btn-info btnedit">Edit</button>
                                            {{-- <button type="button" data-id="{{ $item->Nomor }}" class=" btn
                                            btn-danger btndelete">Delete</button> --}}
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="editform" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pinjaman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ method_field('PUT') }}
                    @csrf
                    <input type="hidden" name="nomor" id="nomor">
                    <div class="form-group status_edit_div">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status_edit">
                            <option value="1" selected>Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                    <div class="form-group noteform">
                        <label for="note">Note</label>
                        <textarea class="form-control" id="note" rows="5" name="note"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button value="save" type="button" class="btn btn-primary" id="updateModal">Save Changes</button>
                </div>
        </form>
    </div>
</div>
</div>

<!-- Modal Image-->
{{-- <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Modal Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data" method="POST" action="{{route('update_pinjaman')}}">
                @csrf
                {{method_field("PUT")}}
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <img alt="image" class="img-thumbnail" id="previewimage">
                    <div class="form-group mt-3 formimage">
                        <label for="image">Upload Image</label>
                        <input type="file" class="form-control" id="image" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btnupdateimage">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/datetime/1.0.3/js/dataTables.dateTime.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
    integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ=="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
    integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
    crossorigin="anonymous"></script>
<script>
    var minDate, maxDate;
    function form_submit() {
            document.getElementById("editform").submit();
     }

     function readURLImage(input) {
                    jQuery('#previewimage').attr('src', input);
     }

     $.fn.dataTable.ext.search.push(
     function( settings, data, dataIndex ) {
        var min = $('#min').val();
        var max = $('#max').val();
        var parseDate = moment(data[9]).format('YYYY/MM/DD')
        var date = new Date( parseDate );
        if (
            ( min == "" || max == "" )
                ||
                ( moment(parseDate).isSameOrAfter(min) && moment(parseDate).isSameOrBefore(max) )
        ) {
            return true;
        }
        return false;
    }
    );

    $(document).ready(function () {
        var date = new Date();
        var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        $('.input-daterange input').each(function() {
            $(this).datepicker('clearDates');

        });

        $('#min').datepicker('setDate',firstDay);
        $('#max').datepicker('setDate',lastDay);

        var table = $("#table-1").DataTable({
            "scrollX": true,
         });

         table.draw();
        $('#min, #max').on('change', function () {
            table.draw();
        })

    $(document).on('click',".btnedit", function () {
        var nomor = $(this).data('id');
        var url = "{{ url('/admin/pinjaman/') }}/" + nomor;
        $('#editform').attr("action", url);
        $("#nomor").val(nomor);
        $("#editModal").modal('show');

     });

     $(document).ready(function(){
        $(".noteform").hide();
     });

     $('#editModal').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                $(".noteform").hide();

    });


     $("#status_edit").on('change', function(){
         var status = $(this).val();
         if(status == 1){
            $(".noteform").hide();
         }else{
            $(".noteform").show();
         }
     });

     $('#updateModal').on('click', function(){
        form_submit();
     });

     //image modal and form

     $('#imageModal').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                $("#previewimage").attr('src',null);

    });


     $('.btnkartu, .btnsaldo, .btntambahan').on('click', function(){
         var cek = $(this).data('cek');
         var level = $(this).data('level');
         var title = $(this).data('title');
         var id = $(this).data('id');

         $('#id').val(id);

         $('#imageModalLabel').text('Modal '+title);

         if(title=="Kartu Jamsostek"){
             $('#image').attr('name','Jaminan1');
         }else if(title=="Saldo Jamsostek"){
             $('#image').attr('name','Jaminan2');
         }else if(title=="Jaminan Tambahan"){
             $('#image').attr('name','JaminanResmi');
         }

         if(cek != ""){
            var img = $(this).data('image');
            readURLImage(img);
         }

         if(level != "1"){
             $('.formimage').remove();
             $('.btnupdateimage').remove();
         }

         $('#imageModal').modal('show');

     });
    })
</script>
@endsection
