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



Route::get('/', 'InicioController@index')->name('inicio');
Route::get('/archivos/procesar', 'InicioController@readFiles')->name('readFiles');
Route::get('/archivos/subir/{code}', 'InicioController@uploadFile')->name('uploadFile');
Route::get('/archivos/guardar', 'InicioController@saveFile')->name('saveFile');

/*Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth:sanctum', 'verified'])->group(function(){
	Route::get('/inicio', function () {
	    return view('dashboard');
	})->name('inicio');

	Route::get('/doctores', function(){
		return view('doctores');
	})->name('doctores');
	
	Route::get('/pacientes', function(){
		return view('pacientes');
	})->name('pacientes');
	
	Route::get('/citas', function(){
		return view('citas');
	})->name('citas');
});

*/