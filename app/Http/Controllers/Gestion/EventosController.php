<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gestion\EventosResource;
use App\Models\Gestion\Evento;
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
        //
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
        $nuevoEvento->descripcion = $request->input('descripcion');

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
}
