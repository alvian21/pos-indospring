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
                                <a type="button" href="{{route('koperasi.anggota.create')}}" class="btn btn-primary float-right addAnggota ">Tambah
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
