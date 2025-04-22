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

// Ruta para obtener unidades por magnitud
Route::get('/units/{magnitud}', [UnidadController::class, 'index'])->name('units.byMagnitude');
// Conversión entre unidades
Route::post('/convert', [CambioController::class, 'cambio'])->name('conversion.convert');
// Cálculo de patrones recomendados
Route::post('/patterns/recommend', [PatronController::class, 'calculoMain'])->name('patterns.recommend');
// Cálcul de incertidumbre para un Patrón
Route::post('/patterns/uncertainty', [PatronController::class, 'calculoIncertidumbre'])->name('patterns.uncertainty');

//Route::get('/prueba', 'App\Http\Controllers\CambioController@prueba');
