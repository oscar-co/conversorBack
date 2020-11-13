<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/unidades/{magnitud}', 'App\Http\Controllers\UnidadController@index');
Route::post('/cambio', 'App\Http\Controllers\CambioController@cambio');
Route::post('/calculo', 'App\Http\Controllers\PatronController@calculoMain');
Route::post('/calculoIncertidumbre', 'App\Http\Controllers\PatronController@calculoIncertidumbre');
//Route::get('/prueba', 'App\Http\Controllers\CambioController@prueba');
