<?php

use App\Http\Controllers\Configuraciones\ModuloController;
use App\Http\Controllers\Configuraciones\PermisoController;
use App\Http\Controllers\Configuraciones\RolController;
use App\Http\Controllers\core\InvitadoController;
use App\Http\Controllers\core\UsuarioController;
use App\Http\Controllers\Gestion\CaballosController;
use App\Http\Controllers\Gestion\AsistenciasController;
use App\Http\Controllers\Gestion\DocumentacionController;
use App\Http\Controllers\Gestion\EventosController;
use App\Http\Controllers\Gestion\QRcontroller;
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
Route::post('asignar/permisos/rol/{rolId}', [RolController::class, 'asignarPermisos']);


// Api para usuarios
Route::get('usuarios', [UsuarioController::class, 'obtenerUsuarios']);
Route::get('fichasUsuarios', [UsuarioController::class, 'fichasUsuarios']);
Route::post('/asistencias/usuario/{id}', [AsistenciasController::class, 'obtenerAsistencias']);

Route::put('verificar/usuario/{id}', [UsuarioController::class, 'verificarUsuario']);
Route::put('estado/usuario/{id}', [UsuarioController::class, 'cambiarEstado']);
Route::put('sincronizar/rol/usuario/{id}', [UsuarioController::class, 'sincronizarRoles']);

// Api para caballos
Route::get('caballos', [CaballosController::class, 'obtenerCaballos']);
Route::post('caballo/agregar', [CaballosController::class, 'agregarCaballo']);
Route::patch('caballo/{id}', [CaballosController::class, 'editarCaballo']);
Route::delete('caballo/{id}', [CaballosController::class, 'destroy']);

// Api para eventos
Route::get('eventos', [EventosController::class, 'obtenerEventos']);
Route::post('evento', [EventosController::class, 'store']);
Route::get('eventos/usuario/{id}', [EventosController::class, 'obtenerEventosUsuario']);


// Api para gestion de QRs
Route::post('qr/generar', [QRcontroller::class, 'generarQR']);
Route::get('qr/evento/{id}', [QRcontroller::class, 'cargarQRS']);

// Api para invitados
Route::post('invitado/enviar/solicitud', [InvitadoController::class, 'enviarSolicitud']);

// Api para documentacion 
Route::post('subir-archivo/{usuarioId}', [DocumentacionController::class, 'subirArchivo']);
Route::get('obtener-documentacion/{usuario_id}', [DocumentacionController::class, 'obtenerDocumentosUsuario']);
Route::get('tipos-documento', [DocumentacionController::class, 'obtenerTiposDocumento']);
