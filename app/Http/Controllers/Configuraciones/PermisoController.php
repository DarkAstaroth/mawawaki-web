<?php

namespace App\Http\Controllers\Configuraciones;

use App\Http\Controllers\Controller;
use App\Http\Resources\Configuraciones\PermisoResource;
use App\Models\Configuraciones\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('configuraciones.permisos.index');
    }


    public function obtenerPermisos(Request $request)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');

        $permisosQuery = Permission::select('permissions.*', 'modulos.nombre as modulo_nombre')
            ->leftJoin('modulos', 'permissions.modulo_id', '=', 'modulos.id');

        if ($busqueda) {
            $busquedaSinEspacios = str_replace(' ', '', $busqueda);
            $permisosQuery->where(function ($query) use ($busquedaSinEspacios) {
                $query->whereRaw("REPLACE(nombre, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                    ->orWhereRaw("REPLACE(descripcion, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
            });
        }

        $permisos = $permisosQuery->paginate($porPagina, ['*'], 'page', $pagina);

        $coleccionPermisos = new Collection($permisos->items());
        $recursoPermisos = PermisoResource::collection($coleccionPermisos);

        $resultadoBusqueda = $recursoPermisos->isEmpty() ? [] : $recursoPermisos;

        $modulos = Modulo::all(['id', 'nombre']);

        return response()->json([
            'modulos' => $modulos,
            'permisos' => $resultadoBusqueda,
            'paginacion' => [
                'total' => $permisos->total(),
                'porPagina' => $permisos->perPage(),
                'paginaActual' => $permisos->currentPage(),
                'ultimaPagina' => $permisos->lastPage(),
                'desde' => $permisos->firstItem(),
                'hasta' => $permisos->lastItem()
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
        $nombrePermiso = $request->input('name');
        $descripcionPermiso = $request->input('description');
        $moduloId = $request->input('moduloId');

        $modulo = Permission::create(['name' => $nombrePermiso, "description" => $descripcionPermiso, "modulo_id" => $moduloId]);

        $response = [
            'message' => 'Permiso creado exitosamente',
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
        $permiso = Permission::findOrFail($id);
        return response()->json([
            'permiso' => $permiso
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permiso = Permission::findOrFail($id);
        $permiso->modulo_id = $request->input('modulo_id');
        $permiso->name = $request->input('name');
        $permiso->description = $request->input('description');
        $permiso->save();

        return response()->json([
            'permiso' => $permiso
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
