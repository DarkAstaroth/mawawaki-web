<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Http\Resources\gestion\CaballoResource;
use App\Models\Gestion\Caballo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;

class CaballosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('gestion.caballos.index');
    }


    public function obtenerCaballos(Request $request)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');

        $caballoQuery = Caballo::query();

        if ($busqueda) {
            $busquedaSinEspacios = str_replace(' ', '', $busqueda);
            $caballoQuery->where(function ($query) use ($busquedaSinEspacios) {
                $query->whereRaw("REPLACE(nombre, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
            });
        }

        $caballos = $caballoQuery->paginate($porPagina, ['*'], 'page', $pagina);

        $caballosResource = CaballoResource::collection($caballos);
        $resultadoBusqueda = $caballosResource->isEmpty() ? [] : $caballosResource;

        return response()->json([
            'caballos' => $resultadoBusqueda,
            'paginacion' => [
                'total' => $caballos->total(),
                'porPagina' => $caballos->perPage(),
                'paginaActual' => $caballos->currentPage(),
                'ultimaPagina' => $caballos->lastPage(),
                'desde' => $caballos->firstItem(),
                'hasta' => $caballos->lastItem()
            ]
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gestion.caballos.create');
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
        $caballo = Caballo::findOrFail($id);
        $modo = 'editar';
        return view('gestion.caballos.edit', compact('caballo', 'modo'));
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
        $caballo = Caballo::findOrFail($id);
        $caballo->delete();
        return response()->json([
            'success' => 'El caballo se eliminó correctamente'
        ]);
    }



    public function agregarCaballo(Request $request)
    {
        $caballo = new Caballo();
        $caballo->nombre = $request->input('nombre');
        $caballo->apodo = $request->input('apodo');
        $caballo->raza = $request->input('raza');
        $caballo->color_pelaje = $request->input('color');
        $caballo->fecha_nacimiento = Carbon::parse($request->input('fecha_nacimiento'))->format('Y-m-d');
        $caballo->genero = $request->input('genero');
        $caballo->notas = $request->input('notas');
        $caballo->save();
    }

    public function editarCaballo(Request $request, string $id)
    {
        $caballo = Caballo::findOrFail($id);

        $caballo->nombre = $request->input('nombre');
        $caballo->apodo = $request->input('apodo');
        $caballo->raza = $request->input('raza');
        $caballo->color_pelaje = $request->input('color');
        $caballo->fecha_nacimiento = Carbon::parse($request->input('fecha_nacimiento'))->format('Y-m-d');
        $caballo->genero = $request->input('genero');
        $caballo->notas = $request->input('notas');
        $caballo->save();

        return response()->json([
            'caballo' => $caballo
        ]);
    }
}
