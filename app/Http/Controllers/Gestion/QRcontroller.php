<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gestion\QRResource;
use App\Models\Gestion\Evento;
use App\Models\Gestion\QR;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class QRcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function cargarQRS(Request $request, string $id)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');

        $evento = Evento::with('qrs')->find($id);

        if (!$evento) {
            return response()->json(['message' => 'El evento no se encontró'], 404);
        }

        $qrsQuery = $evento->qrs();

        // Aplicar filtros
        if ($busqueda) {
            $busquedaSinEspacios = str_replace(' ', '', $busqueda);
            $qrsQuery->where(function ($query) use ($busquedaSinEspacios) {
                $query->whereRaw("REPLACE(CodigoQR, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
            });
        }


        $qrs = $qrsQuery->paginate($porPagina, ['*'], 'page', $pagina);

        $qrResource = QRResource::collection($qrs);
        $resultadoBusqueda = $qrResource->isEmpty() ? [] : $qrResource;


        return response()->json([
            'qrs' => $resultadoBusqueda,
            'paginacion' => [
                'total' => $qrs->total(),
                'porPagina' => $qrs->perPage(),
                'paginaActual' => $qrs->currentPage(),
                'ultimaPagina' => $qrs->lastPage(),
                'desde' => $qrs->firstItem(),
                'hasta' => $qrs->lastItem(),
            ],
        ]);
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

    // public function generarQR(Request $request)
    // {
    //     $nuevoQr = new QR();
    //     $nuevoQr->EventoID = $request->input('idEvento');
    //     $nuevoQr->CodigoQR = (string) Str::uuid();
    //     $nuevoQr->fecha_vencimiento = $request->input('fechaVencimiento') === null ? null : strtotime($request->input('fechaVencimiento'));
    //     $nuevoQr->cantidad_usos = $request->input('cantidadUsos');
    //     $nuevoQr->cuota = $request->input('cantidadUsos');
    //     $nuevoQr->save();

    //     return response()->json(['message' => 'Evento creado con éxito'], 201);
    // }

    public function generarQR(Request $request)
    {
        // Crear un nuevo registro de QR
        $nuevoQr = new QR();
        $nuevoQr->EventoID = $request->input('idEvento');
        $nuevoQr->CodigoQR = (string) Str::uuid(); // Generar un UUID como código QR
        $nuevoQr->fecha_vencimiento = $request->input('fechaVencimiento') ? strtotime($request->input('fechaVencimiento')) : null;
        $nuevoQr->cantidad_usos = $request->input('cantidadUsos');
        $nuevoQr->cuota = $request->input('cantidadUsos');

        // Guardar el nuevo registro en la base de datos
        $nuevoQr->save();

        // Devolver el objeto nuevo QR con los datos relevantes
        return response()->json([
            'message' => 'Evento creado con éxito',
            'nuevoQr' => [
                'EventoID' => $nuevoQr->EventoID,
                'CodigoQR' => $nuevoQr->CodigoQR,
                'fecha_vencimiento' => $nuevoQr->fecha_vencimiento,
                'cantidad_usos' => $nuevoQr->cantidad_usos,
                'cuota' => $nuevoQr->cuota,
            ]
        ], 201);
    }
}
