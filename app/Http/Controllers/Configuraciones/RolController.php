<?php

namespace App\Http\Controllers\Configuraciones;

use App\Http\Controllers\Controller;
use App\Http\Resources\Configuraciones\RolResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('configuraciones.roles.index');
    }

    public function obtenerRoles(Request $request)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');

        $rolesQuery = Role::query();

        if ($busqueda) {

            $busquedaSinEspacios = str_replace(' ', '', $busqueda);
            $rolesQuery->where(function ($query) use ($busquedaSinEspacios) {
                $query->whereRaw("REPLACE(name, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                    ->orWhereRaw("REPLACE(description, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
            });
        }

        $roles = $rolesQuery->paginate($porPagina, ['*'], 'page', $pagina);

        $rolesCollection = new Collection($roles->items());
        $rolesResource = RolResource::collection($rolesCollection);

        $resultadoBusqueda = $rolesResource->isEmpty() ? [] : $rolesResource;

        return response()->json([
            'roles' => $resultadoBusqueda,
            'paginacion' => [
                'total' => $roles->total(),
                'porPagina' => $roles->perPage(),
                'paginaActual' => $roles->currentPage(),
                'ultimaPagina' => $roles->lastPage(),
                'desde' => $roles->firstItem(),
                'hasta' => $roles->lastItem()
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
        $nombreRol = $request->input('name');
        $descripcionRol = $request->input('description');
        $rol = Role::create(['name' => $nombreRol, "description" => $descripcionRol]);

        $response = [
            'message' => 'Rol creado exitosamente',
            'rol' => $rol
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
        $rol = Role::findOrFail($id);
        return response()->json([
            'rol' => $rol
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rol = Role::findOrFail($id);
        $rol->name = $request->input('name');
        $rol->description = $request->input('description');
        $rol->save();

        return response()->json([
            'rol' => $rol
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rol = Role::findOrFail($id);
        $rol->delete();
        return response()->json([
            'success' => 'El rol se eliminó correctamente'
        ]);
    }
}
