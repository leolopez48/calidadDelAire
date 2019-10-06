<?php

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

Route::get('mapas', function () {
    return view('mapas');
});

Route::get('crudMarker', function () {
    return view('crudMarker');
});

Route::get('adminMarkers', function () {
    return view('adminMarkers');
});

Auth::routes(['register' => false], ['password.request' => false]);

Route::resource('registros', 'RegistrosController');

Route::get('/home', 'HomeController@index')->name('home');
