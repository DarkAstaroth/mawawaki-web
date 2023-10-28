<?php


use App\Http\Controllers\core\InvitadoController;
use App\Http\Controllers\core\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;


// Rutas de autenticación (pero no requieren sesión verificada)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('autenticacion.login');
    })->name('login');
    Route::get('/registro', [InvitadoController::class, 'registroEmail'])->name('login.registro');
    Route::get('/reset-pass', [InvitadoController::class, 'resetPass'])->name('reset.pass');
    Route::post('/crear-cuenta', [InvitadoController::class, 'crearUsuarioEmail'])->name('usuario.crear');
    Route::post('/verificar-login', [UsuarioController::class, 'login'])->name("verificar.login");
});

Route::post('/reset-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', __($status))
        : back()->with('error', __($status));
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('autenticacion.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::patch('/update-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);


    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', __($status))
        : back()->with('error', __($status));
})->middleware('guest')->name('password.update');

Route::get('/logout', [UsuarioController::class, 'logout'])->name('logout');

// Rutas de solicitud y verificacion de estado de cuenta
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('setup/invitado', InvitadoController::class);
    Route::get('verificar/cuenta', [UsuarioController::class, 'verificarCuenta'])->name('verificar.cuenta');
});
