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
        $usuarios = User::with('persona', 'roles')->get();
        $usersData = [];
        $fila = 1; // Inicializar el contador de fila

        foreach ($usuarios as $usuario) {
            $roles = $usuario->roles->pluck('name')->implode(', ');
            $usersData[] = [
                strtoupper($usuario->persona->paterno),
                strtoupper($usuario->persona->materno),
                strtoupper($usuario->persona->nombre),
                $usuario->email,
                $roles
            ];
        }

        // Ordenar la colección de usuarios por apellido paterno
        $usersData = collect($usersData)->sortBy(function ($user) {
            return $user[0]; // apellido paterno
        })->values()->all();

        // Cabecera
        $cabecera = [["Nro", "Paterno", "Materno", "Nombre", "Correo", "Roles"]];

        // Añadir número de fila y agregar cabecera a los datos de usuarios
        foreach ($usersData as $key => &$userData) {
            array_unshift($userData, $fila++); // Agregar número de fila al principio del array
        }

        // Devolver la cabecera y los datos de usuarios
        $resultado = [
            'cabecera' => $cabecera,
            'usuarios' => $usersData,
        ];

        return response()->json($resultado);
    }
}
