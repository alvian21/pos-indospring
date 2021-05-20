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

Route::get('/', function () {
    return view('welcome');
});



Route::group(['namespace' => 'Frontend', 'prefix' => 'admin'], function () {
    Route::resource('dashboard', 'DashboardController');


    //POS-transaksi
    Route::group(['namespace' => 'POS\Transaksi', 'prefix' => 'pos', 'as' => 'pos.'], function () {
        Route::resource('pembelian', 'PembelianController');
        Route::resource('penjualan', 'PenjualanController');
        Route::resource('tfantartoko', 'TfAntarTokoController');
        Route::resource('stockopname', 'StockOpnameController');
        Route::resource('stockhilang', 'StockHilangController');
    });
});
