<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gestion\EventosResource;
use App\Models\Gestion\Asistencia;
use App\Models\Gestion\Evento;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gestion.eventos.index');
    }


    public function obtenerEventos()
    {

        $Eventos = Evento::all();
        $usuariosResource = EventosResource::collection($Eventos);

        return response()->json([
            'eventos' => $usuariosResource,
            'total' => count($usuariosResource),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        return view('gestion.eventos.crear');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $nuevoEvento = new Evento;
        $nuevoEvento->nombre = $request->input('nombre');
        $nuevoEvento->fecha_hora_inicio = strtotime($request->input('fechaInicio'));
        $nuevoEvento->fecha_hora_fin = strtotime($request->input('fechaFin'));
        $nuevoEvento->lugar = $request->input('lugar');
        $nuevoEvento->latitud = $request->input('latitud');
        $nuevoEvento->longitud = $request->input('longitud');
        $nuevoEvento->descripcion = $request->input('descripcion');
        $nuevoEvento->tipo = $request->input('tipoEvento');
        $nuevoEvento->tipo = $request->input('tipoEvento');
        $nuevoEvento->solo_ingreso = $request->input('soloIngreso');
        $nuevoEvento->usuarios_ids = json_encode($request->input('usuariosFiltro'));
        $nuevoEvento->save();

        return response()->json(['message' => 'Evento creado con éxito'], 201);
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'El evento no fue encontrado'], 404);
        }

        // dd($request->input('soloIngreso'));
        $evento->nombre = $request->input('nombre');
        $evento->fecha_hora_inicio = strtotime($request->input('fechaInicio'));
        $evento->fecha_hora_fin = strtotime($request->input('fechaFin'));
        $evento->lugar = $request->input('lugar');
        $evento->latitud = strval($request->input('latitud'));
        $evento->longitud = strval($request->input('longitud'));
        $evento->descripcion = $request->input('descripcion');
        $evento->tipo = $request->input('tipoEvento');
        $evento->solo_ingreso = $request->input('soloIngreso');
        $evento->solo_ingreso = $request->input('soloIngreso');
        $evento->usuarios_ids = json_encode($request->input('usuariosFiltro'));
        $evento->save();

        return response()->json(['message' => 'Evento modificado con éxito'], 200);
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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function DetalleEvento(string $id)
    {
        $evento = Evento::findOrFail($id);
        return view('gestion.eventos.detalle', compact('evento'));
    }

    public function DetalleEventoQR(string $id)
    {
        $evento = Evento::findOrFail($id);
        return view('gestion.eventos.qr', compact('evento'));
    }

    public function obtenerEventosUsuario(string $id)
    {
        $eventos = Evento::whereHas('asistencias', function ($query) use ($id) {
            $query->where('UsuarioID', $id);
        })->select('id', 'nombre', 'solo_ingreso')->get();

        $result = $eventos->map(function ($evento) {
            return [
                'value' => $evento->id,
                'name' => $evento->nombre,
                'solo_ingreso' => $evento->solo_ingreso
            ];
        });

        return response()->json($result);
    }

    public function verificarEventoPrincipal(Request $request)
    {
        $eventoPrincipal = Evento::where('principal', true)->first();

        if ($eventoPrincipal) {
            return response()->json(['existe' => true, 'evento' => $eventoPrincipal]);
        }

        return response()->json(['existe' => false]);
    }

    public function eventosPrincipalFalse(Request $request)
    {
        $eventos = Evento::where('principal', false)->get(['id', 'nombre']);

        return response()->json($eventos);
    }

    public function establecerPrincipal(Request $request)
    {
        $evento = Evento::findOrFail($request->id);

        $evento->update([
            'principal' => true,
            'solo_ingreso' => false
        ]);

        return response()->json($evento);
    }

    public function obtenerEventosPrivados()
    {
        $eventos = Evento::where('tipo', 'privado')
            ->where('principal', false)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json($eventos);
    }

    public function obtenerEventosPrincipal()
    {
        $eventoPrincipal = Evento::where('principal', true)->first();

        if (!$eventoPrincipal) {
            return response()->json(['message' => 'No se encontró ningún evento principal.'], 404);
        }

        return response()->json($eventoPrincipal, 200);
    }

    public function getAsistentes($id)
    {
        $asistentes = Asistencia::where('EventoID', $id)->with('usuario.persona')->get();

        $nombres_apellidos = $asistentes->map(function ($asistencia) {
            $ci = $asistencia->usuario->persona->ci;
            $nombres = $asistencia->usuario->persona->nombre;
            $paterno = $asistencia->usuario->persona->paterno;
            $materno = $asistencia->usuario->persona->materno;

            $ciTexto = $ci !== null ? $ci : 'SIN CI';

            return [
                'ci' => $ciTexto,
                'nombres' => $nombres,
                'paterno' => $paterno,
                'materno' => $materno
            ];
        });

        return response()->json(['usuarios' => $nombres_apellidos]);
    }

    public function getAsistentesPdf($id)
    {
        // Obtener el evento correspondiente por ID
        $evento = Evento::findOrFail($id);

        // Obtener asistentes del evento y ordenarlos por apellido paterno
        $asistentes = Asistencia::where('EventoID', $id)
            ->with(['usuario.persona', 'usuario.roles']) // Cargar los roles del usuario
            ->get()
            ->sortBy(function ($asistencia) {
                return $asistencia->usuario->persona->paterno; // Ordenar por apellido paterno
            });

        // Mapear los asistentes para obtener sus datos y asignar número
        $nombres_apellidos = $asistentes->values()->map(function ($asistencia, $index) {
            $ci = $asistencia->usuario->persona->ci;
            $nombres = $asistencia->usuario->persona->nombre;
            $paterno = $asistencia->usuario->persona->paterno;
            $materno = $asistencia->usuario->persona->materno;

            // Obtener los roles del usuario y filtrar "personal" y "cliente"
            $roles = $asistencia->usuario->roles->pluck('name')->filter(function ($role) {
                return !in_array($role, ['personal', 'cliente','Asistente']);
            })->toArray();

            // Convertir roles a texto
            $rolesTexto = !empty($roles) ? implode(', ', $roles) : 'Sin rol';

            $ciTexto = $ci !== null ? $ci : 'SIN CI';

            return [
                'nro' => $index + 1, // Numeración basada en el orden después de ordenar
                'ci' => $ciTexto,
                'nombres' => $nombres,
                'paterno' => $paterno,
                'materno' => $materno,
                'roles' => $rolesTexto // Agregar los roles
            ];
        });

        // Crear la cabecera para el PDF
        $cabecera = [
            ["Nro", "CI", "Nombres", "Paterno", "Materno", "Roles"] // Agregar columna de Roles
        ];

        // Preparar las fechas del evento en formato legible
        $fechaInicioLegible = date('Y-m-d H:i:s', $evento->fecha_hora_inicio);
        $fechaFinLegible = date('Y-m-d H:i:s', $evento->fecha_hora_fin);

        // Preparar los datos a devolver
        $resultado = [
            'evento' => [
                'nombre' => $evento->nombre,
                'lugar' => $evento->lugar,
                'fecha_hora_inicio' => $fechaInicioLegible,
                'fecha_hora_fin' => $fechaFinLegible,
            ],
            'total_asistentes' => $nombres_apellidos->count(), // Total de asistentes
            'cabecera' => $cabecera,
            'asistentes' => $nombres_apellidos // Ahora es un array de objetos
        ];

        return response()->json($resultado);
    }

    public function eliminarEvento($id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['error' => 'El evento no existe'], 404);
        }

        $evento->delete();

        return response()->json(['message' => 'Evento eliminado correctamente'], 200);
    }
}
