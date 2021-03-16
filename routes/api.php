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
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\InscripcionCarreraController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\TipoSalaController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/setup', 'App\Http\Controllers\Setup@setup');
Route::get('/issetup', 'App\Http\Controllers\Setup@issetup');

Route::get('/puede-ingresar/{sala}/{cedula}', 'App\Http\Controllers\SalaController@puedeIngresar');

Route::resource('area', AreaController::class);
Route::resource('carrera', CarreraController::class);
Route::resource('ingreso', IngresoController::class);
Route::resource('inscripcioncarrera', InscripcionCarreraController::class);
Route::resource('persona', PersonaController::class);
Route::resource('rol', RolController::class);
Route::resource('sala', SalaController::class);
Route::resource('tiposala', TipoSalaController::class);
