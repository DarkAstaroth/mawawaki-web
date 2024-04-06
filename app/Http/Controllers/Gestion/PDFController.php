<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Gestion\Personal;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function usuariosSistema()
    {
        $usuarios = User::with('persona', 'roles')
            ->where('estado', true)
            ->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'admin');
            })
            ->get();


        $usersData = [];
        $fila = 1;

        foreach ($usuarios as $usuario) {
            $roles = $usuario->roles->reject(function ($role) {
                return $role->name === 'personal' || $role->name === 'invitado';
            })->pluck('name')->implode(', ');

            $usersData[] = [
                strtoupper($usuario->persona->paterno),
                strtoupper($usuario->persona->materno),
                strtoupper($usuario->persona->nombre),
                $usuario->email,
                $roles
            ];
        }

        $usersData = collect($usersData)->sortBy(function ($user) {
            return $user[0];
        })->values()->all();

        $cabecera = [["Nro", "Paterno", "Materno", "Nombre", "Correo", "Roles"]];

        foreach ($usersData as $key => &$userData) {
            array_unshift($userData, $fila++);
        }

        $resultado = [
            'cabecera' => $cabecera,
            'usuarios' => $usersData,
        ];

        return response()->json($resultado);
    }
}
