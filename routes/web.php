<?php

use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', function () {
    return view('home');
})->name('home');


// Ruta para imagenes
Route::get('/imagenes/{nombreImagen}', function ($nombreImagen) {
    $rutaImagen = public_path('imagenes/' . $nombreImagen);
    if (file_exists($rutaImagen)) {
        return response()->file($rutaImagen);
    } else {
        abort(404);
    }
})->name('imagen.usuario');
