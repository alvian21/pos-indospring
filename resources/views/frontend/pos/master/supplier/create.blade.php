@extends('frontend.master')

@section('title', 'Supplier')

@section('posmaster', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Supplier</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-8">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Tambah Supplier</h4>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <form action="{{route('pos.master.supplier.store')}}" method="post" id="formTransaksi">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode">Kode</label>
                                        <input type="text" class="form-control" required id="kode" name="kode">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control" required id="nama" name="nama">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kota">Kota</label>
                                        <input type="text" class="form-control" id="kota" name="kota">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <input type="text" class="form-control" id="alamat" name="alamat">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kontak_person">Kontak Person</label>
                                        <input type="text" class="form-control" id="kontak_person" name="kontak_person">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone1">Phone 1</label>
                                        <input type="text" class="form-control" id="phone1" name="phone1">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone2">Phone 2</label>
                                        <input type="text" class="form-control" id="phone2" name="phone2">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
