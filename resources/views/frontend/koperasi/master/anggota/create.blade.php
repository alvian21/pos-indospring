@extends('frontend.master')

@section('title', 'Master | List anggota')

@section('koperasi', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Master | Tambah anggota</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Master | Tambah anggota</h4>
                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')

                        <form action="{{route('koperasi.anggota.store')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode">Kode</label>
                                        <input type="text" class="form-control" name="kode" id="kode" placeholder="kode">
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" readonly class="form-control" id="email"
                                            placeholder="email">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status Aktif</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="1">Ya</option>
                                            <option value="0">Tidak</option>
                                        </select>

                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                            <option value="M">Laki - laki</option>
                                            <option value="F">Perempuan</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="grp">Grp</label>
                                        <input type="text" class="form-control" id="grp" name="grp">
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pangkat">Pangkat</label>
                                        <input type="text" class="form-control" name="pangkat" id="pangkat">

                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="dept">Dept</label>
                                        <input type="text" class="form-control" name="dept" id="dept">
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="subdept">Subdept</label>
                                        <input type="text" class="form-control" name="subdept" id="subdept">
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tanggal_masuk">Tanggal Masuk</label>
                                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk">

                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tanggal_keluar">Tanggal Keluar</label>
                                        <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar">
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password">Password (Default 000000)</label>
                                        <input type="text" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
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
