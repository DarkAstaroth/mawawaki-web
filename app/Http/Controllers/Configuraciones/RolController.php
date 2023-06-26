<?php

namespace App\Http\Controllers\Configuraciones;

use App\Http\Controllers\Controller;
use App\Http\Resources\Configuraciones\RolResource;
use Illuminate\Http\Request;
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

    public function obtenerRoles()
    {
        return RolResource::collection(Role::all());
        // return view('configuraciones.roles.index', compact('roles'));
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
        return redirect()->route('roles.index')->with('success', 'El rol se creó correctamente');
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

        $rol = Role::findOrFail($id);
        $rol->delete();
        return response()->json([
            'success' => 'El rol se eliminó correctamente'
        ]);
    }
}
