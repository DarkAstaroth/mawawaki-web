<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;

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
                Auth::login($usuarioExistente);
                if($usuarioExistente->hasRole('invitado')){
                    return redirect('/setup/invitado');
                }
                return redirect('/dashboard');
            } else {
                session()->flush();
                $nuevoUsuario = User::create([
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'profile_photo_path' => $usuario->avatar,
                    'gauth_id' => $usuario->id,
                    'gauth_type' => 'google',
                    'password' => encrypt('fhccfnxdy24!')
                ]);
                $nuevoUsuario->assignRole('invitado');
                Auth::login($nuevoUsuario);

                return redirect('/setup/invitado');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
