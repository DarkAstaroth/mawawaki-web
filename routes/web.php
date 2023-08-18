<?php

use App\Http\Controllers\core\InvitadoController;
use App\Http\Controllers\core\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;

// Rutas públicas
Route::get('/', function () {
    return view('home');
})->name('home');

// Rutas de autenticación requerida
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/logout', [UsuarioController::class, 'logout']);
});

// Rutas de autenticación (pero no requieren sesión verificada)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('autenticacion.login');
    })->name('login');
});



Route::group(['middleware' => ['role:invitado']], function () {
    Route::resource('setup/invitado', InvitadoController::class);
});

// Rutas de Google
Route::get('auth/google', [GoogleController::class, 'signInwithGoogle']);
Route::get('callback/google', [GoogleController::class, 'callbackToGoogle']);

