@extends('frontend.master')

@section('title', 'Upload-SHU')

@section('transaksi', 'active')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Transaksi | Upload SHU</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-dark">
                        <div class="card-header container-fluid d-flex justify-content-between">
                            <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Transaksi | Upload SHU</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modalImport">
                                        Import Excel
                                    </button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#modalHapus">
                                        Hapus SHU
                                    </button>


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
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Total Kontribusi</th>
                                            <th>Poin</th>
                                            <th>Nilai Poin</th>
                                            <th>Periode</th>
                                            {{-- <th>Aksi</th> --}}
                                        </tr>

                                    </thead>
                                    <tbody>
                                        {{-- @forelse ($anggota as $item)
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
                                        <td>
                                            <a href="{{route('koperasi.anggota.edit',[$item->Kode])}}" class="btn btn-warning">Edit</a>
                                            <button type="button" class="btn btn-warning btnpassword" data-kode="{{$item->Kode}}">Reset Password</button>
                                            <button type="button" class="btn btn-warning btnemail" data-kode="{{$item->Kode}}">Reset Email</button>
                                        </td>
                                    </tr>
                                    @empty

                                    @endforelse --}}

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportLabel">Import Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('koperasi.shu.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="periode">Periode</label>
                            <select class="form-control" name="periode" id="periode">
                                @foreach ($tahun as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach

                            </select>
                        </div>
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

      <div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-labelledby="modalHapusLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHapusLabel">Hapus SHU</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('koperasi.shu.hapus') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="periode">Periode</label>
                            <select class="form-control" name="periode" id="periode">
                                @foreach ($tahun as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var table = $("#table-anggota").DataTable({
                "scrollX": true,
                    processing: true,
                serverSide: false,
                ajax: "{{ route('koperasi.shu.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'total_kontribusi',
                        name: 'total_kontribusi'
                    },
                    {
                        data: 'poin',
                        name: 'poin'
                    },
                    {
                        data: 'nilai_poin',
                        name: 'nilai_poin'
                    },
                    {
                        data: 'periode',
                        name: 'periode'
                    }

                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false
                    // },
                ],
            });

            $(document).on('click', '.btnpassword', function() {
                var kode = $(this).data("kode");
                swal({
                        title: "Apa anda yakin?",
                        text: "Proses ini akan me-RESET Password (000000)",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willUpdate) => {
                        if (willUpdate) {

                            $.ajax({
                                url: "{{ route('koperasi.updatepassword') }}",
                                method: "GET",
                                data: {
                                    'kode': kode
                                },
                                success: function(data) {
                                    swal("Password berhasil di update!", {
                                        icon: "success",
                                    });

                                    setTimeout(function() {
                                        window.location.href =
                                            "{{ route('koperasi.anggota.index') }}"
                                    }, 1500)
                                }
                            })


                        }
                    });
            })

            $(document).on('click', '.btnemail', function() {
                var kode = $(this).data("kode");
                swal({
                        title: "Apa anda yakin?",
                        text: "Proses ini akan me-RESET Email",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willUpdate) => {
                        if (willUpdate) {

                            $.ajax({
                                url: "{{ route('koperasi.updateemail') }}",
                                method: "GET",
                                data: {
                                    'kode': kode
                                },
                                success: function(data) {
                                    swal("Email berhasil di update!", {
                                        icon: "success",
                                    });

                                    setTimeout(function() {
                                        window.location.href =
                                            "{{ route('koperasi.anggota.index') }}"
                                    }, 1500)
                                }
                            })


                        }
                    });
            })
        })
    </script>
@endsection
