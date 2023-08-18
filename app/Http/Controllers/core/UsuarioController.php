<?php

namespace App\Http\Controllers\core;

use App\Http\Controllers\Controller;
use App\Http\Resources\core\UsuarioResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('core.usuarios.index');
    }

    public function obtenerUsuarios(Request $request)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');

        $usuariosQuery = User::query();

        if ($busqueda) {

            $busquedaSinEspacios = str_replace(' ', '', $busqueda);
            $usuariosQuery->where(function ($query) use ($busquedaSinEspacios) {
                $query->whereRaw("REPLACE(name, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                    ->orWhereRaw("REPLACE(email, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
            });
        }

        $usuarios = $usuariosQuery->paginate($porPagina, ['*'], 'page', $pagina);
        $usuariosIds = $usuarios->pluck('id')->toArray();
        $usuariosConRoles = User::with('roles')->whereIn('id', $usuariosIds)->get();

         $usuariosConCamposAdicionales = $usuariosConRoles->map(function ($usuario) {
            $usuario->nuevo_campo = "Valor del nuevo campo para este usuario";
            return $usuario;
        });

        $usuariosResource = UsuarioResource::collection($usuariosConCamposAdicionales);
        $resultadoBusqueda = $usuariosResource->isEmpty() ? [] : $usuariosResource;

        return response()->json([
            'usuarios' => $resultadoBusqueda,
            'paginacion' => [
                'total' => $usuarios->total(),
                'porPagina' => $usuarios->perPage(),
                'paginaActual' => $usuarios->currentPage(),
                'ultimaPagina' => $usuarios->lastPage(),
                'desde' => $usuarios->firstItem(),
                'hasta' => $usuarios->lastItem()
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

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        return redirect('/');
    }
}
