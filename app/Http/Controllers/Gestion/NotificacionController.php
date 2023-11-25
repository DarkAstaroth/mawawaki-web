<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function enviarNotificacion($id, Request $request)
    {
        // Obtener el tipo y el mensaje del cuerpo de la solicitud
        $tipo = $request->input('tipo');
        $mensaje = $request->input('mensaje');

        // Validar que se proporcionen el tipo y el mensaje
        $request->validate([
            'tipo' => 'required|in:info,success,warning',
            'mensaje' => 'required|string',
        ]);

        $notificacion = new Notificacion([
            'usuario_id' => $id,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
            'leida' => false,
            'fecha_unix' => now()->timestamp,
        ]);

        $notificacion->save();

        return response()->json(['mensaje' => 'Notificación enviada correctamente']);
    }
}
