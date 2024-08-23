<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Gestion\Servicio;
use App\Models\Gestion\Sesion;
use Illuminate\Http\Request;

class TerapiasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gestion.terapias.index');
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
        $request->validate([
            'paciente_id' => 'required|uuid|exists:pacientes,id',
            'tipo_servicio' => 'required|in:EQUITACION,EQUINOTERAPIA',
            'observaciones' => 'nullable|string',
            'fecha_ingreso' => 'nullable|date',
            'fecha_final' => 'nullable|date',
            'estado' => 'boolean',
        ]);

        // Convierte las fechas de formato "date" a timestamp de Unix
        $fechaIngreso = $request->input('fecha_ingreso') ? strtotime($request->input('fecha_ingreso')) : null;
        $fechaFinal = $request->input('fecha_salida') ? strtotime($request->input('fecha_salida')) : null;

        $data = [
            'paciente_id' => $request->input('paciente_id'),
            'tipo_servicio' => $request->input('tipo_servicio'),
            'observaciones' => $request->input('observaciones'),
            'fecha_ingreso' => $fechaIngreso,
            'fecha_final' => $fechaFinal,
            'estado' => true
        ];

        $servicio = Servicio::create($data);

        return response()->json(['message' => 'Servicio creado con éxito', 'servicio' => $servicio], 201);
    }

    public function obtenerServicio()
    {
        $servicios = Servicio::with('paciente.persona')->get();
        return response()->json(['servicios' => $servicios], 200);
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

    public function verServicio(string $id)
    {
        $servicio = Servicio::with('paciente.persona')->findOrFail($id);
        return view('gestion.terapias.detalle', compact('servicio'));
    }

    public function verServicioPaciente(string $id)
    {
        return view('core.router', compact('id'));
    }


    public function registrarSesion(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha_sesion' => 'required|date',
            'responsable' => 'nullable|exists:personal,id',
            'terapeuta' => 'nullable|exists:personal,id',
            'coterapeuta_id' => 'nullable|exists:personal,id',
            'apoyo_id' => 'nullable|exists:personal,id',
            'caballo_id' => 'nullable|exists:caballos,id',
            'observaciones' => 'nullable|string',
            'estado_sesion' => 'required|in:En Progreso,Completado,Cancelado,Pendiente',
        ]);

        $fechaSesionUnix = strtotime($request->input('fecha_sesion'));
        $sesion = Sesion::create(array_merge($request->all(), [
            'fecha_sesion' => $fechaSesionUnix,
        ]));

        return response()->json(['sesion' => $sesion], 201);
    }
}
