@extends('frontend.master')

@section('title', 'Supplier')

@section('posmaster', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Daftar Supplier</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Daftar Supplier</h4>
                        <a href="{{route('pos.master.supplier.create')}}"
                            class="btn btn-primary float-right addSupplier">Tambah Supplier</a>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Kota</th>
                                        <th>Kontak Person</th>
                                        <th>Phone 1</th>
                                        <th>Phone 2</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($supplier as $item)
                                    <tr>
                                            <td>{{$item->Kode}}</td>
                                            <td>{{$item->Nama}}</td>
                                            <td>{{$item->Alamat}}</td>
                                            <td>{{$item->Kota}}</td>
                                            <td>{{$item->KontakPerson}}</td>
                                            <td>{{$item->Phone1}}</td>
                                            <td>{{$item->Phone2}}</td>
                                            <td>
                                                <a href="{{route('pos.master.supplier.edit',[$item->Kode])}}" class="btn btn-success">Edit</a>
                                            </td>
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
    $(document).ready(function () {
        var table =  $('#table-1').DataTable();
     })
</script>
@endsection
