<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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



Route::get('/generate-link', function () {
    Artisan::call('storage:link');
    return response()->json(['message' => 'Enlace simbólico generado correctamente'], 200);
});


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('event:clear');
    Artisan::call('optimize:clear');
    return response()->json(['message' => 'Caché limpiada correctamente'], 200);
});
