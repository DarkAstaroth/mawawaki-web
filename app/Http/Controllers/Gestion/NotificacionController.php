<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gestion\NotificacionResource;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function enviarNotificacion($id, Request $request)
    {
        // Obtener el tipo y el mensaje del cuerpo de la solicitud
        $tipo = $request->input('tipo');
        $titulo = $request->input('titulo');
        $mensaje = $request->input('mensaje');

        // Validar que se proporcionen el tipo y el mensaje
        $request->validate([
            'tipo' => 'required|in:info,success,warning',
            'titulo' => 'required|string',
            'mensaje' => 'required|string',
        ]);

        $notificacion = new Notificacion([
            'usuario_id' => $id,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
            'leida' => false,
            'fecha_unix' => now()->timestamp,
        ]);

        $notificacion->save();

        return response()->json(['mensaje' => 'Notificación enviada correctamente']);
    }

    public function obtenerNotificaciones(Request $request, $usuarioId)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');
        $parametro = $request->get('parametro');

        $notificacionesQuery = Notificacion::query()->where('usuario_id', $usuarioId);

        if ($busqueda) {
            $notificacionesQuery->where(function ($query) use ($busqueda) {
                $query->where('titulo', 'like', "%$busqueda%")
                    ->orWhere('mensaje', 'like', "%$busqueda%");
            });
        }

        $notificaciones = $notificacionesQuery->paginate($porPagina, ['*'], 'page', $pagina);

        $notificacionesResource = NotificacionResource::collection($notificaciones);
        $cantidadNoLeidas = $notificacionesQuery->where('leido', false)->count();

        return response()->json([
            'notificaciones' => $notificacionesResource,
            'no_visto' => $cantidadNoLeidas,
            'paginacion' => [
                'total' => $notificaciones->total(),
                'porPagina' => $notificaciones->perPage(),
                'paginaActual' => $notificaciones->currentPage(),
                'ultimaPagina' => $notificaciones->lastPage(),
                'desde' => $notificaciones->firstItem(),
                'hasta' => $notificaciones->lastItem(),
            ],
        ]);
    }

    public function marcarTodasLeidas($usuarioId)
    {
        try {
            Notificacion::where('usuario_id', $usuarioId)->update(['leido' => true]);
            return response()->json(['message' => 'Todas las notificaciones marcadas como leídas.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al marcar las notificaciones como leídas.'], 500);
        }
    }
}
