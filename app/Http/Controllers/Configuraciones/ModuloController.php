<?php

namespace App\Http\Controllers\Configuraciones;

use App\Http\Controllers\Controller;
use App\Http\Resources\Configuraciones\RolResource;
use App\Models\Configuraciones\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ModuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('configuraciones.modulos.index');
    }

    public function obtenerModulos(Request $request)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');

        $modulosQuery = Modulo::query();

        if ($busqueda) {
            $busquedaSinEspacios = str_replace(' ', '', $busqueda);
            $modulosQuery->where(function ($query) use ($busquedaSinEspacios) {
                $query->whereRaw("REPLACE(nombre, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                    ->orWhereRaw("REPLACE(descripcion, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
            });
        }

        $modulos = $modulosQuery->paginate($porPagina, ['*'], 'page', $pagina);

        $coleccionModulo = new Collection($modulos->items());
        $recursoModulo = RolResource::collection($coleccionModulo);

        $resultadoBusqueda = $recursoModulo->isEmpty() ? [] : $recursoModulo;

        return response()->json([
            'modulos' => $resultadoBusqueda,
            'paginacion' => [
                'total' => $modulos->total(),
                'porPagina' => $modulos->perPage(),
                'paginaActual' => $modulos->currentPage(),
                'ultimaPagina' => $modulos->lastPage(),
                'desde' => $modulos->firstItem(),
                'hasta' => $modulos->lastItem()
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
        $nombreModulo = $request->input('nombre');
        $descripcionModulo = $request->input('descripcion');
        $modulo = Modulo::create(['nombre' => $nombreModulo, "descripcion" => $descripcionModulo]);

        $response = [
            'message' => 'Módulo creado exitosamente',
            'modulo' => $modulo
        ];

        return response()->json($response);
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
        $modulo = Modulo::findOrFail($id);
        return response()->json([
            'modulo' => $modulo
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $modulo = Modulo::findOrFail($id);
        $modulo->nombre = $request->input('nombre');
        $modulo->descripcion = $request->input('descripcion');
        $modulo->save();

        return response()->json([
            'modulo' => $modulo
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permiso = Modulo::findOrFail($id);
        $permiso->delete();
        return response()->json([
            'success' => 'El módulo se eliminó correctamente'
        ]);
    }

    public function modulosPermisos()
    {
        $modulos = Modulo::with('permisos')->get();
        return response()->json($modulos);
    }
}
