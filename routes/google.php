<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;

// Rutas de Google
Route::get('auth/google', [GoogleController::class, 'signInwithGoogle']);
Route::get('callback/google', [GoogleController::class, 'callbackToGoogle']);
