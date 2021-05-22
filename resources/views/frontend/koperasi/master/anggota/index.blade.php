@extends('frontend.master')

@section('title', 'Master | List anggota')

@section('koperasi', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Master | List anggota</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Master | List anggota</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalImport">
                                    Import Excel
                                   </button>
                                <a type="button" href="{{route('koperasi.anggota.create')}}" class="btn btn-primary float-right addAnggota ml-2">Tambah
                                    Anggota</a>

                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-bordered display nowrap" id="table-anggota" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Email</th>
                                        <th>Nama</th>
                                        <th>Aktif</th>
                                        <th>Jenis Kelamin</th>
                                        <th>GRP</th>
                                        <th>Pangkat</th>
                                        <th>Subdept</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Tanggal Keluar</th>
                                        <th>User Update</th>
                                        <th>Last Update</th>
                                        <th>Aksi</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse ($anggota as $item)
                                    <tr>
                                        <td>{{$item->Kode}}</td>
                                        <td>{{$item->Email}}</td>
                                        <td>{{$item->Nama}}</td>
                                        <td>{{$item->Aktif}}</td>
                                        <td>{{$item->Sex}}</td>
                                        <td>{{$item->Grp}}</td>
                                        <td>{{$item->Pangkat}}</td>
                                        <td>{{$item->SubDept}}</td>
                                        <td>{{$item->TglMasuk}}</td>
                                        <td>{{$item->TglKeluar}}</td>
                                        <td>{{$item->UserUpdate}}</td>
                                        <td>{{$item->LastUpdate}}</td>
                                        <td><a href="{{route('koperasi.anggota.edit',[$item->Kode])}}" class="btn btn-warning">Edit</a></td>
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

<div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalImportLabel">Import Excel</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{route('koperasi.anggota.import')}}" enctype="multipart/form-data">
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

    var table = $("#table-anggota").DataTable({
        "scrollX": true,
    });



})
</script>
@endsection
