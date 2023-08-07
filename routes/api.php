<?php

use App\Http\Controllers\Configuraciones\ModuloController;
use App\Http\Controllers\Configuraciones\PermisoController;
use App\Http\Controllers\Configuraciones\RolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('roles', [RolController::class, 'obtenerRoles']);
Route::get('roles/{id}', [RolController::class, 'edit']);
Route::post('roles/agregar', [RolController::class, 'store']);
Route::put('roles/{id}', [RolController::class, 'update']);
Route::delete('roles/{id}', [RolController::class, 'destroy']);


Route::get('modulos', [ModuloController::class, 'obtenerModulos']);
Route::get('modulos/{id}', [ModuloController::class, 'edit']);
Route::post('modulos/agregar', [ModuloController::class, 'store']);
Route::put('modulos/{id}', [ModuloController::class, 'update']);
Route::delete('modulos/{id}', [ModuloController::class, 'destroy']);

// Lista de modulos con sus respectivos permisos
Route::get('modulo/permisos', [ModuloController::class, 'modulosPermisos']);


Route::get('permisos', [PermisoController::class, 'obtenerPermisos']);
Route::get('permisos/{id}', [PermisoController::class, 'edit']);
Route::post('permisos/agregar', [PermisoController::class, 'store']);
Route::put('permisos/{id}', [PermisoController::class, 'update']);
Route::delete('permisos/{id}', [PermisoController::class, 'destroy']);


// asignacion de permisos
Route::post('asignar/permisos/rol/{rolId}',[RolController::class,'asignarPermisos']);
