<?php

use App\Http\Controllers\Configuraciones\RolController;
use App\Http\Controllers\Configuraciones\ModuloController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');;

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('autenticacion.login');
    })->name('login');
});

Route::resource('dashboard/roles', RolController::class);
Route::resource('dashboard/modulos', ModuloController::class);

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

Route::get('auth/google', [GoogleController::class, 'signInwithGoogle']);
Route::get('callback/google', [GoogleController::class, 'callbackToGoogle']);
