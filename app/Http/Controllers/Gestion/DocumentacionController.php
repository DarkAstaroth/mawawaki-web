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
    // public function subirArchivo(Request $request, $usuarioId)
    // {
    //     try {
    //         $request->validate([
    //             'archivo' => 'required|mimes:pdf|max:2048',
    //             'tipo_documento_id' => 'required',
    //         ]);

    //         $archivo = $request->file('archivo');
    //         $nombreUnico = 'archivo' . '_' . time() . '.' . $archivo->getClientOriginalExtension();
    //         $rutaArchivo = $archivo->storeAs('documentos', $nombreUnico, 'public');
    //         $usuario = User::findOrFail($usuarioId);
    //         // Crear un nuevo documento
    //         $documento = new Documentacion([
    //             'user_id' => $usuarioId,
    //             'nombre_archivo' => $nombreUnico,
    //             'ruta_archivo' => $rutaArchivo,
    //             'completado' => true,
    //             'tamano_archivo' => $archivo->getSize(),
    //             'formato_archivo' => $archivo->getClientOriginalExtension(),
    //             'tipo_documento_id' => $request->tipo_documento_id
    //         ]);

    //         $documento->save();
    //         return response()->json(['message' => 'Archivo subido exitosamente']);
    //     } catch (ValidationException $e) {
    //         return response()->json(['error' => $e->validator->errors()], 400);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function subirArchivo(Request $request, $usuarioId)
    // {
    //     try {
    //         $request->validate([
    //             'archivo' => 'required|mimes:pdf|max:3024',
    //             'tipo_documento_id' => 'required',
    //         ]);

    //         $usuario = User::findOrFail($usuarioId);
    //         $tipoDocumentoId = $request->tipo_documento_id;

    //         // Verificar si el tipo de documento es único
    //         $tipoDocumento = TipoDocumento::findOrFail($tipoDocumentoId);
    //         if ($tipoDocumento->unico) {
    //             // Verificar si el usuario ya tiene un archivo con este tipo de documento
    //             $documentoExistente = Documentacion::where('user_id', $usuarioId)
    //                 ->where('tipo_documento_id', $tipoDocumentoId)
    //                 ->exists();

    //             if ($documentoExistente) {
    //                 return response()->json(['error' => 'El usuario ya tiene un archivo con este tipo de documento.'], 400);
    //             }
    //         }

    //         $archivo = $request->file('archivo');
    //         $nombreUnico = 'archivo' . '_' . time() . '.' . $archivo->getClientOriginalExtension();
    //         $rutaArchivo = $archivo->storeAs('documentos', $nombreUnico, 'public');

    //         // Crear un nuevo documento
    //         $documento = new Documentacion([
    //             'user_id' => $usuarioId,
    //             'nombre_archivo' => $nombreUnico,
    //             'ruta_archivo' => $rutaArchivo,
    //             'completado' => true,
    //             'tamano_archivo' => $archivo->getSize(),
    //             'formato_archivo' => $archivo->getClientOriginalExtension(),
    //             'tipo_documento_id' => $tipoDocumentoId
    //         ]);

    //         $documento->save();

    //         return response()->json(['message' => 'Archivo subido exitosamente']);
    //     } catch (ValidationException $e) {
    //         return response()->json(['error' => $e->validator->errors()], 400);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }


    public function subirArchivo(Request $request, $usuarioId)
    {
        try {
            $request->validate([
                'archivo' => 'required|mimes:pdf|max:3024',
                'tipo_documento_id' => 'required',
            ], [
                'archivo.required' => 'El archivo es obligatorio.',
                'archivo.mimes' => 'El archivo debe ser de tipo PDF.',
                'archivo.max' => 'El tamaño del archivo no debe superar los 3 MB.',
                'tipo_documento_id.required' => 'El tipo de documento es obligatorio.',
            ]);

            $usuario = User::findOrFail($usuarioId);
            $tipoDocumentoId = $request->tipo_documento_id;

            // Verificar si el tipo de documento es único
            $tipoDocumento = TipoDocumento::findOrFail($tipoDocumentoId);
            if ($tipoDocumento->unico) {
                // Verificar si el usuario ya tiene un archivo con este tipo de documento
                $documentoExistente = Documentacion::where('user_id', $usuarioId)
                    ->where('tipo_documento_id', $tipoDocumentoId)
                    ->exists();

                if ($documentoExistente) {
                    return response()->json(['error' => 'El usuario ya tiene un archivo con este tipo de documento.'], 400);
                }
            }

            $archivo = $request->file('archivo');
            $nombreUnico = 'archivo' . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            $rutaArchivo = $archivo->storeAs('documentos', $nombreUnico, 'public');

            // Obtener el nombre original del archivo
            $nombreOriginal = $archivo->getClientOriginalName();

            // Crear un nuevo documento con la descripción que contiene el nombre original del archivo
            $documento = new Documentacion([
                'user_id' => $usuarioId,
                'nombre_archivo' => $nombreUnico,
                'ruta_archivo' => $rutaArchivo,
                'completado' => true,
                'tamano_archivo' => $archivo->getSize(),
                'formato_archivo' => $archivo->getClientOriginalExtension(),
                'descripcion' =>  $nombreOriginal,
                'tipo_documento_id' => $tipoDocumentoId,
                'estado_revision' => 0
            ]);

            $documento->save();

            return response()->json(['message' => 'Archivo subido exitosamente']);
        } catch (ValidationException $e) {
            $errores = $e->validator->errors()->all();
            return response()->json(['error' => $errores], 400);
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
            $documentos = $usuario->documentos()->with('tipoDocumento')->get();
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


    public function destroy($id)
    {
        $archivo = Documentacion::findOrFail($id);
        $nombreArchivo = $archivo->nombre_archivo;

        if (Storage::exists('public/documentos/' . $nombreArchivo)) {

            Storage::delete('public/documentos/' . $nombreArchivo);
        } else {

            return response()->json(['error' => 'El archivo no existe']);
        }

        $archivo->delete();

        return response()->json(['message' => 'Archivo eliminado correctamente']);
    }

    public function cambiarEstadoRevision($id)
    {
        $documento = Documentacion::findOrFail($id);
        $documento->estado_revision = true;
        $documento->save();

        return response()->json(['message' => 'Estado de revisión cambiado correctamente'], 200);
    }
}
