<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gestion\PacienteResource;
use App\Models\Gestion\Caballo;
use App\Models\Gestion\Paciente;
use App\Models\Gestion\Pago;
use App\Models\Gestion\Personal;
use App\Models\Gestion\Servicio;
use App\Models\Gestion\Sesion;
use App\Models\Persona;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PacientesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('gestion.pacientes.index');
    }

    public function obtenerPacientes(Request $request)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');
        $parametro = $request->get('parametro');

        $pacientesQuery = Paciente::query()
            ->with(['persona', 'cliente']) // Cargar las relaciones necesarias
            ->whereHas('persona', function ($query) use ($busqueda) {
                // Filtrar por nombre de persona
                $query->where('nombre', 'like', "%$busqueda%");
            });

        // Resto de la lógica de filtrado según $parametro y otras condiciones...

        $pacientes = $pacientesQuery->paginate($porPagina, ['*'], 'page', $pagina);

        $pacientesResource = PacienteResource::collection($pacientes);

        return response()->json([
            'pacientes' => $pacientesResource,
            'paginacion' => [
                'total' => $pacientes->total(),
                'porPagina' => $pacientes->perPage(),
                'paginaActual' => $pacientes->currentPage(),
                'ultimaPagina' => $pacientes->lastPage(),
                'desde' => $pacientes->firstItem(),
                'hasta' => $pacientes->lastItem(),
            ],
        ]);
    }

    public function verificarPaciente($id)
    {
        $paciente = Paciente::findOrFail($id);

        // Verifica si el paciente ya está verificado
        if ($paciente->verificado) {
            return response()->json(['message' => 'El paciente ya está verificado'], 422);
        }
        // Actualiza el campo 'verificado' a true
        $paciente->update(['verificado' => true]);
        $paciente->update(['estado' => true]);

        return response()->json(['message' => 'Paciente verificado correctamente']);
    }

    public function cambiarEstado($id)
    {
        $paciente = Paciente::find($id);

        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        // Cambiar el estado
        $paciente->estado = !$paciente->estado;
        $paciente->save();

        return response()->json(['message' => 'Estado actualizado correctamente']);
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

    public function detallePaciente(Request $request)
    {
        $query = Paciente::with(['persona', 'usuario', 'servicios']);

        if ($request->has('busqueda')) {
            $busqueda = $request->get('busqueda');
            $query->whereHas('persona', function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%$busqueda%")
                    ->orWhere('paterno', 'like', "%$busqueda%")
                    ->orWhere('materno', 'like', "%$busqueda%")
                    ->orWhere('ci', 'like', "%$busqueda%");
            });
        }

        if ($request->has('parametro')) {
            // Aquí puedes agregar más filtros según el parámetro
        }

        $pacientes = $query->paginate(10);

        return response()->json([
            'pacientes' => $pacientes
        ]);
    }

    public function detallePacienteId(Request $request, $id)
    {
        // Buscar el paciente con sus relaciones
        $paciente = Paciente::with(['persona', 'servicios'])->findOrFail($id);

        // Iniciar la consulta de servicios
        $serviciosQuery = $paciente->servicios();

        // Aplicar filtro por tipo de servicio si se proporciona
        if ($request->has('tipo_servicio') && $request->tipo_servicio !== null) {
            $serviciosQuery->where('tipo_servicio', $request->tipo_servicio);
        }

        // Aplicar filtro por rango de fechas si se proporcionan
        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay()->timestamp;
            $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay()->timestamp;
            $serviciosQuery->whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin]);
        }

        // Obtener los servicios filtrados
        $servicios = $serviciosQuery->get();

        // Formatear las fechas de los servicios
        $servicios = $servicios->map(function ($servicio) {
            $servicio->fecha_ingreso = $servicio->fecha_ingreso ? Carbon::createFromTimestamp($servicio->fecha_ingreso)->format('Y-m-d') : null;
            $servicio->fecha_final = $servicio->fecha_final ? Carbon::createFromTimestamp($servicio->fecha_final)->format('Y-m-d') : null;
            return $servicio;
        });

        // Preparar los datos del paciente
        $pacienteData = [
            'id' => $paciente->id,
            'persona' => [
                'nombre' => $paciente->persona->nombre,
                'paterno' => $paciente->persona->paterno,
                'materno' => $paciente->persona->materno,
                'ci' => $paciente->persona->ci,
                'fecha_nacimiento' => $paciente->persona->fecha_nacimiento,
            ],
            'profile_photo_path' => $paciente->profile_photo_path,
            'codigo' => $paciente->codigo,
            'tipo_paciente' => $paciente->tipo_paciente,
            'fecha_ingreso' => $paciente->fecha_ingreso,
            'estado_salud' => $paciente->estado_salud,
            'precondicion' => $paciente->precondicion,
            'contacto_emergencia_nombre' => $paciente->contacto_emergencia_nombre,
            'contacto_emergencia_telefono' => $paciente->contacto_emergencia_telefono,
            'servicios' => $servicios
        ];

        return response()->json([
            'paciente' => $pacienteData
        ]);
    }

    public function registrarServicioPaciente(Request $request, $pacienteId)
    {
        $request->validate([
            'tipo_servicio' => 'required|string',
            'precio_sesion' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string',
            'fecha_ingreso' => 'required|date',
        ]);

        $paciente = Paciente::findOrFail($pacienteId);

        $servicio = new Servicio([
            'paciente_id' => $pacienteId,
            'tipo_servicio' => $request->tipo_servicio,
            'precio_sesion' => $request->precio_sesion,
            'observaciones' => $request->observaciones,
            'fecha_ingreso' => Carbon::parse($request->fecha_ingreso)->timestamp,
            'estado' => true, // Assuming the service starts as active
        ]);

        $paciente->servicios()->save($servicio);

        return response()->json([
            'message' => 'Servicio registrado exitosamente',
            'servicio' => $servicio
        ], 201);
    }


    public function registrarPago(Request $request, $servicioId)
    {
        $request->validate([
            'fecha_pago' => 'required',
            'monto' => 'required|numeric|min:0',
            'tipo_pago' => 'required|string',
            'id_transaccion' => 'required|string',
            'razon_social' => 'required|string',
            'nit' => 'required|string',
            'estado' => 'required|string',
            'notas' => 'nullable|string',
            'comprobante' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $servicio = Servicio::findOrFail($servicioId);

        $pago = new Pago($request->except(['comprobante', 'fecha_pago', 'facturado']));
        $pago->servicio_id = $servicioId;
        $pago->fecha_pago = $request->fecha_pago;
        $pago->facturado = $request->facturado ? 1 : 0;


        if ($request->hasFile('comprobante')) {
            $comprobante = $request->file('comprobante');
            $nombreUnico = 'comprobante_' . Str::uuid() . '.' . $comprobante->getClientOriginalExtension();
            $path = $comprobante->storeAs('public/fotos/pagos', $nombreUnico);
            $pago->comprobante = 'storage/fotos/pagos/' . $nombreUnico;
        }

        $pago->save();

        return response()->json([
            'message' => 'Pago registrado exitosamente',
            'pago' => $pago
        ], 201);
    }

    // public function programarSesiones(Request $request)
    // {
    //     $request->validate([
    //         'servicioId' => 'required|uuid',
    //         'pagoId' => 'required|uuid',
    //         'sesionesDisponibles' => 'required|integer|min:1',
    //     ]);

    //     // Aquí implementa la lógica para programar las sesiones
    //     // Por ejemplo, crear registros de sesiones, actualizar el estado del pago, etc.

    //     return response()->json(['message' => 'Sesiones programadas con éxito']);
    // }


    public function programarSesiones(Request $request)
    {
        $request->validate([
            'servicioId' => 'required|uuid',
            'pagoId' => 'required|uuid',
            'sesionesDisponibles' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $servicio = Servicio::findOrFail($request->servicioId);
            $pago = Pago::findOrFail($request->pagoId);

            // Crear las sesiones programadas
            for ($i = 0; $i < $request->sesionesDisponibles; $i++) {
                Sesion::create([
                    'servicio_id' => $servicio->id,
                    'responsable' => null,
                    'apoyo_id' => null,
                    'caballo_id' => null,
                    'fecha_sesion' => null,
                    'fecha_asistencia' => null,
                    'usuario_scanner' => null,
                    'observaciones' => null,
                    'estado_sesion' => 'Programado'
                ]);
            }

            // Actualizar el estado del pago
            $pago->update([
                'consumido' => true,
            ]);

            // Actualizar el servicio
            $servicio->increment('sesiones_disponibles', $request->sesionesDisponibles);

            DB::commit();

            return response()->json([
                'message' => 'Sesiones programadas con éxito',
                'sesiones_programadas' => $request->sesionesDisponibles
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al programar las sesiones: ' . $e->getMessage()], 500);
        }
    }

    // public function obtenerSesionesServicio($id)
    // {
    //     $sesiones = Sesion::where('servicio_id', $id)->orderBy('fecha_sesion', 'desc')->get();
    //     return response()->json($sesiones);
    // }



    public function obtenerSesionesServicio($id)
    {
        $sesiones = Sesion::where('servicio_id', $id)
            ->orderBy('fecha_sesion', 'desc')
            ->get()
            ->map(function ($sesion) {
                $responsablePersonal = $sesion->responsable ? Personal::with('usuario.persona')->find($sesion->responsable) : null;
                $asistentePersonal = $sesion->asistente_id ? Personal::with('usuario.persona')->find($sesion->asistente_id) : null;
                $caballo = $sesion->caballo_id ? Caballo::find($sesion->caballo_id) : null;

                $sesion->responsable = $responsablePersonal ? [
                    'name' => $this->getNombreCompleto($responsablePersonal->usuario->persona),
                    'id' => $responsablePersonal->id
                ] : null;

                $sesion->asistente = $asistentePersonal ? [
                    'name' => $this->getNombreCompleto($asistentePersonal->usuario->persona),
                    'id' => $asistentePersonal->id
                ] : null;

                $sesion->caballo = $caballo ? [
                    'name' => $caballo->nombre . ($caballo->apodo ? " ({$caballo->apodo})" : ''),
                    'id' => $caballo->id
                ] : null;

                // Mantenemos los campos originales
                $sesion->responsable_id = $sesion->responsable;
                $sesion->asistente_id = $sesion->asistente_id;
                $sesion->caballo_id = $sesion->caballo_id;

                return $sesion;
            });

        // Agregamos un dd() para ver cómo está saliendo el array de respuesta
        // dd($sesiones->toArray());

        return response()->json($sesiones);
    }

    private function getNombreCompleto($persona)
    {
        return $persona ? trim($persona->nombre . ' ' . $persona->paterno . ' ' . $persona->materno) : null;
    }


    public function actualizarSesion(Request $request, $id)
    {
        $sesion = Sesion::findOrFail($id);

        $datosActualizados = [
            'servicio_id' => $request->input('servicio_id'),
            'responsable' => $request->input('usuario.id'),
            'asistente_id' => $request->input('apoyo.id'),
            'caballo_id' => $request->input('caballo_id'),
            'fecha_sesion' => $request->has('fecha_sesion') ? Carbon::parse($request->input('fecha_sesion'))->timestamp : null,
            'fecha_asistencia' => $request->input('fecha_asistencia'),
            'usuario_scanner' => $request->input('usuario_scanner'),
            'observaciones' => $request->input('observaciones'),
            'estado_sesion' => $request->input('estado_sesion'),
        ];

        $sesion->update($datosActualizados);

        return response()->json([
            'message' => 'Sesión actualizada exitosamente',
            'sesion' => $sesion
        ]);
    }

    public function actualizarPaciente(Request $request, $id)
    {
        $request->validate([
            'persona.nombre' => 'required|string|max:255',
            'persona.paterno' => 'required|string|max:255',
            'persona.materno' => 'required|string|max:255',
            'persona.ci' => 'required|string|max:20',
            'persona.fecha_nacimiento' => 'required|integer',
            'contacto_emergencia_nombre' => 'required|string|max:255',
            'contacto_emergencia_telefono' => 'required|string|max:20',
        ]);

        $paciente = Paciente::findOrFail($id);
        $persona = $paciente->persona;

        $persona->update([
            'nombre' => $request->input('persona.nombre'),
            'paterno' => $request->input('persona.paterno'),
            'materno' => $request->input('persona.materno'),
            'ci' => $request->input('persona.ci'),
            'fecha_nacimiento' => $request->input('persona.fecha_nacimiento'),
        ]);

        $paciente->update([
            'precondicion' => $request->input('contraindicacion'),
            'contacto_emergencia_nombre' => $request->input('contacto_emergencia_nombre'),
            'contacto_emergencia_telefono' => $request->input('contacto_emergencia_telefono'),
        ]);

        return response()->json([
            'message' => 'Paciente actualizado correctamente',
            'paciente' => $paciente->load('persona')
        ], 200);
    }

    public function eliminarPaciente($id)
    {
        DB::beginTransaction();

        try {
            $paciente = Paciente::findOrFail($id);

            // Eliminar primero el paciente
            $paciente->delete();

            $persona = $paciente->persona;
            if ($persona) {
                $persona->delete();
            }

            DB::commit();

            return response()->json([
                'message' => 'Paciente eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al eliminar el paciente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verificar($id)
    {
        try {
            $pago = Pago::findOrFail($id);
            $pago->verificado = true;
            $pago->save();
            return response()->json(['message' => 'Pago verificado con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al verificar el pago', 'error' => $e->getMessage()], 500);
        }
    }

    public function eliminarServicio($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);
            $servicio->delete();
            return response()->json(['message' => 'Servicio eliminado con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el servicio', 'error' => $e->getMessage()], 500);
        }
    }

    public function eliminarPago($id)
    {
        try {
            $pago = Pago::findOrFail($id);
            $pago->delete();
            return response()->json(['message' => 'Pago eliminado con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el pago', 'error' => $e->getMessage()], 500);
        }
    }



    public function listarPagosServicio($id)
    {
        $servicio = Servicio::findOrFail($id);
        $pagos = $servicio->pagos()->orderBy('fecha_pago', 'desc')->get();

        return response()->json($pagos);
    }

    public function buscarPaciente($codigo)
    {
        $paciente = Paciente::where('codigo', $codigo)
            ->with(['usuario', 'persona'])
            ->first();

        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        return response()->json($paciente);
    }

    public function obtenerServiciosDisponibles($pacienteId)
    {
        $servicios = Servicio::where('paciente_id', $pacienteId)
            ->whereRaw('sesiones_disponibles > sesiones_realizadas')
            ->get();

        return response()->json($servicios);
    }

    public function obtenerSesiones($servicioId)
    {
        $sesiones = Sesion::where('servicio_id', $servicioId)
            ->where('estado_sesion', '!=', 'Completado')
            ->get();
        return response()->json($sesiones);
    }

    public function registrarSesion(Request $request, $sesionId)
    {
        DB::beginTransaction();
        try {
            $sesion = Sesion::findOrFail($sesionId);
            $servicio = Servicio::findOrFail($sesion->servicio_id);

            // Update session
            $sesion->update([
                'fecha_asistencia' => now()->timestamp,
                'estado_sesion' => 'Completado',
            ]);

            // Update service
            $servicio->update([
                'sesiones_realizadas' => $servicio->sesiones_realizadas + 1,
                'ultima_actualizacion' => now()->timestamp
            ]);

            DB::commit();
            return response()->json(['message' => 'Sesión registrada con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al registrar la sesión', 'error' => $e->getMessage()], 500);
        }
    }
}
