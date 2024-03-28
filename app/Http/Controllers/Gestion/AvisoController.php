<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Gestion\Aviso;
use Illuminate\Http\Request;

class AvisoController extends Controller
{
    public function index()
    {
        return view('gestion.avisos.gestion');
    }
    public function crearAviso()
    {
        return view('gestion.avisos.crear');
    }

    public function obtenerAvisos(Request $request)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');

        $avisosQuery = Aviso::query();

        if ($busqueda) {
            $avisosQuery->where(function ($query) use ($busqueda) {
                $query->where('titulo', 'like', "%$busqueda%")
                    ->orWhere('descripcion', 'like', "%$busqueda%");
            });
        }

        $avisos = $avisosQuery->paginate($porPagina, ['*'], 'page', $pagina);
        return response()->json([
            'avisos' => $avisos,
            'paginacion' => [
                'total' => $avisos->total(),
                'porPagina' => $avisos->perPage(),
                'paginaActual' => $avisos->currentPage(),
                'ultimaPagina' => $avisos->lastPage(),
                'desde' => $avisos->firstItem(),
                'hasta' => $avisos->lastItem()
            ]
        ]);
    }

    public function eliminarAviso($id)
    {
        $aviso = Aviso::find($id);

        if (!$aviso) {
            return response()->json(['error' => 'Aviso no encontrado'], 404);
        }

        $aviso->delete();
        return response()->json(['mensaje' => 'Aviso eliminado correctamente'], 200);
    }
}
