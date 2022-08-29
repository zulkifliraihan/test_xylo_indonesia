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

Route::group(['prefix' => 'staff', 'middleware' => ['role:staff'], 'as' => 'staff.'], function () {

    Route::group(['as' => 'parking.'], function () {

        Route::get('record-parking', 'Dashboard\Staff\RecordParkingController@index')->name('index');
        Route::post('record-parking/enter', 'Dashboard\Staff\RecordParkingController@enter')->name('enter');
        Route::post('record-parking/out', 'Dashboard\Staff\RecordParkingController@out')->name('out');
        Route::post('record-parking/out/payment', 'Dashboard\Staff\RecordParkingController@payment')->name('payment');
    });
});

