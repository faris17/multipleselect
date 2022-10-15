<?php

use App\Http\Controllers\HomeController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(HomeController::class)->group(function () {
    //halaman awal
    Route::get('/', 'create');

    //get data dropdown
    Route::get('selectmhs', 'selectmhs')->name('select.mhs');
    Route::get('selectmk/{id}', 'selectmk')->name('select.mk');

    //save
    Route::post('store', 'store')->name('save.form');

    //get data
    Route::get('ngampumk/{id}', 'getngampu')->name('getngampumk');

    Route::get('nilai', 'index')->name('list.nilai');
});
