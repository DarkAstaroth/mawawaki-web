<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

// rutas para gestionar emision y recepcion de correos.

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request, $id) {
//     Auth::loginUsingId($id);
//     $request->fulfill();
//     return redirect()->route('invitado.index')->with('success', 'Email verificado correctamente');
// })->name('verification.verify');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request, $id) {
    $user = User::find($id);
    if (!$user) {
        return redirect()->route('invitado.index')->with('error', 'Usuario no encontrado');
    }

    Auth::loginUsingId($id);
    // Manejar otros casos, como usuarios desactivados, si es necesario
    $request->fulfill();
    return redirect()->route('invitado.index')->with('success', 'Email verificado correctamente');
})->name('verification.verify');
