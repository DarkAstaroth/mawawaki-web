<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Configuraciones\RolController;
use App\Http\Controllers\Configuraciones\ModuloController;
use App\Http\Controllers\Configuraciones\PermisoController;
use App\Http\Controllers\core\UsuarioController;
use App\Http\Controllers\Gestion\CaballosController;
use App\Http\Controllers\Gestion\AsistenciasController;
use App\Http\Controllers\Gestion\AvisoController;
use App\Http\Controllers\Gestion\ClienteController;
use App\Http\Controllers\Gestion\EventosController;
use App\Http\Controllers\Gestion\PacientesController;
use App\Http\Controllers\Gestion\ReportesController;
use App\Http\Controllers\Gestion\TerapiasController;

// Rutas de autenticación requerida
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


// Rutas para configuraciones generales
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {

    Route::get('dashboard/config/general', [UsuarioController::class, 'vistaConfiguracion'])->name('config.general');
});

// Rutas de roles
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::resource('dashboard/roles', RolController::class);
    Route::get('dashboard/permiso/rol/{id}', [RolController::class, 'PermisoRol'])->name('permiso.rol');
});

// Rutas de módulos (requieren autenticación)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::resource('dashboard/modulos', ModuloController::class);
    Route::get('dashboard/avisos', [ModuloController::class, 'verAvisos'])->name('avisos.index');
    Route::get('dashboard/gestion/avisos', [AvisoController::class, 'index'])->name('avisos.gestion');
    Route::get('avisos/crear', [AvisoController::class, 'crearAviso'])->name('avisos.crear');;
});

// Rutas de permisos (requieren autenticación)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::resource('dashboard/permisos', PermisoController::class);
});


// Rutas de usuarios (requieren autenticación)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::resource('dashboard/usuarios', UsuarioController::class);
    Route::get('dashboard/usuario/control/{id}', [UsuarioController::class, 'UsuarioControl'])->name('usuario.control');
    Route::get('dashboard/usuario/perfil/{id}', [UsuarioController::class, 'PerfilUsuario'])->name('usuario.perfil');
});

// Rutas de caballos (requiere autenticación)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::resource('dashboard/caballos', CaballosController::class);
    Route::get('dashboard/caballo/nuevo', [CaballosController::class, 'create'])->name('caballo.nuevo');
    Route::get('dashboard/caballo/{id}', [CaballosController::class, 'show'])->name('caballo.detalle');
});

// Rutas de eventos
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::resource('dashboard/eventos', EventosController::class);
    Route::get('dashboard/evento/{id}', [EventosController::class, 'DetalleEvento'])->name('evento.detalle');
    Route::get('dashboard/evento/qrs/{id}', [EventosController::class, 'DetalleEventoQR'])->name('evento.qrs');
});

//Ruta para asistencias
Route::middleware([
    'check.auth',
])->group(function () {
    Route::get('asistencia/{id}', [AsistenciasController::class, 'registrarAsistencia'])->name('registrar.aistencia');
});

//Ruta para asistencias
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::resource('dashboard/pacientes', PacientesController::class);
});

//Ruta para terapias
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::resource('dashboard/terapias', TerapiasController::class);
});

//Ruta para reportes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::resource('dashboard/reportes', ReportesController::class);
});




//================================================
// RUTAS PARA CLIENTES
//================================================

// CLIENTE
// pacientes asignados
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::get('dashboard/pacientes/cliente/{id}', [ClienteController::class, 'listaPacientes'])->name('cliente.pacientes');
});


// PERSONAL
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::get('dashboard/pacientes', [PacientesController::class, 'index'])->name('pacientes.index');
});


//================================================
// RUTAS PARA ACTIVIDADES
//======


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::get('dashboard/actividades/usuario/{id}', [UsuarioController::class, 'actividadesUsuario'])->name('usuario.actividades');
});


//================================================
// RUTAS PARA ACTIVIDADES
//======

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion',
    'check.estado'
])->group(function () {
    Route::get('dashboard/servicio/{id}', [TerapiasController::class, 'verServicio'])->name('servicio.detalles');
});
