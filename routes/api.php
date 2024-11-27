<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistorialTransitoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
});

// Rutas del CRUD de historial-transito, protegidas con autenticación
Route::group(['middleware' => ['api', 'auth:api']], function () {
    Route::post('historial-transito-data', [HistorialTransitoController::class, 'index']);
    Route::post('historial-transito', [HistorialTransitoController::class, 'store']);
    Route::get('historial-transito/{id}', [HistorialTransitoController::class, 'show']);
    Route::put('historial-transito/{id}', [HistorialTransitoController::class, 'update']);
    Route::delete('historial-transito/{id}', [HistorialTransitoController::class, 'destroy']);

    // Rutas adicionales para ver registros eliminados y restaurarlos
    Route::get('historial-transito/trashed', [HistorialTransitoController::class, 'trashed']);
    Route::patch('historial-transito/{id}/restore', [HistorialTransitoController::class, 'restore']);

    // Ruta para procesar la importación
    Route::post('historial-transito/import', [HistorialTransitoController::class, 'import'])->name('import');
});
