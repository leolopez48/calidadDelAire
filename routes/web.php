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
    return view('registros.index');
});
Route::get('mapas', function () {
    return view('mapas');
});
Route::get('crudMarker', function () {
    return view('crudMarker');
});

Route::get('crearExcel', 'RegistrosController@crearExcel');
Route::get('id', 'RegistrosController@id');
Route::get('crearCsv', 'RegistrosController@crearCsv');
Route::get('csv/{id}/{fechaIni}/{fechaFin}', 'RegistrosController@ejecutar');
Route::get('csv/{fechaIni}/{fechaFin}', 'RegistrosController@ejecutar1');
Route::get('fullBD', 'RegistrosController@fullBD');
Route::get('proyecciones', function () {
    return view('proyecciones');
});

//Group y middleware se utilizan para la validación de los usuarios
//y que estos no puedan acceder a esta cuenta
Route::group(['middleware' => 'auth'], function(){

    Route::get('adminMarkers', function () {
    return view('adminMarkers');
    });
    Route::get('adminMarkers/modificar', function () {
        $data = 'modificar';
        return view('adminMarkers')->with('datos',$data);
    });
    Route::get('adminMarkers/agregar', function () {
        $data = 'agregar';
        return view('adminMarkers')->with('datos',$data);
    });
    Route::get('adminMarkers/eliminar', function () {
        $data = 'eliminar';
        return view('adminMarkers')->with('datos',$data);
    });
    Route::get('adminMarkers/restaurar', function () {
        $data = 'restaurar';
        return view('adminMarkers')->with('datos',$data);
    });
});

Route::get('registros/cargar', 'RegistrosController@cargar');
Route::get('registros/restaurar', 'RegistrosController@restaurar');
Route::get('registros/validar', 'RegistrosController@validar');

Auth::routes(['register' => false], ['password.request' => false]);

Route::resource('registros', 'RegistrosController');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('nosotros', function () {
    return view('nosotros');
});

Route::get('inicio', function () {
    return view('inicio');
});