<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Http\Resources\PacienteResource;
use App\Models\Gestion\Cliente;
use App\Models\Gestion\Paciente;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function listaPacientes(string $id)
    {
        $usuario = User::with('persona', 'cliente')->findOrFail($id);
        return view('clientes.pacientes', compact('usuario'));
    }

    public function obtenerPacientes(Request $request, $id)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');
        $parametro = $request->get('parametro');

        $cliente = Cliente::where('UsuarioID', $id)->firstOrFail();

        $pacientesQuery = $cliente->pacientes()->with(['persona']);

        if ($parametro === 'todos') {
            // Mantener todos los pacientes
        }

        if ($parametro === 'activos') {
            $pacientesQuery->where('estado', 1);
        }

        if ($parametro === 'inactivos') {
            $pacientesQuery->where('estado', 0);
        }

        if ($busqueda) {
            $busquedaSinEspacios = str_replace(' ', '', $busqueda);
            $pacientesQuery->where(function ($query) use ($busquedaSinEspacios) {
                $query->whereRaw("REPLACE(personas.nombre, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                    ->orWhereRaw("REPLACE(personas.paterno, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                    ->orWhereRaw("REPLACE(personas.materno, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
            });
        }

        $pacientes = $pacientesQuery->paginate($porPagina, ['*'], 'page', $pagina);

        $pacientesResource = PacienteResource::collection($pacientes);

        return response()->json([
            'pacientes' => $pacientesResource,
            'cliente' => $cliente,
            'paginacion' => [
                'total' => $pacientes->total(),
                'porPagina' => $pacientes->perPage(),
                'paginaActual' => $pacientes->currentPage(),
                'ultimaPagina' => $pacientes->lastPage(),
                'desde' => $pacientes->firstItem(),
                'hasta' => $pacientes->lastItem()
            ]
        ]);
    }

    public function solicitudPaciente(Request $request)
    {
        // Validación de datos
        $request->validate([
            'nombre' => 'required|string',
            'paterno' => 'required|string',
            'materno' => 'required|string',
            'ci' => 'required|string|unique:personas',
            'fechaNacimiento' => 'required|date',
            'tipoPaciente' => 'required|in:interno,externo',
            'estadoSalud' => 'nullable|string',
            'precondicion' => 'nullable|string',
            'contactoEmergenciaNombre' => 'nullable|string',
            'contactoEmergenciaTelefono' => 'nullable|string',
        ]);

        // Crear una nueva persona
        $persona = Persona::create([
            'nombre' => $request->input('nombre'),
            'paterno' => $request->input('paterno'),
            'materno' => $request->input('materno'),
            'ci' => $request->input('ci'),
            'fecha_nacimiento' => strtotime($request->input('fechaNacimiento')),
        ]);

        // Crear un nuevo paciente asociado a la persona
        $paciente = Paciente::create([
            'cliente_id' => $request->cliente,
            'persona_id' => $persona->id,
            'tipo_paciente' => $request->input('tipoPaciente'),
            'estado_salud' => $request->input('estadoSalud'),
            'precondicion' => $request->input('precondicion'),
            'contacto_emergencia_nombre' => $request->input('contactoEmergenciaNombre'),
            'contacto_emergencia_telefono' => $request->input('contactoEmergenciaTelefono'),
        ]);

        return response()->json(['message' => 'Paciente registrado con éxito', 'paciente' => $paciente]);
    }
}
