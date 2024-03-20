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
        // $client = new Client();
        // $response = $client->request('GET', 'https://api.ipgeolocation.io/getip');
        // $ipInfo = $response->getBody()->getContents();
        // $getIP = json_decode($ipInfo, true);

        // // Dirección IP obtenida de la respuesta anterior
        // $ip = $getIP['ip'];

        // // Clave de la API
        // $apiKey = "37cac8b6d41846c5976151b2b5480a88";

        // // Crear una instancia de Guzzle Client
        // $client = new Client();

        // // Realizar la solicitud GET a la API
        // $response = $client->request('GET', 'https://api.ipgeolocation.io/ipgeo', [
        //     'query' => [
        //         'apiKey' => $apiKey,
        //         'ip' => $ip,
        //     ],
        // ]);

        // // Obtener el contenido de la respuesta como un string JSON
        // $geoInfo = $response->getBody()->getContents();

        // // Parsear el string JSON a un array en PHP
        // $geoInfoArray = json_decode($geoInfo, true);

        // // Mostrar la respuesta en un dd
        // dd($geoInfoArray);

        return view('gestion.eventos.index');
    }


    public function obtenerEventos(Request $request)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');

        $eventoQuery = Evento::query();

        if ($busqueda) {
            $busquedaSinEspacios = str_replace(' ', '', $busqueda);
            $eventoQuery->where(function ($query) use ($busquedaSinEspacios) {
                $query->whereRaw("REPLACE(nombre, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
            });
        }

        $eventos = $eventoQuery->paginate($porPagina, ['*'], 'page', $pagina);

        $eventoResource = EventosResource::collection($eventos);
        $resultadoBusqueda = $eventoResource->isEmpty() ? [] : $eventoResource;

        return response()->json([
            'eventos' => $resultadoBusqueda,
            'paginacion' => [
                'total' => $eventos->total(),
                'porPagina' => $eventos->perPage(),
                'paginaActual' => $eventos->currentPage(),
                'ultimaPagina' => $eventos->lastPage(),
                'desde' => $eventos->firstItem(),
                'hasta' => $eventos->lastItem()
            ]
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
            return [
                'ci' => $asistencia->usuario->persona->ci,
                'nombres' => $asistencia->usuario->persona->nombre,
                'paterno' => $asistencia->usuario->persona->paterno,
                'materno' => $asistencia->usuario->persona->materno
            ];
        });

        return response()->json(['usuarios' => $nombres_apellidos]);
    }
}
