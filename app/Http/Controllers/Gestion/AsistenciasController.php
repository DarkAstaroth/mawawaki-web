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
        $usuarioId = $request->usuario;
        $eventoId = $request->evento;
        $codigoQR = $request->qr;
        $detalleQR = QR::findOrFail($request->qr);

        // Obtener la fecha y hora actual en formato UNIX timestamp
        $fechaHoraActual = now()->timestamp;
        if ($detalleQR->fecha_vencimiento !== null && $detalleQR->fecha_vencimiento - $fechaHoraActual   < 0) {
            return redirect()->route('dashboard')->with('error', 'QR vencido');
        };


        $asistencia = Asistencia::where('UsuarioID', $usuarioId)
            ->where('EventoID', $eventoId)
            ->whereDate('created_at', today())
            ->latest()
            ->first();


        if ($asistencia) {
            // Verificar si la fecha de entrada de la asistencia es la misma que la fecha actual
            $fechaEntrada = $asistencia->fecha_hora_entrada;
            if (date('Y-m-d', $fechaEntrada) === date('Y-m-d', $fechaHoraActual)) {
                // Si es el mismo día, actualizar la hora de salida en la asistencia existente
                $asistencia->fecha_hora_salida = $fechaHoraActual;
                $asistencia->save();
            } else {
                // Si es otro día, crear una nueva asistencia con la hora de entrada
                Asistencia::create([
                    'UsuarioID' => $usuarioId,
                    'EventoID' => $eventoId,
                    'fecha_hora_entrada' => $fechaHoraActual,
                    'global' => false, // Puedes configurar esto según tus necesidades
                    'CodigoQR' => $codigoQR,
                ]);
            }
        } else {
            // Si no existe asistencia, crear una nueva asistencia con la hora de entrada
            Asistencia::create([
                'UsuarioID' => $usuarioId,
                'EventoID' => $eventoId,
                'fecha_hora_entrada' => $fechaHoraActual,
                'global' => false, // Puedes configurar esto según tus necesidades
                'CodigoQR' => $codigoQR,
            ]);

            if ($detalleQR->cantidad_usos === 0) {
                return redirect()->route('dashboard')->with('error', 'Limite de usos superado');
            }

            if ($detalleQR->cantidad_usos !== 0 && $detalleQR->cantidad_usos > 0) {
                if ($detalleQR->cantidad_usos !== -1) {
                    $detalleQR->cantidad_usos -= 1;
                }
                $detalleQR->save();
            }
        }

        // return redirect()->route('dashboard')->with('success', 'Marcado registrado correctamente');
    }


    public function obtenerAsistencias(Request $request, string $id)
    {
        // Establecer la zona horaria local
        date_default_timezone_set(config('app.timezone'));

        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');
        $idEvento = $request->get('idEvento');

        $asistenciasQuery = Asistencia::where('UsuarioID', $id);

        if ($idEvento) {
            $asistenciasQuery->where('EventoID', $idEvento);
        }

        $asistencias = $asistenciasQuery->get();

        $totalHoras = 0;
        $totalMinutos = 0;
        $totalSegundos = 0;

        $asistenciasConHoras = [];

        foreach ($asistencias as $asistencia) {
            $horaEntrada = \Carbon\Carbon::createFromTimestamp($asistencia->fecha_hora_entrada);

            $horaSalida = $asistencia->fecha_hora_salida ? \Carbon\Carbon::createFromTimestamp($asistencia->fecha_hora_salida) : null;

            $horas = 0;
            $minutos = 0;
            $segundos = 0;

            if ($horaSalida) {
                $diferencia = $horaSalida->diff($horaEntrada);

                $horas = $diferencia->h;
                $minutos = $diferencia->i;
                $segundos = $diferencia->s;
            }

            $totalHoras += $horas;
            $totalMinutos += $minutos;
            $totalSegundos += $segundos;

            $asistenciasConHoras[] = [
                'id' => $asistencia->id,
                'fecha_hora_entrada' => $horaEntrada->format('H:i:s d/m/Y'), // Formato personalizado
                'fecha_hora_salida' => $horaSalida ? $horaSalida->format('H:i:s d/m/Y') : null, // Formato personalizado
                'horas' => $horas,
                'minutos' => $minutos,
                'segundos' => $segundos,
            ];
        }

        $totalAsistencias = count($asistenciasConHoras);
        $inicio = ($pagina - 1) * $porPagina;
        $fin = min($inicio + $porPagina, $totalAsistencias);
        $asistenciasPaginadas = array_slice($asistenciasConHoras, $inicio, $fin - $inicio);

        return response()->json([
            'total' => [
                'total_asistencias' => $totalAsistencias,
                'total_horas' => $totalHoras,
                'total_minutos' => $totalMinutos,
                'total_segundos' => $totalSegundos,
            ],
            'asistencias' => $asistenciasPaginadas,
            'paginacion' => [
                'total' => $totalAsistencias,
                'porPagina' => $porPagina,
                'paginaActual' => $pagina,
                'ultimaPagina' => ceil($totalAsistencias / $porPagina),
                'desde' => $inicio + 1,
                'hasta' => $fin,
            ],
        ]);
    }
}
