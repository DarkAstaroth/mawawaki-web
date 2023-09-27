<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function signInwithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackToGoogle()
    {
        try {
            $usuario = Socialite::driver('google')->user();
            $usuarioExistente = User::where('gauth_id', $usuario->id)->first();

            if ($usuarioExistente) {
                session()->flush();
                Auth::login($usuarioExistente);
                if ($usuarioExistente->hasRole('invitado') && $usuarioExistente->solicitud !== 1) {
                    return redirect('/setup/invitado');
                }
                return redirect('/dashboard');
            } else {
                session()->flush();
                $imagenUrlExterna = $usuario->avatar;
                $contenidoImagen = file_get_contents($imagenUrlExterna);
                $nombreImagen = uniqid() . '.jpg';
                $rutaLocalImagen = public_path('assets/imagenes/' . $nombreImagen);
                file_put_contents($rutaLocalImagen, $contenidoImagen);

                $nuevoUsuario = User::create([
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'profile_photo_path' => 'assets/imagenes/' . $nombreImagen,
                    'gauth_id' => $usuario->id,
                    'gauth_type' => 'google',
                    'password' => bcrypt('fhccfnxdy24!')
                ]);
                $nuevoUsuario->assignRole('invitado');
                Auth::login($nuevoUsuario);

                return redirect('/setup/invitado');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->route('login')->with('error', 'El correo electrónico ya está en uso. Por favor, utiliza otro correo.');
            }
            return redirect()->route('login')->with('error', 'Ha ocurrido un error en el proceso de registro. Por favor, inténtalo de nuevo.');
        }
    }
}
