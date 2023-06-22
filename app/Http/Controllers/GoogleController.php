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

                return redirect('/dashboard');
            } else {
                $nuevoUsuario = User::create([
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'gauth_id' => $usuario->id,
                    'gauth_type' => 'google',
                    'password' => encrypt('admin@123')
                ]);

                Auth::login($nuevoUsuario);

                return redirect('/dashboard');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
