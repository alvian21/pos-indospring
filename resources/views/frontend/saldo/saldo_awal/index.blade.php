@extends('frontend.master')

@section('title', 'Saldo | Saldo Awal')

@section('saldo-awal', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Saldo awal hutang dan simpanan</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Saldo Hutang</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                   Import Excel
                                  </button>

                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-bordered display nowrap" id="table-hutang" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>KodeUser</th>
                                        <th>Saldo</th>
                                        <th>BayarBerapaKali</th>
                                        <th>CicilanTotal</th>
                                        <th>TotalBerapaKali</th>

                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($trsaldohutang as $item)
                                    <tr>
                                        <td>{{$item->Tanggal}}</td>
                                        <td>{{$item->KodeUser}}</td>
                                        <td>{{$item->Saldo}}</td>
                                        <td>{{$item->BayarBerapaKali}}</td>
                                        <td>{{$item->CicilanTotal}}</td>
                                        <td>{{$item->TotalBerapaKali}}</td>
                                    </tr>
                                    @empty

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section">

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Saldo Simpanan</h4>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-bordered display nowrap" id="table-simpanan" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>KodeUser</th>
                                        <th>Saldo</th>

                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($trsaldosimpanan as $item)
                                    <tr>
                                        <td>{{$item->Tanggal}}</td>
                                        <td>{{$item->KodeUser}}</td>
                                        <td>{{$item->Saldo}}</td>
                                    </tr>
                                    @empty

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section">

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Transaksi Periode</h4>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-bordered display nowrap" id="table-periode" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Periode</th>
                                        <th>KodeUser</th>
                                        <th>KodeTransaksi</th>
                                        <th>Nilai</th>
                                        <th>UserUpdate</th>
                                        <th>LastUpdate</th>

                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($trtransaksiperiode as $item)
                                    <tr>
                                        <td>{{$item->Nomor}}</td>
                                        <td>{{$item->Periode}}</td>
                                        <td>{{$item->KodeUser}}</td>
                                        <td>{{$item->KodeTransaksi}}</td>
                                        <td>{{$item->Nilai}}</td>
                                        <td>{{$item->UserUpdate}}</td>
                                        <td>{{$item->LastUpdate}}</td>
                                    </tr>
                                    @empty

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section">

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Transaksi Pinjaman</h4>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-bordered display nowrap" id="table-pinjaman" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>KodeAnggota</th>
                                        <th>SubDept</th>
                                        <th>Pinjaman</th>
                                        <th>CicilanTotal</th>
                                        <th>BerapaKaliBayar</th>
                                        <th>CicilanPokok</th>
                                        <th>CicilanBunga</th>
                                        <th>Alasan</th>
                                        <th>TanggalPengajuan</th>
                                        <th>UserUpdate</th>
                                        <th>ApprovalStatus</th>
                                        <th>PengajuanPinjaman</th>

                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($trpinjaman as $item)
                                    <tr>
                                        <td>{{$item->Nomor}}</td>
                                        <td>{{$item->KodeAnggota}}</td>
                                        <td>{{$item->SubDept}}</td>
                                        <td>{{$item->Pinjaman}}</td>
                                        <td>{{$item->CicilanTotal}}</td>
                                        <td>{{$item->BerapaKaliBayar}}</td>
                                        <td>{{$item->CicilanPokok}}</td>
                                        <td>{{$item->CicilanBunga}}</td>
                                        <td>{{$item->Alasan}}</td>
                                        <td>{{$item->TanggalPengajuan}}</td>
                                        <td>{{$item->UserUpdate}}</td>
                                        <td>{{$item->ApprovalStatus}}</td>
                                        <td>{{$item->PengajuanPinjaman}}</td>
                                    </tr>
                                    @empty

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{route('saldo.saldo_awal.import')}}" enctype="multipart/form-data">
            @csrf
        <div class="modal-body">
                <div class="form-group">
                  <label for="exampleFormControlFile1">Import File Excel</label>
                  <input type="file" required class="form-control-file" name="file" id="exampleFormControlFile1">
                </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
    </form>
      </div>
    </div>
  </div>

@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){

    var table = $("#table-hutang").DataTable({
        "scrollX": true,
    });

    var table_simpanan = $("#table-simpanan").DataTable({
        "scrollX": true,
    });
    var table_periode = $("#table-periode").DataTable({
        "scrollX": true,
    });
    var table_pinjaman = $("#table-pinjaman").DataTable({
        "scrollX": true,
    });



})
</script>
@endsection
