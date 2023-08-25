<?php

use App\Http\Controllers\core\InvitadoController;
use App\Http\Controllers\core\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Rutas públicas
Route::get('/', function () {
    return view('home');
})->name('home');

// Rutas de autenticación requerida
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.solicitud',
    'check.verificacion'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Rutas de autenticación (pero no requieren sesión verificada)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('autenticacion.login');
    })->name('login');
    Route::get('/registro', [InvitadoController::class, 'registroEmail'])->name('login.registro');
    Route::post('/crear-cuenta', [InvitadoController::class, 'crearUsuarioEmail'])->name('usuario.crear');
});

Route::get('/logout', [UsuarioController::class, 'logout']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:invitado',
])->group(function () {
    Route::resource('setup/invitado', InvitadoController::class);
    Route::get('verificar/cuenta', [UsuarioController::class, 'verificarCuenta'])->name('verificar.cuenta');
});


// Rutas de Google
Route::get('auth/google', [GoogleController::class, 'signInwithGoogle']);
Route::get('callback/google', [GoogleController::class, 'callbackToGoogle']);


Route::get('/imagenes/{nombreImagen}', function ($nombreImagen) {
    $rutaImagen = public_path('imagenes/' . $nombreImagen);
    if (file_exists($rutaImagen)) {
        return response()->file($rutaImagen);
    } else {
        abort(404);
    }
})->name('imagen.usuario');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');
