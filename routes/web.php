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

//Routes only logged in users can access
Route::group(['middleware' => 'auth'], function () {

    //Upload csv of meter readings
    Route::get('meter/{meter}/reading/upload_csv', 'App\Http\Controllers\MeterReadingController@uploadCSV')->name('upload_csv');
    Route::get('meter/{meter}/reading/force_destroy', 'App\Http\Controllers\MeterReadingController@forceDestroy')->name('force_destroy');

    //Resource routes
    Route::resource('meter', 'App\Http\Controllers\MeterController');
    Route::resource('meter.reading', 'App\Http\Controllers\MeterReadingController')->except('index', 'show');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
