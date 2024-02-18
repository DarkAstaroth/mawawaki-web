<?php

namespace App\Http\Controllers\core;

use App\Http\Controllers\Controller;
use App\Models\Gestion\Cliente;
use App\Models\Gestion\Personal;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class InvitadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('autenticacion.setup');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('verificar.cuenta')->with('error', 'Usuario no encontrado');
        }

        $user->update([
            'nombres' => $request->nombres,
            'paterno' => $request->paterno,
            'materno' => $request->materno,
            'solicitud' => 1,
        ]);

        if (auth()->check()) {
            return redirect()->route('verificar.cuenta')->with('success', 'Solicitud enviada con éxito');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function registroEmail()
    {
        return view('autenticacion.registro');
    }


    public function crearUsuarioEmail(Request $request)
    {
        $request->validate([
            'nombres' => 'required',
            'paterno' => 'required',
            'materno' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'toc' => 'accepted',
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'toc.accepted' => 'Debe aceptar los términos y condiciones.',
        ]);

        $nuevaPersona = Persona::create([
            'nombre' => $request->nombres,
            'paterno' => $request->paterno,
            'materno' => $request->materno,
        ]);

        $nuevoUsuario = User::create([
            'email' => $request->email,
            'gauth_type' => 'email',
            'password' => bcrypt($request->input('password')),
            'persona_id' => $nuevaPersona->id,
        ]);

        $nuevoUsuario->assignRole('invitado');

        return redirect()->route('login')->with('success', 'Usuario registrado con éxito. Debes iniciar sesión');
    }


    public function resetPass()
    {
        return view('autenticacion.forgot');
    }

    public function enviarSolicitud(Request $request)
    {
        $usuario = User::find($request->usuario_id,);

        if ($request->tipo === 'cliente') {

            Cliente::create([
                'UsuarioID' => $request->usuario_id,
                'ocupacion' => $request->datos['ocupacion'],
                'materno' => $request->materno,
            ]);

            $usuario->update([
                'solicitud' => 1,
                'tipo_solicitud' => 'cliente',
            ]);

            return response()->json([
                'success' => 'Cliente creado con éxito',
            ]);
        }

        if ($request->tipo === 'personal') {

            Personal::create([
                'UsuarioID' => $request->usuario_id,
                'universidad' => $request->datos['universidad'],
                'facultad' => $request->datos['facultad'],
                'carrera' => $request->datos['carrera'],
            ]);

            $usuario->update([
                'solicitud' => 1,
                'tipo_solicitud' => 'personal',

            ]);

            return response()->json([
                'success' => 'Cliente creado con éxito',
            ]);
        }
    }
}
