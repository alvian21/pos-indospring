@extends('frontend.master')

@section('title', 'Transaksi | Transfer Antar Toko')

@section('pos', 'active')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi | Transfer Antar Toko</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header container-fluid d-flex justify-content-between">
                        <h4 class="text-dark"><i class="fas fa-list pr-2"></i> Transaksi | Transfer Antar Toko</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary float-right addTransaksi ">Transaksi
                                    Transfer Antar Toko</button>
                                <button type="button" class="btn btn-primary float-right adddetailBarang mr-2">Input
                                    Detail Barang</button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        @include('frontend.include.alert')
                        <div class="table-responsive">

                            <table class="table table-bordered display nowrap" id="table-transaksi" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Transaksi</th>
                                        <th class="text-center">Nomor</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Kode</th>
                                        <th class="text-center">Supplier</th>
                                        <th class="text-center">Diskon Persen</th>
                                        <th class="text-center">Diskon Tunai</th>
                                        <th class="text-center">Pajak</th>
                                        <th class="text-center">Total Harga</th>
                                        <th class="text-center">Keterangan</th>
                                    </tr>

                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- modal transaksi --}}
<div class="modal fade bd-example-modal-lg" id="transaksiModal" tabindex="-1" role="dialog"
    aria-labelledby="transaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transaksiModalLabel">Transaksi Transfer Antar Toko</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="transaksi">Transaksi</label>
                                <input type="text" class="form-control" id="transaksi" readonly value="MUTASI">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nomor">Nomor</label>
                                <input type="text" class="form-control" id="nomor" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="text" class="form-control" id="tanggal" readonly
                                    value="{{date('d M y H:i')}}">
                            </div>

                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_rp">Diskon (Rp)</label>
                                <input type="text" class="form-control" id="diskon_rp">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_persen">Diskon (%)</label>
                                <input type="text" class="form-control" id="diskon_persen">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pajak">Pajak</label>
                                <input type="text" class="form-control" id="pajak">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lokasi_asal">Lokasi asal</label>
                                <select class="form-control" id="lokasi_asal">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <select class="form-control" id="lokasi">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="keterangan">keterangan</label>
                                <textarea class="form-control" id="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sub_total">Sub Total</label>
                                <input type="text" class="form-control" id="sub_total" readonly>
                            </div>

                        </div>

                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Post</button>
            </div>
        </div>
    </div>
</div>
{{-- modal barang --}}
<div class="modal fade bd-example-modal-lg" id="barangModal" tabindex="-1" role="dialog"
    aria-labelledby="barangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="barangModalLabel">Input Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="barang">Barang</label>
                                <select class="form-control" id="barang">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="text" class="form-control" id="harga" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" class="form-control" id="qty" >
                            </div>

                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_persen">Diskon (%)</label>
                                <input type="text" class="form-control" id="diskon_persen">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="diskon_rp">Diskon (Rp))</label>
                                <input type="text" class="form-control" id="diskon_rp">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="keterangan">keterangan</label>
                                <textarea class="form-control" id="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="total">SubtTotal</label>
                                <input type="text" class="form-control" id="total" readonly>
                            </div>

                        </div>

                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Insert</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){

    var table = $("#table-transaksi").DataTable({
        "scrollX":true
    });

    $('.addTransaksi').on('click',function () {
        $('#transaksiModal').modal('show');
    })
    $('.adddetailBarang').on('click',function () {
        $('#barangModal').modal('show');
    })
})
</script>
@endsection
