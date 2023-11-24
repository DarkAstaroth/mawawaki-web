<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gestion\PacienteResource;
use App\Models\Gestion\Paciente;
use App\Models\Persona;
use Illuminate\Http\Request;

class PacientesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('gestion.pacientes.index');
    }

    public function obtenerPacientes(Request $request)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');
        $parametro = $request->get('parametro');

        $pacientesQuery = Paciente::query()
            ->with(['persona', 'cliente']) // Cargar las relaciones necesarias
            ->whereHas('persona', function ($query) use ($busqueda) {
                // Filtrar por nombre de persona
                $query->where('nombre', 'like', "%$busqueda%");
            });

        // Resto de la lógica de filtrado según $parametro y otras condiciones...

        $pacientes = $pacientesQuery->paginate($porPagina, ['*'], 'page', $pagina);

        $pacientesResource = PacienteResource::collection($pacientes);

        return response()->json([
            'pacientes' => $pacientesResource,
            'paginacion' => [
                'total' => $pacientes->total(),
                'porPagina' => $pacientes->perPage(),
                'paginaActual' => $pacientes->currentPage(),
                'ultimaPagina' => $pacientes->lastPage(),
                'desde' => $pacientes->firstItem(),
                'hasta' => $pacientes->lastItem(),
            ],
        ]);
    }

    public function verificarPaciente($id)
    {
        $paciente = Paciente::findOrFail($id);

        // Verifica si el paciente ya está verificado
        if ($paciente->verificado) {
            return response()->json(['message' => 'El paciente ya está verificado'], 422);
        }
        // Actualiza el campo 'verificado' a true
        $paciente->update(['verificado' => true]);
        $paciente->update(['estado' => true]);

        return response()->json(['message' => 'Paciente verificado correctamente']);
    }

    public function cambiarEstado($id)
    {
        $paciente = Paciente::find($id);

        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        // Cambiar el estado
        $paciente->estado = !$paciente->estado;
        $paciente->save();

        return response()->json(['message' => 'Estado actualizado correctamente']);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
