<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get("/", function(){
//     return view('welcome');
// });

Route::get("/login", "Frontend\AuthController@getLogin");
Route::post("/login", "Frontend\AuthController@login")->name("login");

Route::group(['middleware' => 'auth:web', 'namespace' => 'Frontend', 'prefix' => 'admin'], function () {
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::get('penjualanoffline', 'DashboardController@PenjualanOffline')->name('penjualanoffline');
        Route::get('penjualanonline', 'DashboardController@PenjualanOnline')->name('penjualanonline');
        Route::get('statuspesanan', 'DashboardController@StatusPesanan')->name('statuspesanan');
        Route::get('emailstatus', 'DashboardController@EmailStatus')->name('emailstatus');
        Route::get('emailstatuswithout', 'DashboardController@EmailStatusWithout')->name('emailstatuswithout');
        Route::get('barangpictures', 'DashboardController@BarangPictures')->name('barangpictures');
        Route::get('barangbarcode', 'DashboardController@BarangBarcode')->name('barangbarcode');
        Route::get('minimumstok', 'DashboardController@MinimumStok')->name('minimumstok');
    });

    Route::resource('dashboard', 'DashboardController');
    //saldo
    Route::group(['namespace' => 'Saldo', 'prefix' => 'saldo', 'as' => 'saldo.'], function () {
        Route::post('saldo_awal/excel', 'SaldoAwalController@SaldoImport')->name('saldo_awal.import');
        Route::resource('saldo_awal', 'SaldoAwalController');
    });


    //settings
    Route::group(['namespace' => 'Settings', 'prefix' => 'settings', 'as' => 'settings.'], function () {
        // Route::get('/mssetting/generatesaldominus','SettingController@generateSaldoMinus');
        Route::resource('mssetting', 'SettingController');
    });

    //master
    Route::group(['prefix' => 'master', 'namespace' => 'Master', 'as' => 'master.'], function () {

        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('/delete_detail/{id}', 'UserDetailController@delete_detail');
            Route::get('/delete_detail_item', 'UserDetailController@delete_detail_byitem')->name('deleteitem');
            Route::get('/check_user', 'UserController@checkUser')->name('checkuser');
            Route::get('/data_detail', 'UserDetailController@getDataDetail')->name('datadetail');
            Route::post('/data_detail', 'UserController@saveDetail')->name('savedetail');
            Route::post('/saveheader', 'UserDetailController@saveHeader')->name('saveheader');
            Route::get("/getdata", "UserController@getData")->name('getdata');
            Route::resource('detail', 'UserDetailController');
        });
        Route::resource('user', 'UserController');
        Route::resource('wa', 'WaController');
        Route::group(['prefix' => 'barang', 'as' => 'barang.'], function () {
            Route::get("/kode_barang", "BarangController@CheckKodeBarang")->name("check.kodebarang");
            Route::get("/kode_barcode", "BarangController@CheckKodeBarcode")->name("check.kodebarcode");
            Route::get("/kategori", "BarangController@getKategori")->name("getkategori");
            Route::resource('/', 'BarangController');
        });
    });

    //koperasi master
    Route::group(['namespace' => 'Koperasi\Master', 'prefix' => 'koperasi', 'as' => 'koperasi.'], function () {

        Route::group(['prefix' => 'anggota'], function () {
            Route::post('/import_excel', 'ListAnggotaController@AnggotaImport')->name('anggota.import');
            Route::get("/updatepassword", "ListAnggotaController@UpdatePassword")->name('updatepassword');
            Route::get("/updateemail", "ListAnggotaController@UpdateEmail")->name('updateemail');
        });

        Route::group(['prefix' => 'cicilan','as'=>'cicilan.'], function () {
            Route::get("/getdata", "MsCicilanController@getData")->name('getdata');
        });
        Route::resource('anggota', 'ListAnggotaController');
        Route::resource('transaksi', 'MsTransaksiController');
        Route::resource('cicilan', 'MsCicilanController');
    });

    //koperasi transaksi
    Route::group(['namespace' => 'Koperasi\Transaksi', 'prefix' => 'koperasi', 'as' => 'koperasi.'], function () {


        Route::group(['prefix' => 'saldo', 'as' => 'saldo.'], function () {
            Route::get('ceksaldo', 'SaldoController@CekSaldo')->name('cek');
        });
        Route::resource('saldo', 'SaldoController');

        Route::group(['prefix' => 'topup', 'as' => 'topup.'], function () {
            Route::get('ceksaldo', 'TopUpController@CekSaldo')->name('cek');
        });

        Route::group(['prefix' => 'pembayaran', 'as' => 'pembayaran.'], function () {
            Route::get('ceksaldo', 'PembayaranController@CekSaldo')->name('cek');
        });

        Route::group(['prefix' => 'proses-bulanan', 'as' => 'proses-bulanan.'], function () {
            Route::post('simpan_pinjam', 'ProsesBulananController@store_simpan_pinjam')->name('simpan.pinjam');
        });
        Route::resource('topup', 'TopUpController');
        Route::resource('pembayaran', 'PembayaranController');
        Route::resource('aktivasi', 'AktivasiController');
        Route::resource('proses-bulanan', 'ProsesBulananController');
    });

    //koperasi laporan
    Route::group(['namespace' => 'Koperasi\Laporan', 'prefix' => 'koperasi', 'as' => 'koperasi.'], function () {

        Route::resource('tagihan-kredit', 'TagihanKreditController');
        Route::resource('simpan-pinjam', 'SimpanPinjamController');
    });

    //anggota
    Route::group(['namespace' => 'Anggota', 'prefix' => 'anggotaemail', 'as' => 'anggotaemail.'], function () {
        Route::get('/verify', 'EmailController@verifyUser')->name('verify.user');
        Route::resource('/', 'EmailController');
    });

    //pinjaman
    Route::group(['namespace' => 'Pinjaman'], function () {

        Route::resource('pinjaman', 'PinjamanController');
    });

    //status pesanan
    Route::group(['namespace' => 'Status', 'prefix' => 'status', 'as' => 'status.'], function () {
        Route::get('updatestatus', 'StatusPesananController@updateStatus')->name('updatestatuspsn');
        Route::get('datapesanan', 'StatusPesananController@getDataPesanan')->name('datapesanan');
        Route::resource('pesanan', 'StatusPesananController');
    });

    //POS-master
    Route::group(['namespace' => 'POS\Master', 'prefix' => 'pos', 'as' => 'pos.'], function () {
        Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
            Route::resource('supplier', 'SupplierController');
            Route::resource('lokasi', 'LokasiController');
            Route::resource('kategori', 'KategoriController');
        });
    });

    //POS-transaksi
    Route::group(['namespace' => 'POS\Transaksi', 'prefix' => 'pos', 'as' => 'pos.'], function () {

        Route::group(['prefix' => 'pembelian'], function () {
            Route::get('/data_detail', 'PembelianController@getDataDetail')->name('pembelian.datadetail');
            Route::get('/check_session', 'PembelianController@check_session_detail')->name('pembelian.check');
            Route::get('/data_pembelian', 'PembelianController@getDataPembelian')->name('pembelian.transaksi');
            Route::post('/transaksi_pembelian', 'PembelianController@store_transaksi')->name('transaksi_pembelian.store');
            Route::post('/detail_transaksi_pembelian', 'PembelianController@store_detail')->name('detail_transaksi_pembelian.store');
            Route::post('/detail_transaksi_pembelian_update', 'PembelianController@update_detail_barang')->name('detail_transaksi_pembelian.update');
            Route::get('/data_barang', 'PembelianController@get_data_barang')->name('pembelian.databarang');
            Route::get('/save_transaksi_pembelian', 'PembelianController@save_data_transaksi')->name('pembelian.save');
            Route::post('/update_transaksi', 'PembelianController@update_transaksi')->name('update.trpembelian');
            Route::get('/delete_detail/{id}', 'PembelianController@delete_data');
        });
        Route::group(['prefix' => 'penjualan'], function () {
            Route::get('/data_detail', 'PenjualanController@getDataDetail')->name('penjualan.datadetail');
            Route::get('/data_penjualan', 'PenjualanController@getDataPenjualan')->name('penjualan.transaksi');
            Route::post('/transaksi_penjualan', 'PenjualanController@store_transaksi')->name('transaksi_penjualan.store');
            Route::post('/detail_transaksi_penjualan', 'PenjualanController@store_detail')->name('detail_transaksi_penjualan.store');
            Route::get('/data_barang', 'PenjualanController@get_data_barang')->name('penjualan.databarang');
            Route::post('/save_transaksi_penjualan', 'PenjualanController@save_data_transaksi')->name('penjualan.save');
            Route::post('/detail_transaksi_pembelian_update', 'PenjualanController@update_detail_barang')->name('detail_transaksi_penjualan.update');
            Route::get('/check_session', 'PenjualanController@check_session_detail')->name('penjualan.check');
            Route::get('/delete_detail/{id}', 'PenjualanController@delete_data');
            Route::get('/saldo_ekop', 'PenjualanController@CekSaldoEkop')->name('penjualan.ceksaldo');
        });

        Route::group(['prefix' => 'stockhilang'], function () {
            Route::get('/data_detail', 'StockHilangController@getDataDetail')->name('stockhilang.datadetail');
            Route::get('/data_opnamestockhilang', 'StockHilangController@getDatastockhilang')->name('stockhilang.transaksi');
            Route::post('/transaksi_stockhilang', 'StockHilangController@store_transaksi')->name('transaksi_stockhilang.store');
            Route::post('/detail_transaksi_stockhilang', 'StockHilangController@store_detail')->name('detail_transaksi_stockhilang.store');
            Route::get('/data_barang', 'StockHilangController@get_data_barang')->name('stockhilang.databarang');
            Route::get('/save_transaksi_stockhilang', 'StockHilangController@save_data_transaksi')->name('stockhilang.save');
            Route::post('/detail_transaksi_pembelian_update', 'StockHilangController@update_detail_barang')->name('detail_transaksi_stockhilang.update');
            Route::get('/check_session', 'StockHilangController@check_session_detail')->name('stockhilang.check');
            Route::get('/delete_detail/{id}', 'StockHilangController@delete_data');
            Route::get('/saldo_ekop', 'StockHilangController@CekSaldoEkop')->name('stockhilang.ceksaldo');
        });

        Route::group(['prefix' => 'mutasi'], function () {
            Route::get('/data_detail', 'TfAntarTokoController@getDataDetail')->name('mutasi.datadetail');
            Route::get('/data_mutasi', 'TfAntarTokoController@getDataMutasi')->name('mutasi.transaksi');
            Route::post('/transaksi_mutasi', 'TfAntarTokoController@store_transaksi')->name('transaksi_mutasi.store');
            Route::post('/detail_transaksi_mutasi', 'TfAntarTokoController@store_detail')->name('detail_transaksi_mutasi.store');
            Route::get('/data_barang', 'TfAntarTokoController@get_data_barang')->name('mutasi.databarang');
            Route::post('/save_transaksi_mutasi', 'TfAntarTokoController@save_data_transaksi')->name('mutasi.save');
            Route::post('/detail_transaksi_pembelian_update', 'TfAntarTokoController@update_detail_barang')->name('detail_transaksi_mutasi.update');
            Route::get('/check_session', 'TfAntarTokoController@check_session_detail')->name('mutasi.check');
            Route::get('/delete_detail/{id}', 'TfAntarTokoController@delete_data');
            Route::get('/saldo_ekop', 'TfAntarTokoController@CekSaldoEkop')->name('mutasi.ceksaldo');
        });


        Route::group(['prefix' => 'opname'], function () {
            Route::get('/data_detail', 'StockOpnameController@getDataDetail')->name('opname.datadetail');
            Route::get('/data_opname', 'StockOpnameController@getDataOpname')->name('opname.transaksi');
            Route::post('/transaksi_opname', 'StockOpnameController@store_transaksi')->name('transaksi_opname.store');
            Route::post('/detail_transaksi_opname', 'StockOpnameController@store_detail')->name('detail_transaksi_opname.store');
            Route::get('/data_barang', 'StockOpnameController@get_data_barang')->name('opname.databarang');
            Route::get('/save_transaksi_opname', 'StockOpnameController@save_data_transaksi')->name('opname.save');
            Route::post('/detail_transaksi_pembelian_update', 'StockOpnameController@update_detail_barang')->name('detail_transaksi_opname.update');
            Route::get('/check_session', 'StockOpnameController@check_session_detail')->name('opname.check');
            Route::get('/delete_detail/{id}', 'StockOpnameController@delete_data');
            Route::get('/saldo_ekop', 'StockOpnameController@CekSaldoEkop')->name('opname.ceksaldo');
        });


        Route::group(['prefix' => 'kasir'], function () {
            Route::get('/data_detail', 'KasirController@getDataDetail')->name('kasir.datadetail');
            Route::get('/data_kasir', 'KasirController@getDataKasir')->name('kasir.transaksi');
            Route::post('/transaksi_kasir', 'KasirController@store_transaksi')->name('transaksi_kasir.store');
            Route::post('/detail_transaksi_kasir', 'KasirController@store_detail')->name('detail_transaksi_kasir.store');
            Route::get('/data_barang', 'KasirController@get_data_barang')->name('kasir.databarang');
            Route::post('/save_transaksi_kasir', 'KasirController@save_data_transaksi')->name('kasir.save');
            Route::post('/detail_transaksi_pembelian_update', 'KasirController@update_detail_barang')->name('detail_transaksi_kasir.update');
            Route::get('/check_session', 'KasirController@check_session_detail')->name('kasir.check');
            Route::get('/delete_detail/{id}', 'KasirController@delete_data');
            Route::get('/saldo_ekop', 'KasirController@CekSaldoEkop')->name('kasir.ceksaldo');
            Route::get('/receipt', 'KasirController@receipt')->name('kasir.receipt');
            Route::get('/getstatus', 'KasirController@getStatus')->name('kasir.status');
        });

        Route::group(['prefix' => 'pembelianbaru'], function () {
            Route::get('/data_detail', 'PembelianBaruController@getDataDetail')->name('pembelianbaru.datadetail');
            Route::get('/data_pembelianbaru', 'PembelianBaruController@getDataMutasi')->name('pembelianbaru.transaksi');
            Route::post('/transaksi_pembelianbaru', 'PembelianBaruController@store_transaksi')->name('transaksi_pembelianbaru.store');
            Route::post('/detail_transaksi_pembelianbaru', 'PembelianBaruController@store_detail')->name('detail_transaksi_pembelianbaru.store');
            Route::get('/data_barang', 'PembelianBaruController@get_data_barang')->name('pembelianbaru.databarang');
            Route::get('/save_transaksi_pembelianbaru', 'PembelianBaruController@save_data_transaksi')->name('pembelianbaru.save');
            Route::post('/detail_transaksi_pembelian_update', 'PembelianBaruController@update_detail_barang')->name('detail_transaksi_pembelianbaru.update');
            Route::get('/check_session', 'PembelianBaruController@check_session_detail')->name('pembelianbaru.check');
            Route::get('/delete_detail/{id}', 'PembelianBaruController@delete_data');
            Route::get('/update_status', 'PembelianBaruController@updatePost')->name('pembelianbaru.updatestatus');
            Route::post('/transaksi_pembelianbaru/edit', 'PembelianBaruController@store_transaksi_edit')->name('transaksi_pembelianbaru.update');
            Route::post('/transaksi_pembelianbaru/edit/store', 'PembelianBaruController@store_detail_update')->name('transaksi_pembelianbaru.store_detail_update');
            Route::delete('/transaksi_pembelianbaru/edit/delete', 'PembelianBaruController@delete_detail')->name('transaksi_pembelianbaru.delete_detail');
            Route::get('/data_detail/edit', 'PembelianBaruController@getDataDetailEdit')->name('pembelianbaru.datadetailedit');
        });

        Route::group(['prefix' => 'returpembelian'], function () {
            Route::get('/data_detail', 'ReturPembelianController@getDataDetail')->name('returpembelian.datadetail');
            Route::get('/data_returpembelian', 'ReturPembelianController@getDataMutasi')->name('returpembelian.transaksi');
            Route::post('/transaksi_returpembelian', 'ReturPembelianController@store_transaksi')->name('transaksi_returpembelian.store');
            Route::post('/detail_transaksi_returpembelian', 'ReturPembelianController@store_detail')->name('detail_transaksi_returpembelian.store');
            Route::get('/data_barang', 'ReturPembelianController@get_data_barang')->name('returpembelian.databarang');
            Route::get('/save_transaksi_returpembelian', 'ReturPembelianController@save_data_transaksi')->name('returpembelian.save');
            Route::post('/detail_transaksi_pembelian_update', 'ReturPembelianController@update_detail_barang')->name('detail_transaksi_returpembelian.update');
            Route::get('/check_session', 'ReturPembelianController@check_session_detail')->name('returpembelian.check');
            Route::get('/delete_detail/{id}', 'ReturPembelianController@delete_data');
            Route::get('/update_status', 'ReturPembelianController@updatePost')->name('returpembelian.updatestatus');
            Route::get('/data_detail/edit', 'ReturPembelianController@getDataDetailEdit')->name('returpembelian.datadetailedit');
            Route::post('/transaksi_returpembelian/edit', 'ReturPembelianController@store_transaksi_edit')->name('transaksi_returpembelian.update');
            Route::delete('/transaksi_returpembelian/edit/delete', 'ReturPembelianController@delete_detail')->name('transaksi_returpembelian.delete_detail');
            Route::post('/transaksi_returpembelian/edit/store', 'ReturPembelianController@store_detail_update')->name('transaksi_returpembelian.store_detail_update');
        });

        Route::group(['prefix' => 'listpromo'], function () {
            Route::get('/data_detail', 'ListPromoController@getDataDetail')->name('listpromo.datadetail');
            Route::get('/data_listpromo', 'ListPromoController@getDataMutasi')->name('listpromo.transaksi');
            Route::post('/transaksi_listpromo', 'ListPromoController@store_transaksi')->name('transaksi_listpromo.store');
            Route::post('/detail_transaksi_listpromo', 'ListPromoController@store_detail')->name('detail_transaksi_listpromo.store');
            Route::get('/data_barang', 'ListPromoController@get_data_barang')->name('listpromo.databarang');
            Route::get('/save_transaksi_listpromo', 'ListPromoController@save_data_transaksi')->name('listpromo.save');
            Route::post('/detail_transaksi_pembelian_update', 'ListPromoController@update_detail_barang')->name('detail_transaksi_listpromo.update');
            Route::get('/check_session', 'ListPromoController@check_session_detail')->name('listpromo.check');
            Route::get('/delete_detail/{id}', 'ListPromoController@delete_data');
            Route::get('/update_status', 'ListPromoController@updatePost')->name('listpromo.updatestatus');
            Route::get('/data_detail/edit', 'ListPromoController@getDataDetailEdit')->name('listpromo.datadetailedit');
            Route::post('/transaksi_listpromo/edit', 'ListPromoController@store_transaksi_edit')->name('transaksi_listpromo.update');
            Route::delete('/transaksi_listpromo/edit/delete', 'ListPromoController@delete_detail')->name('transaksi_listpromo.delete_detail');
            Route::post('/transaksi_listpromo/edit/store', 'ListPromoController@store_detail_update')->name('transaksi_listpromo.store_detail_update');
        });

        Route::resource('pembelian', 'PembelianController');
        Route::resource('returpembelian', 'ReturPembelianController');
        Route::resource('listpromo', 'ListPromoController');
        Route::resource('pembelianbaru', 'PembelianBaruController');
        Route::resource('penjualan', 'PenjualanController');
        Route::resource('tfantartoko', 'TfAntarTokoController');
        Route::resource('stockopname', 'StockOpnameController');
        Route::resource('stockhilang', 'StockHilangController');
        Route::resource('kasir', 'KasirController');
        Route::resource('harga', 'HargaController');
    });


    Route::group(['namespace' => 'POS\Laporan', 'prefix' => 'pos/laporan', 'as' => 'poslaporan.'], function () {

        // Route::get('/penjualan/cetakpdf','PenjualanController@cetakPdf');
        Route::post('/penjualan/cetakpdf', 'PenjualanController@cetakPdf')->name('penjualan.cetakpdf');
        Route::post('/penjualan/cetakdetail', 'PenjualanController@cetakDetail')->name('penjualan.cetakdetail');
        Route::post('/pembelian/cetakpdf', 'PembelianController@cetakPdf')->name('pembelian.cetakpdf');
        Route::post('/pembelian/cetakdetail', 'PembelianController@cetakDetail')->name('pembelian.cetakdetail');
        Route::post('/minimumstok/cetak', 'MinimumStokController@cetak')->name('minimumstok.cetak');
        Route::post('/opnamehilang/cetak', 'OpnameHilangController@cetakDetail')->name('opnamehilang.cetak');
        Route::post('/realtimestok/cetak', 'RealtimeStokController@cetak')->name('realtimestok.cetak');
        Route::post('/paretopenjualan/cetakpdf', 'ParetoPenjualan@cetakPdf')->name('paretopenjualan.cetakpdf');
        Route::post('/tracestok/cetak', 'TraceStokController@cetak')->name('tracestok.cetak');
        Route::get('/trcetak/label', 'TrcetakController@cetak')->name('cetak.label');
        Route::delete('/trcetak/deleteall', 'TrcetakController@deleteAll')->name('cetak.deleteall');
        // Route::post('/paretopenjualan/cetakdetail','ParetoPenjualan@cetakDetail')->name('paretopenjualan.cetakdetail');
        Route::resource('penjualan', 'PenjualanController');
        Route::resource('pembelian', 'PembelianController');
        Route::resource('minimumstok', 'MinimumStokController');
        Route::resource('opnamehilang', 'OpnameHilangController');
        Route::resource('realtimestok', 'RealtimeStokController');
        Route::resource('paretopenjualan', 'ParetoPenjualan');
        Route::resource('tracestok', 'TraceStokController');
        Route::resource('trcetak', 'TrcetakController');
        Route::resource('mutasibulanan', 'MutasiBulananController');
    });


    //logout
    Route::post("/logout", "AuthController@logout")->name("logout");
});
