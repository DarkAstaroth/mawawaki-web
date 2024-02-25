<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gestion\EventosResource;
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

    public function DetalleEvento(string $id)
    {
        $evento = Evento::findOrFail($id);
        return view('gestion.eventos.detalle', compact('evento'));
    }

    public function obtenerEventosUsuario(string $id)
    {
        $eventos = Evento::whereHas('asistencias', function ($query) use ($id) {
            $query->where('UsuarioID', $id);
        })->select('id', 'nombre')->get();

        $result = $eventos->map(function ($evento) {
            return [
                'value' => $evento->id,
                'name' => $evento->nombre,
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
}
