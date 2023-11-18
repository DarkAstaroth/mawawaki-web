<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Gestion\Documentacion;
use App\Models\Gestion\TipoDocumento;
use App\Models\User;
use App\Services\DocumentacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;


class DocumentacionController extends Controller
{
    public function subirArchivo(Request $request, $usuarioId)
    {
        try {
            $request->validate([
                'archivo' => 'required|mimes:pdf|max:2048',
                'tipo_documento_id' => 'required|exists:tipo_documento,id',
            ]);

            $archivo = $request->file('archivo');
            $nombreUnico = 'archivo' . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            $rutaArchivo = $archivo->storeAs('documentos', $nombreUnico, 'public');

            // Crear un nuevo documento
            $documento = new Documentacion([
                'nombre_archivo' => $nombreUnico,
                'ruta_archivo' => $rutaArchivo,
                'completado' => true,
                'tamano_archivo' => $archivo->getSize(),
                'formato_archivo' => $archivo->getClientOriginalExtension(),
            ]);

            // Asociar el documento con el usuario
            $usuario = User::findOrFail($usuarioId);
            $usuario->documentos()->save($documento);

            // Asociar el documento con el tipo de documento
            $tipoDocumento = TipoDocumento::find($request->tipo_documento_id);
            $documento->tiposDocumento()->attach($tipoDocumento);

            return response()->json(['message' => 'Archivo subido exitosamente']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function actualizarDocumentacion($tipoDocumento, $usuarioId, $nombreUnico, $rutaArchivo, $archivo, $extension)
    {
        $columnaCompletado = 'completado';
        $columnaNombreArchivo = 'nombre_archivo';
        $columnaRutaArchivo = 'ruta_archivo';

        // Buscar un registro existente para el usuario y tipo de documento
        $documentacion = Documentacion::where('user_id', $usuarioId)
            ->where('tipo_documento', $tipoDocumento)
            ->first();

        // Si no se encuentra un registro existente, crea uno nuevo
        if (!$documentacion) {
            Documentacion::create([
                'user_id' => $usuarioId,
                'tipo_documento' => $tipoDocumento,
                $columnaNombreArchivo => $nombreUnico,
                $columnaRutaArchivo => $rutaArchivo,
                $columnaCompletado => true,
                'tamano_archivo' => $archivo->getSize(),
                'formato_archivo' => $extension,
                // Agrega más campos según sea necesario
            ]);
        } else {
            // Si existe un registro, elimina el archivo existente y actualiza los campos correspondientes
            Storage::disk('public')->delete($documentacion->$columnaRutaArchivo);

            $documentacion->update([
                $columnaNombreArchivo => $nombreUnico,
                $columnaRutaArchivo => $rutaArchivo,
                $columnaCompletado => true,
                'tamano_archivo' => $archivo->getSize(),
                'formato_archivo' => $extension,
                // Agrega más campos según sea necesario
            ]);
        }
    }

    public function obtenerDocumentosUsuario($usuarioId)
    {
        try {
            $usuario = User::findOrFail($usuarioId);
            // $documentos = $usuario->documentos;
            $documentos = $usuario->documentos()->with('tiposDocumento')->get();


            return response()->json(['data' => $documentos], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerTiposDocumento()
    {
        $tiposDocumento = TipoDocumento::all(['id', 'nombre']);

        return response()->json($tiposDocumento);
    }
}
