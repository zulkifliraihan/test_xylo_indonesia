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

Route::group(['prefix' => 'admin', 'middleware' => ['role:admin'], 'as' => 'admin.'], function () {

    Route::group(['as' => 'parking.'], function () {

        Route::get('record-parking', 'Dashboard\Admin\AdminRecordParkingController@index')->name('index');

        Route::group(['prefix' => 'export', 'as' => 'export.'], function () {
            Route::get('excel', 'Dashboard\Admin\AdminRecordParkingController@exportExcel')->name('excel');
        });
    });
});

