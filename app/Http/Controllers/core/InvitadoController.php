<?php

namespace App\Http\Controllers\core;

use App\Http\Controllers\Controller;
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

        if ($user) {
            $user->update([
                'nombres' => $request->input('nombres'),
                'paterno' => $request->input('paterno'),
                'materno' => $request->input('materno'),
                'solicitud' => 1,
                'password' => bcrypt($request->input('password')),
            ]);

            return redirect()->route('login')->with('success', 'Usuario actualizado correctamente');
        }

        return redirect()->route('dashboard')->with('error', 'Usuario no encontrado');
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

        $nuevoUsuario = User::create([
            'nombres' => $request->nombres,
            'paterno' => $request->paterno,
            'materno' => $request->materno,
            'email' => $request->email,
            'gauth_type' => 'email',
            'solicitud' => 1,
            'password' => bcrypt($request->input('password')),
        ]);
        $nuevoUsuario->assignRole('invitado');
        Auth::login($nuevoUsuario);

        return redirect()->route('dashboard')->with('success', 'Registro guardado con éxito');
    }
}
