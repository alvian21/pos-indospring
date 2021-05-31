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
    });
    Route::resource('dashboard', 'DashboardController');
    //saldo
    Route::group(['namespace' => 'Saldo', 'prefix' => 'saldo', 'as' => 'saldo.'], function () {
        Route::post('saldo_awal/excel', 'SaldoAwalController@SaldoImport')->name('saldo_awal.import');
        Route::resource('saldo_awal', 'SaldoAwalController');
    });

    //koperasi
    Route::group(['namespace' => 'Koperasi\Master', 'prefix' => 'koperasi', 'as' => 'koperasi.'], function () {

        Route::group(['prefix' => 'anggota'], function () {
            Route::post('/import_excel','ListAnggotaController@AnggotaImport')->name('anggota.import');
        });
        Route::resource('anggota', 'ListAnggotaController');
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
            Route::get('/data_stockhilang','StockHilangController@getDataStockHilang')->name('stockhilang.transaksi');
            Route::post('/transaksi_stockhilang', 'StockHilangController@store_transaksi')->name('transaksi_stockhilang.store');
            Route::post('/detail_transaksi_stockhilang', 'StockHilangController@store_detail')->name('detail_transaksi_stockhilang.store');
            Route::get('/data_barang', 'StockHilangController@get_data_barang')->name('stockhilang.databarang');
            Route::get('/save_transaksi_stockhilang', 'StockHilangController@save_data_transaksi')->name('stockhilang.save');
        });

        Route::resource('pembelian', 'PembelianController');
        Route::resource('penjualan', 'PenjualanController');
        Route::resource('tfantartoko', 'TfAntarTokoController');
        Route::resource('stockopname', 'StockOpnameController');
        Route::resource('stockhilang', 'StockHilangController');
    });


    //logout
    Route::post("/logout", "AuthController@logout")->name("logout");
});
