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

Route::get("/login", "Frontend\AuthController@getLogin");
Route::post("/login", "Frontend\AuthController@login")->name("login");


Route::group(['middleware' => 'auth:web', 'namespace' => 'Frontend', 'prefix' => 'admin'], function () {
    Route::resource('dashboard', 'DashboardController');


    //POS-transaksi
    Route::group(['namespace' => 'POS\Transaksi', 'prefix' => 'pos', 'as' => 'pos.'], function () {

        Route::group(['prefix' => 'pembelian'], function () {
            Route::post('/transaksi_pembelian', 'PembelianController@store_transaksi')->name('transaksi_pembelian.store');
            Route::post('/detail_transaksi_pembelian', 'PembelianController@store_detail')->name('detail_transaksi_pembelian.store');
            Route::get('/data_barang', 'PembelianController@get_data_barang')->name('pembelian.databarang');
            Route::get('/save_transaksi_pembelian', 'PembelianController@save_data_transaksi')->name('pembelian.save');
        });
        Route::group(['prefix' => 'penjualan'], function () {
            Route::post('/transaksi_penjualan', 'PenjualanController@store_transaksi')->name('transaksi_penjualan.store');
            Route::post('/detail_transaksi_penjualan', 'PenjualanController@store_detail')->name('detail_transaksi_penjualan.store');
            Route::get('/data_barang', 'PenjualanController@get_data_barang')->name('penjualan.databarang');
            Route::get('/save_transaksi_penjualan', 'PenjualanController@save_data_transaksi')->name('penjualan.save');
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
