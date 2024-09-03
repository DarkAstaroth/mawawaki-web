<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Gestion\Asistencia;
use App\Models\Gestion\Evento;
use App\Models\Gestion\Personal;
use App\Models\Gestion\QR;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                // Verificar si el evento ya ha finalizado
                if ($evento->fecha_hora_fin) {
                    $fechaFin = Carbon::createFromTimestamp($evento->fecha_hora_fin);
                    $now = Carbon::now();

                    if ($now->gt($fechaFin)) {
                        return view('gestion.asistencia.caducado');
                    }
                }

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
            ->where('ingreso_verificado', 1)
            ->whereDate('created_at', today())
            ->latest()
            ->first();


        if ($asistencia) {
            // Verificar si la fecha de entrada de la asistencia es la misma que la fecha actual
            $fechaEntrada = $asistencia->fecha_hora_entrada;
            if (date('Y-m-d', $fechaEntrada) === date('Y-m-d', $fechaHoraActual)) {
                // Si es el mismo día, actualizar la hora de salida en la asistencia existente
                $asistencia->fecha_hora_salida = $fechaHoraActual;
                $asistencia->salida_verificado = 1;
                $asistencia->save();
            } else {
                // Si es otro día, crear una nueva asistencia con la hora de entrada
                Asistencia::create([
                    'UsuarioID' => $usuarioId,
                    'EventoID' => $eventoId,
                    'fecha_hora_entrada' => $fechaHoraActual,
                    'global' => false, // Puedes configurar esto según tus necesidades
                    'CodigoQR' => $codigoQR,
                    'ingreso_verificado' => 1,
                    'salida_verificado' => 0,
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
                'ingreso_verificado' => 1,
                'salida_verificado' => 0,
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

        // Ordenar por fecha de entrada
        $asistenciasQuery->orderBy('fecha_hora_entrada', 'asc');

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
                'ingreso_verificado' => $asistencia->ingreso_verificado,
                'salida_verificado' => $asistencia->salida_verificado,
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


    public function calcularTotalAsistencias(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'idUsuario' => 'required|uuid',
            'idEvento' => 'required|uuid',
        ]);

        // Obtener el total de segundos de todas las asistencias con ingreso y salida verificados
        $totalAsistencias = Asistencia::where('UsuarioID', $request->idUsuario)
            ->where('EventoID', $request->idEvento)
            ->where('ingreso_verificado', 1) // Verificar que el ingreso esté verificado
            ->where('salida_verificado', 1) // Verificar que la salida esté verificada
            ->sum(DB::raw('fecha_hora_salida - fecha_hora_entrada'));

        // Convertir el total a horas, minutos y segundos
        $horas = floor($totalAsistencias / 3600);
        $minutos = floor(($totalAsistencias % 3600) / 60);
        $segundos = $totalAsistencias % 60;

        return response()->json([
            'horas' => $horas,
            'minutos' => $minutos,
            'segundos' => $segundos,
        ]);
    }


    public function generarPDFAsistencias(Request $request)
    {
        // Obtener idUsuario y idEvento del cuerpo de la solicitud
        $idUsuario = $request->idUsuario;
        $idEvento = $request->idEvento;

        // Consultar asistencias en la base de datos y ordenar por fecha y hora de entrada
        $asistencias = Asistencia::where('UsuarioID', $idUsuario)
            ->where('ingreso_verificado', 1)
            ->where('salida_verificado', 1)
            ->when($idEvento, function ($query) use ($idEvento) {
                return $query->where('EventoID', $idEvento);
            })
            ->orderBy('fecha_hora_entrada')
            ->get();

        // Cabecera y datos para el PDF
        $cabecera = [['Fecha y Hora de Entrada', 'Fecha y Hora de Salida', 'Horas', 'Minutos', 'Segundos']];
        $datos = [];

        foreach ($asistencias as $asistencia) {
            $horaEntrada = \Carbon\Carbon::createFromTimestamp($asistencia->fecha_hora_entrada);
            $horaSalida = $asistencia->fecha_hora_salida ? \Carbon\Carbon::createFromTimestamp($asistencia->fecha_hora_salida) : null;

            // Calcular diferencia de tiempo si hay hora de salida
            if ($horaSalida) {
                $diferencia = $horaSalida->diff($horaEntrada);
                $horas = $diferencia->h;
                $minutos = $diferencia->i;
                $segundos = $diferencia->s;
            } else {
                $horas = $minutos = $segundos = null;
            }

            $datos[] = [
                $horaEntrada->format('H:i:s d/m/Y'), // Formato personalizado
                $horaSalida ? $horaSalida->format('H:i:s d/m/Y') : null, // Formato personalizado
                $horas,
                $minutos,
                $segundos
            ];
        }

        // Enviar la respuesta con los datos en formato JSON
        return response()->json(['cabecera' => $cabecera, 'datos' => $datos]);
    }



    public function registrarAsistenciaVerificar(Request $request)
    {
        $request->validate([
            'UsuarioID' => 'required|uuid',
            'EventoID' => 'required|uuid',
            'fecha_hora_entrada' => 'required|integer',
            'fecha_hora_salida' => 'nullable|integer',
            'CodigoQR' => 'nullable|string',
        ]);

        $fecha_entrada = date('Y-m-d', $request->fecha_hora_entrada);

        $fechas_entrada_existente = Asistencia::where('UsuarioID', $request->UsuarioID)
            ->where('EventoID', $request->EventoID)
            ->pluck('fecha_hora_entrada')
            ->map(function ($fecha_unix) {
                return date('Y-m-d', $fecha_unix);
            })
            ->toArray();

        if (in_array($fecha_entrada, $fechas_entrada_existente)) {
            return response()->json(['message' => 'Ya existe una asistencia registrada para este usuario y evento en esta fecha'], 400);
        }

        Asistencia::create([
            'UsuarioID' => $request->UsuarioID,
            'EventoID' => $request->EventoID,
            'fecha_hora_entrada' => $request->fecha_hora_entrada,
            'fecha_hora_salida' => $request->fecha_hora_salida,
            'global' => false,
            'CodigoQR' => $request->CodigoQR,
            'ingreso_verificado' => false,
            'salida_verificado' => false,
        ]);

        return response()->json(['message' => 'Asistencia registrada correctamente'], 201);
    }




    public function eliminarAsistencia(string $id)
    {
        try {
            // Validar que la asistencia exista
            $asistencia = Asistencia::findOrFail($id);

            // Eliminar la asistencia
            $asistencia->delete();

            return response()->json(['message' => 'Asistencia eliminada correctamente']);
        } catch (\Exception $e) {
            // Enviar una respuesta de error si la asistencia no se encuentra o no se puede eliminar
            return response()->json(['error' => 'No se pudo eliminar la asistencia'], 500);
        }
    }

    public function verificarAsistencia($id)
    {
        $asistencia = Asistencia::findOrFail($id);

        if (!$asistencia->ingreso_verificado || !$asistencia->salida_verificado) {
            if (!$asistencia->ingreso_verificado) {
                $asistencia->ingreso_verificado = true;
            }

            if (!$asistencia->salida_verificado) {
                $asistencia->salida_verificado = true;
            }

            $asistencia->save();

            return response()->json(['message' => 'Asistencia verificada correctamente'], 200);
        } else {
            return response()->json(['message' => 'La asistencia ya ha sido verificada'], 400);
        }
    }

    public function modificarSalida(Request $request, $id)
    {
        $asistencia = Asistencia::findOrFail($id);

        $request->validate([
            'fecha_hora_salida' => 'required',
        ]);


        $fechaIngreso = date('Y-m-d', $asistencia->fecha_hora_entrada);
        $horaSalida = $request->input('fecha_hora_salida');
        $fechaHoraSalida = $fechaIngreso . ' ' . $horaSalida;

        $marcaTiempoSalida = strtotime($fechaHoraSalida);
        $asistencia->update([
            'fecha_hora_salida' => $marcaTiempoSalida,
        ]);

        return response()->json([
            'message' => 'Asistencia actualizada exitosamente',
            'asistencia' => $asistencia
        ]);
    }
    public function RegistrarAsistenciaQR(Request $request)
    {
        $codigoQR = $request->input('codigoQR');
        $eventoID = $request->input('evento');

        // Buscar al usuario asociado al código QR proporcionado
        $personal = Personal::where('codigo_personal', $codigoQR)->first();
        if (!$personal) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }


        // Obtener el usuario completo basado en el ID del usuario asociado al personal
        $usuarioCompleto = User::find($personal->UsuarioID);
        if (!$usuarioCompleto) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Verificar si el evento existe
        $evento = Evento::find($eventoID);
        if (!$evento) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }

        $fechaHoraActual = now()->timestamp;

        // Verificar si el usuario ya ha marcado asistencia en este evento hoy
        $asistencia = Asistencia::where('UsuarioID', $usuarioCompleto->id)
            ->where('EventoID', $eventoID)
            ->whereDate('created_at', today())
            ->latest()
            ->first();

        if ($asistencia) {
            // Verificar si la fecha de entrada de la asistencia es la misma que la fecha actual
            $fechaEntrada = $asistencia->fecha_hora_entrada;
            if (date('Y-m-d', $fechaEntrada) === date('Y-m-d', $fechaHoraActual)) {
                // Si es el mismo día, actualizar la hora de salida en la asistencia existente
                $asistencia->fecha_hora_salida = $fechaHoraActual;
                $asistencia->salida_verificado = 1;
                $asistencia->save();
            } else {
                // Si es otro día, crear una nueva asistencia con la hora de entrada
                Asistencia::create([
                    'UsuarioID' => $usuarioCompleto->id,
                    'EventoID' => $eventoID,
                    'fecha_hora_entrada' => $fechaHoraActual,
                    'global' => false, // Puedes configurar esto según tus necesidades
                    'CodigoQR' => $codigoQR,
                    'ingreso_verificado' => 1,
                    'salida_verificado' => 0,
                ]);
            }
        } else {
            // Si no existe asistencia, crear una nueva asistencia con la hora de entrada
            Asistencia::create([
                'UsuarioID' => $usuarioCompleto->id,
                'EventoID' => $eventoID,
                'fecha_hora_entrada' => $fechaHoraActual,
                'global' => false, // Puedes configurar esto según tus necesidades
                'CodigoQR' => $codigoQR,
                'ingreso_verificado' => 1,
                'salida_verificado' => 0,
            ]);
        }

        return response()->json(['success' => 'Marcado registrado correctamente'], 200);
    }

    // public function obtenerHorasCumplidas()
    // {
    //     $usuarios = User::whereHas('asistencias.evento', function ($query) {
    //         $query->where('principal', true);
    //     })
    //         ->with(['asistencias' => function ($query) {
    //             $query->whereHas('evento', function ($subQuery) {
    //                 $subQuery->where('principal', true);
    //             });
    //         }, 'persona', 'roles'])
    //         ->get()
    //         ->map(function ($user) {
    //             $totalSegundos = 0;
    //             $fechaCumple250Horas = null;
    //             $horasAcumuladas = 0;

    //             foreach ($user->asistencias as $asistencia) {
    //                 $entrada = Carbon::createFromTimestamp($asistencia->fecha_hora_entrada);
    //                 $salida = Carbon::createFromTimestamp($asistencia->fecha_hora_salida);

    //                 if ($entrada->isSameDay($salida)) {
    //                     $segundos = $salida->diffInSeconds($entrada);
    //                 } else {
    //                     $segundos = 0;
    //                     $diaActual = $entrada->copy();
    //                     while ($diaActual->isBefore($salida)) {
    //                         $finDia = $diaActual->copy()->endOfDay();
    //                         if ($finDia->isAfter($salida)) {
    //                             $finDia = $salida;
    //                         }
    //                         $segundos += $finDia->diffInSeconds($diaActual);
    //                         $diaActual = $diaActual->addDay()->startOfDay();
    //                     }
    //                 }

    //                 $totalSegundos += $segundos;
    //                 $horasAcumuladas += $segundos / 3600;

    //                 if ($horasAcumuladas >= 250 && $fechaCumple250Horas === null) {
    //                     $fechaCumple250Horas = $salida;
    //                 }
    //             }

    //             $horas = floor($totalSegundos / 3600);
    //             $minutos = floor(($totalSegundos % 3600) / 60);
    //             $segundos = $totalSegundos % 60;

    //             $roles = $user->roles->pluck('name')->filter(function ($role) {
    //                 return !in_array($role, ['cliente', 'personal']);
    //             })->values();

    //             return [
    //                 'id' => $user->id,
    //                 'paterno' => $user->persona->paterno,
    //                 'materno' => $user->persona->materno,
    //                 'nombre' => $user->persona->nombre,
    //                 'email' => $user->email,
    //                 'roles' => $roles->isEmpty() ? ['Sin rol relevante'] : $roles,
    //                 'tiempo_acumulado' => [
    //                     'horas' => $horas,
    //                     'minutos' => $minutos,
    //                     'segundos' => $segundos
    //                 ],
    //                 'total_segundos' => $totalSegundos,
    //                 'fecha_cumple_250_horas' => $fechaCumple250Horas ? $fechaCumple250Horas->toDateString() : 'No alcanzado'
    //             ];
    //         })
    //         ->filter(function ($user) {
    //             return $user['total_segundos'] > 200 * 3600;
    //         })
    //         ->values();

    //     $cabecera = [["Nro", "Paterno", "Materno", "Nombre", "Correo", "Roles", "Fecha Cumplida", "Tiempo acumulado"]];

    //     $resultado = [
    //         'total' => $usuarios->count(),
    //         'cabecera' => $cabecera,
    //         'usuarios' => $usuarios
    //     ];

    //     return response()->json($resultado);
    // }
    public function obtenerHorasCumplidas()
    {
        $usuarios = User::whereHas('asistencias.evento', function ($query) {
            $query->where('principal', true);
        })
            ->with(['asistencias' => function ($query) {
                $query->whereHas('evento', function ($subQuery) {
                    $subQuery->where('principal', true);
                });
            }, 'persona', 'roles'])
            ->get()
            ->map(function ($user, $index) {
                $totalSegundos = 0;
                $fechaCumple250Horas = null;
                $horasAcumuladas = 0;

                foreach ($user->asistencias as $asistencia) {
                    $entrada = Carbon::createFromTimestamp($asistencia->fecha_hora_entrada);
                    $salida = Carbon::createFromTimestamp($asistencia->fecha_hora_salida);

                    if ($entrada->isSameDay($salida)) {
                        $segundos = $salida->diffInSeconds($entrada);
                    } else {
                        $segundos = 0;
                        $diaActual = $entrada->copy();
                        while ($diaActual->isBefore($salida)) {
                            $finDia = $diaActual->copy()->endOfDay();
                            if ($finDia->isAfter($salida)) {
                                $finDia = $salida;
                            }
                            $segundos += $finDia->diffInSeconds($diaActual);
                            $diaActual = $diaActual->addDay()->startOfDay();
                        }
                    }

                    $totalSegundos += $segundos;
                    $horasAcumuladas += $segundos / 3600;

                    if ($horasAcumuladas >= 250 && $fechaCumple250Horas === null) {
                        $fechaCumple250Horas = $salida;
                    }
                }

                $horas = floor($totalSegundos / 3600);
                $minutos = floor(($totalSegundos % 3600) / 60);
                $segundos = $totalSegundos % 60;

                $roles = $user->roles->pluck('name')->filter(function ($role) {
                    return !in_array($role, ['cliente', 'personal']);
                })->values();

                return [
                    'nro' => $index + 1,
                    'id' => $user->id,
                    'paterno' => $user->persona->paterno,
                    'materno' => $user->persona->materno,
                    'nombre' => $user->persona->nombre,
                    'email' => $user->email,
                    'roles' => $roles->isEmpty() ? ['Sin rol relevante'] : $roles,
                    'tiempo_acumulado' => [
                        'horas' => $horas,
                        'minutos' => $minutos,
                        'segundos' => $segundos
                    ],
                    'total_segundos' => $totalSegundos,
                    'fecha_cumple_250_horas' => $fechaCumple250Horas ? $fechaCumple250Horas->toDateString() : 'No alcanzado'
                ];
            })
            ->filter(function ($user) {
                return $user['total_segundos'] > 200 * 3600;
            })
            ->values();

        // Reindexamos los números después del filtrado
        $usuariosNumerados = $usuarios->values()->map(function ($user, $index) {
            $user['nro'] = $index + 1;
            return $user;
        });

        $cabecera = [["Nro", "Paterno", "Materno", "Nombre", "Correo", "Roles", "Fecha Cumplida", "Tiempo acumulado"]];

        $resultado = [
            'total' => $usuariosNumerados->count(),
            'cabecera' => $cabecera,
            'usuarios' => $usuariosNumerados
        ];

        return response()->json($resultado);
    }
}
