@extends('frontend.master')

@section('title', 'Master | List anggota')

@section('koperasi', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Master | Edit anggota</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Master | Edit anggota</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')

                        <form action="{{route('koperasi.anggota.update',[$anggota->Kode])}}" method="POST">
                            @csrf
                            {{method_field('PUT')}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode">Kode</label>
                                        <input type="text" class="form-control" readonly name="kode" id="kode" value="{{$anggota->Kode}}" placeholder="kode">
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" readonly class="form-control" id="email"
                                            placeholder="email" value="{{$anggota->Email}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="{{$anggota->Nama}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status Aktif</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="1" @if($anggota->Aktif==1) selected @endif>Ya</option>
                                            <option value="0" @if($anggota->Aktif==0) selected @endif> Tidak</option>
                                        </select>

                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                            <option value="M" @if($anggota->Sex=='M') selected @endif>Laki - laki</option>
                                            <option value="F" @if($anggota->Sex=='F') selected @endif>Perempuan</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="grp">Grp</label>
                                        <input type="text" class="form-control" id="grp" name="grp" value="{{$anggota->Grp}}">
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pangkat">Pangkat</label>
                                        <input type="text" class="form-control" name="pangkat" id="pangkat" value="{{$anggota->Pangkat}}">

                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="dept">Dept</label>
                                        <input type="text" class="form-control" name="dept" id="dept"  value="{{$anggota->Dept}}">
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="subdept">Subdept</label>
                                        <input type="text" class="form-control" name="subdept" id="subdept"  value="{{$anggota->SubDept}}">
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tanggal_masuk">Tanggal Masuk</label>
                                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk"  value="{{$anggota->TglMasuk}}">

                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tanggal_keluar">Tanggal Keluar</label>
                                        <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" value="{{$anggota->TglKeluar}}">
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password">Password </label>
                                        <input type="text" class="form-control" id="password" name="password" value="{{$anggota->UserPassword}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
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
    $(document).ready(function(){

    var table = $("#table-anggota").DataTable({
        "scrollX": true,
    });



})
</script>
@endsection
