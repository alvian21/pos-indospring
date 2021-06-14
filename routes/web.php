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
    Route::group(['prefix'=>'dashboard','as'=>'dashboard.'],function () {
        Route::get('penjualanoffline','DashboardController@PenjualanOffline')->name('penjualanoffline');
        Route::get('penjualanonline','DashboardController@PenjualanOnline')->name('penjualanonline');
        Route::get('statuspesanan','DashboardController@StatusPesanan')->name('statuspesanan');
        Route::get('emailstatus','DashboardController@EmailStatus')->name('emailstatus');
        Route::get('emailstatuswithout','DashboardController@EmailStatusWithout')->name('emailstatuswithout');
        Route::get('barangpictures','DashboardController@BarangPictures')->name('barangpictures');
        Route::get('barangbarcode','DashboardController@BarangBarcode')->name('barangbarcode');
        Route::get('minimumstok','DashboardController@MinimumStok')->name('minimumstok');

    });

    Route::resource('dashboard', 'DashboardController');
    //saldo
    Route::group(['namespace' => 'Saldo', 'prefix' => 'saldo', 'as' => 'saldo.'], function () {
        Route::post('saldo_awal/excel', 'SaldoAwalController@SaldoImport')->name('saldo_awal.import');
        Route::resource('saldo_awal', 'SaldoAwalController');
    });


       //settings
    Route::group(['namespace' => 'Settings', 'prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::resource('mssetting', 'SettingController');
    });

    //koperasi
    Route::group(['namespace' => 'Koperasi\Master', 'prefix' => 'koperasi', 'as' => 'koperasi.'], function () {

        Route::group(['prefix' => 'anggota'], function () {
            Route::post('/import_excel','ListAnggotaController@AnggotaImport')->name('anggota.import');
            Route::get("/updatepassword","ListAnggotaController@UpdatePassword")->name('updatepassword');
            Route::get("/updateemail","ListAnggotaController@UpdateEmail")->name('updateemail');
        });
        Route::resource('anggota', 'ListAnggotaController');

    });

    //anggota
    Route::group(['namespace'=>'Anggota','prefix'=>'anggotaemail','as'=>'anggotaemail.'],function () {
        Route::get('/verify','EmailController@verifyUser')->name('verify.user');
        Route::resource('/','EmailController');
    });

    //pinjaman
    Route::group(['namespace' => 'Pinjaman','prefix'=>'pinjaman','as'=>'pinjaman.'],function () {

        Route::resource('/', 'PinjamanController');
    });

    //status pesanan
    Route::group(['namespace' => 'Status','prefix'=>'status','as'=>'status.'],function () {
        Route::get('updatestatus','StatusPesananController@updateStatus')->name('updatestatuspsn');
        Route::get('datapesanan','StatusPesananController@getDataPesanan')->name('datapesanan');
        Route::resource('pesanan', 'StatusPesananController');
    });

    //POS-transaksi
    Route::group(['namespace' => 'POS\Transaksi', 'prefix' => 'pos', 'as' => 'pos.'], function () {

        Route::group(['prefix' => 'pembelian'], function () {
            Route::get('/data_detail','PembelianController@getDataDetail')->name('pembelian.datadetail');
            Route::get('/check_session','PembelianController@check_session_detail')->name('pembelian.check');
            Route::get('/data_pembelian','PembelianController@getDataPembelian')->name('pembelian.transaksi');
            Route::post('/transaksi_pembelian', 'PembelianController@store_transaksi')->name('transaksi_pembelian.store');
            Route::post('/detail_transaksi_pembelian', 'PembelianController@store_detail')->name('detail_transaksi_pembelian.store');
            Route::post('/detail_transaksi_pembelian_update', 'PembelianController@update_detail_barang')->name('detail_transaksi_pembelian.update');
            Route::get('/data_barang', 'PembelianController@get_data_barang')->name('pembelian.databarang');
            Route::get('/save_transaksi_pembelian', 'PembelianController@save_data_transaksi')->name('pembelian.save');
            Route::post('/update_transaksi','PembelianController@update_transaksi')->name('update.trpembelian');
            Route::get('/delete_detail/{id}', 'PembelianController@delete_data');
        });
        Route::group(['prefix' => 'penjualan'], function () {
            Route::get('/data_detail','PenjualanController@getDataDetail')->name('penjualan.datadetail');
            Route::get('/data_penjualan','PenjualanController@getDataPenjualan')->name('penjualan.transaksi');
            Route::post('/transaksi_penjualan', 'PenjualanController@store_transaksi')->name('transaksi_penjualan.store');
            Route::post('/detail_transaksi_penjualan', 'PenjualanController@store_detail')->name('detail_transaksi_penjualan.store');
            Route::get('/data_barang', 'PenjualanController@get_data_barang')->name('penjualan.databarang');
            Route::post('/save_transaksi_penjualan', 'PenjualanController@save_data_transaksi')->name('penjualan.save');
            Route::post('/detail_transaksi_pembelian_update', 'PenjualanController@update_detail_barang')->name('detail_transaksi_penjualan.update');
            Route::get('/check_session', 'PenjualanController@check_session_detail')->name('penjualan.check');
            Route::get('/delete_detail/{id}', 'PenjualanController@delete_data');
            Route::get('/saldo_ekop','PenjualanController@CekSaldoEkop')->name('penjualan.ceksaldo');
        });

        Route::group(['prefix' => 'stockhilang'], function () {
            Route::get('/data_detail','StockHilangController@getDataDetail')->name('stockhilang.datadetail');
            Route::get('/data_opnamestockhilang','StockHilangController@getDatastockhilang')->name('stockhilang.transaksi');
            Route::post('/transaksi_stockhilang', 'StockHilangController@store_transaksi')->name('transaksi_stockhilang.store');
            Route::post('/detail_transaksi_stockhilang', 'StockHilangController@store_detail')->name('detail_transaksi_stockhilang.store');
            Route::get('/data_barang', 'StockHilangController@get_data_barang')->name('stockhilang.databarang');
            Route::get('/save_transaksi_stockhilang', 'StockHilangController@save_data_transaksi')->name('stockhilang.save');
            Route::post('/detail_transaksi_pembelian_update', 'StockHilangController@update_detail_barang')->name('detail_transaksi_stockhilang.update');
            Route::get('/check_session', 'StockHilangController@check_session_detail')->name('stockhilang.check');
            Route::get('/delete_detail/{id}', 'StockHilangController@delete_data');
            Route::get('/saldo_ekop','StockHilangController@CekSaldoEkop')->name('stockhilang.ceksaldo');
        });

        Route::group(['prefix' => 'mutasi'], function () {
            Route::get('/data_detail','TfAntarTokoController@getDataDetail')->name('mutasi.datadetail');
            Route::get('/data_mutasi','TfAntarTokoController@getDataMutasi')->name('mutasi.transaksi');
            Route::post('/transaksi_mutasi', 'TfAntarTokoController@store_transaksi')->name('transaksi_mutasi.store');
            Route::post('/detail_transaksi_mutasi', 'TfAntarTokoController@store_detail')->name('detail_transaksi_mutasi.store');
            Route::get('/data_barang', 'TfAntarTokoController@get_data_barang')->name('mutasi.databarang');
            Route::post('/save_transaksi_mutasi', 'TfAntarTokoController@save_data_transaksi')->name('mutasi.save');
            Route::post('/detail_transaksi_pembelian_update', 'TfAntarTokoController@update_detail_barang')->name('detail_transaksi_mutasi.update');
            Route::get('/check_session', 'TfAntarTokoController@check_session_detail')->name('mutasi.check');
            Route::get('/delete_detail/{id}', 'TfAntarTokoController@delete_data');
            Route::get('/saldo_ekop','TfAntarTokoController@CekSaldoEkop')->name('mutasi.ceksaldo');
        });


        Route::group(['prefix' => 'opname'], function () {
            Route::get('/data_detail','StockOpnameController@getDataDetail')->name('opname.datadetail');
            Route::get('/data_opname','StockOpnameController@getDataOpname')->name('opname.transaksi');
            Route::post('/transaksi_opname', 'StockOpnameController@store_transaksi')->name('transaksi_opname.store');
            Route::post('/detail_transaksi_opname', 'StockOpnameController@store_detail')->name('detail_transaksi_opname.store');
            Route::get('/data_barang', 'StockOpnameController@get_data_barang')->name('opname.databarang');
            Route::get('/save_transaksi_opname', 'StockOpnameController@save_data_transaksi')->name('opname.save');
            Route::post('/detail_transaksi_pembelian_update', 'StockOpnameController@update_detail_barang')->name('detail_transaksi_opname.update');
            Route::get('/check_session', 'StockOpnameController@check_session_detail')->name('opname.check');
            Route::get('/delete_detail/{id}', 'StockOpnameController@delete_data');
            Route::get('/saldo_ekop','StockOpnameController@CekSaldoEkop')->name('opname.ceksaldo');
        });

        Route::resource('pembelian', 'PembelianController');
        Route::resource('penjualan', 'PenjualanController');
        Route::resource('tfantartoko', 'TfAntarTokoController');
        Route::resource('stockopname', 'StockOpnameController');
        Route::resource('stockhilang', 'StockHilangController');
    });


    Route::group(['namespace'=>'POS\Laporan','prefix'=>'pos/laporan','as'=>'poslaporan.'],function () {

        // Route::get('/penjualan/cetakpdf','PenjualanController@cetakPdf');
        Route::post('/penjualan/cetakpdf','PenjualanController@cetakPdf')->name('penjualan.cetakpdf');
        Route::post('/penjualan/cetakdetail','PenjualanController@cetakDetail')->name('penjualan.cetakdetail');
        Route::post('/minimumstok/cetak','MinimumStokController@cetak')->name('minimumstok.cetak');
        Route::post('/opnamehilang/cetak','OpnameHilangController@cetakDetail')->name('opnamehilang.cetak');
        Route::post('/realtimestok/cetak','RealtimeStokController@cetak')->name('realtimestok.cetak');
        Route::resource('penjualan', 'PenjualanController');
        Route::resource('minimumstok', 'MinimumStokController');
        Route::resource('opnamehilang', 'OpnameHilangController');
        Route::resource('realtimestok', 'RealtimeStokController');
    });


    //logout
    Route::post("/logout", "AuthController@logout")->name("logout");
});
