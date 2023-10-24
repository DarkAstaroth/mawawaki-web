<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Configuraciones\RolController;
use App\Http\Controllers\Configuraciones\ModuloController;
use App\Http\Controllers\Configuraciones\PermisoController;
use App\Http\Controllers\core\UsuarioController;
use App\Http\Controllers\Gestion\CaballosController;

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
