<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Gestion\Asistencia;
use App\Models\Gestion\QR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsistenciasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    public function registrarAsistencia(string $codigoQR)
    {
        // Buscar el QR por su atributo CodigoQR
        $qr = QR::where('CodigoQR', $codigoQR)->first();

        if ($qr) {
            // Obtener el evento al que pertenece el QR
            $evento = $qr->evento;

            if ($evento) {
                $usuario = Auth::user();
                return view('gestion.asistencia.registrar', compact('usuario', 'evento', 'qr'));
            } else {
                return "Evento no encontrado.";
            }
        } else {
            return "QR no encontrado.";
        }
    }



    public function registrarMarcado(Request $request)
    {
        // Buscar una asistencia existente con los parámetros proporcionados
        $asistencia = Asistencia::where('UsuarioID', $request->usuario)
            ->where('EventoID', $request->evento)
            ->where('CodigoQR', $request->qr)
            ->first();

        if ($asistencia) {
            // Actualizar la hora de salida en la asistencia existente
            $asistencia->fecha_hora_salida = now()->timestamp; // La fecha y hora actual
            $asistencia->save();
        } else {
            // Crear una nueva asistencia con la hora de entrada
            Asistencia::create([
                'UsuarioID' => $request->usuario,
                'EventoID' => $request->evento,
                'fecha_hora_entrada' => now()->timestamp,
                'global' => false, // Puedes configurar esto según tus necesidades
                'CodigoQR' => $request->qr,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Marcado registrado correctamente');
    }
}
