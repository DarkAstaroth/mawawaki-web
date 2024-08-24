<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gestion\PacienteResource;
use App\Models\Gestion\Paciente;
use App\Models\Gestion\Pago;
use App\Models\Gestion\Servicio;
use App\Models\Persona;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            'fecha_pago' => 'required|date',
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
        $pago->fecha_pago = Carbon::parse($request->fecha_pago)->timestamp;
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

    public function listarPagosServicio($id)
    {
        $servicio = Servicio::findOrFail($id);
        $pagos = $servicio->pagos()->orderBy('fecha_pago', 'desc')->get();

        return response()->json($pagos);
    }
}
