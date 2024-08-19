<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Http\Resources\PacienteResource;
use App\Models\Gestion\Cliente;
use App\Models\Gestion\Paciente;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{

    public function index()
    {
        return view('core.router');
    }

    public function create()
    {
        return view('core.router');
    }

    public function listaPacientes(string $id)
    {
        $usuario = User::with('persona', 'cliente')->findOrFail($id);
        return view('clientes.pacientes', compact('usuario'));
    }

    public function obtenerPacientes(Request $request, $id)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');
        $parametro = $request->get('parametro');

        $cliente = Cliente::where('UsuarioID', $id)->firstOrFail();

        $pacientesQuery = $cliente->pacientes()->with(['persona']);

        if ($parametro === 'todos') {
            // Mantener todos los pacientes
        }

        if ($parametro === 'activos') {
            $pacientesQuery->where('estado', 1);
        }

        if ($parametro === 'inactivos') {
            $pacientesQuery->where('estado', 0);
        }

        if ($busqueda) {
            $busquedaSinEspacios = str_replace(' ', '', $busqueda);
            $pacientesQuery->where(function ($query) use ($busquedaSinEspacios) {
                $query->whereRaw("REPLACE(personas.nombre, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                    ->orWhereRaw("REPLACE(personas.paterno, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                    ->orWhereRaw("REPLACE(personas.materno, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
            });
        }

        $pacientes = $pacientesQuery->paginate($porPagina, ['*'], 'page', $pagina);

        $pacientesResource = PacienteResource::collection($pacientes);

        return response()->json([
            'pacientes' => $pacientesResource,
            'cliente' => $cliente,
            'paginacion' => [
                'total' => $pacientes->total(),
                'porPagina' => $pacientes->perPage(),
                'paginaActual' => $pacientes->currentPage(),
                'ultimaPagina' => $pacientes->lastPage(),
                'desde' => $pacientes->firstItem(),
                'hasta' => $pacientes->lastItem()
            ]
        ]);
    }

    public function solicitudPaciente(Request $request)
    {
        // Validación de datos
        $request->validate([
            'nombre' => 'required|string',
            'paterno' => 'required|string',
            'materno' => 'required|string',
            'ci' => 'required|string|unique:personas',
            'fechaNacimiento' => 'required|date',
            'tipoPaciente' => 'required|in:interno,externo',
            'estadoSalud' => 'nullable|string',
            'precondicion' => 'nullable|string',
            'contactoEmergenciaNombre' => 'nullable|string',
            'contactoEmergenciaTelefono' => 'nullable|string',
        ]);

        // Crear una nueva persona
        $persona = Persona::create([
            'nombre' => $request->input('nombre'),
            'paterno' => $request->input('paterno'),
            'materno' => $request->input('materno'),
            'ci' => $request->input('ci'),
            'fecha_nacimiento' => strtotime($request->input('fechaNacimiento')),
        ]);

        // Crear un nuevo paciente asociado a la persona
        $paciente = Paciente::create([
            'cliente_id' => $request->cliente,
            'persona_id' => $persona->id,
            'tipo_paciente' => $request->input('tipoPaciente'),
            'estado_salud' => $request->input('estadoSalud'),
            'precondicion' => $request->input('precondicion'),
            'contacto_emergencia_nombre' => $request->input('contactoEmergenciaNombre'),
            'contacto_emergencia_telefono' => $request->input('contactoEmergenciaTelefono'),
        ]);

        return response()->json(['message' => 'Paciente registrado con éxito', 'paciente' => $paciente]);
    }

    // public function registrarUsuario(Request $request)
    // {
    //     // Validación de los datos de entrada
    //     $validator = Validator::make($request->all(), [
    //         'nombres' => 'required',
    //         'paterno' => 'required',
    //         'materno' => 'required',
    //         'email' => 'required|email|unique:users,email',
    //         'carnet_identidad' => 'required',
    //         'telefono' => 'required',
    //         'fecha_nacimiento' => 'required',
    //         'direccion' => 'required',
    //     ], [
    //         'email.unique' => 'El correo electrónico ya existe.',
    //     ]);

    //     // Si hay errores de validación, devolver el error con código 422
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'error' => 'Datos inválidos',
    //             'message' => $validator->errors()
    //         ], 422);
    //     }

    //     // Generar una contraseña aleatoria
    //     $password = Str::random(10); // Genera una contraseña aleatoria de 10 caracteres

    //     // Convertir nombres y apellidos a mayúsculas
    //     $nombres = strtoupper($request->nombres);
    //     $paterno = strtoupper($request->paterno);
    //     $materno = strtoupper($request->materno);

    //     // Creación de la nueva persona
    //     $nuevaPersona = Persona::create([
    //         'nombre' => $nombres,
    //         'paterno' => $paterno,
    //         'materno' => $materno,
    //     ]);

    //     // Creación del nuevo usuario
    //     $nuevoUsuario = User::create([
    //         'email' => $request->email,
    //         'gauth_type' => 'email',
    //         'password' => bcrypt($password), // Almacena la contraseña encriptada
    //         'persona_id' => $nuevaPersona->id,
    //     ]);

    //     // Asignación del rol al nuevo usuario
    //     $nuevoUsuario->assignRole('cliente');

    //     // Enviar la contraseña al correo electrónico
    //     Mail::send('emails.welcome', ['password' => $password], function ($message) use ($request) {
    //         $message->to($request->email)
    //             ->subject('Bienvenido a nuestro servicio');
    //     });

    //     // Devolver los datos del usuario creado como JSON con código 200
    //     return response()->json([
    //         'user' => $nuevoUsuario,
    //         'message' => 'Usuario registrado con éxito. Se ha enviado una contraseña al correo electrónico.'
    //     ], 200);
    // }

    public function registrarUsuario(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'nombres' => 'required',
            'paterno' => 'required',
            'materno' => 'required',
            'email' => 'required|email|unique:users,email',
            'carnet_identidad' => 'required',
            'telefono' => 'required',
            'fecha_nacimiento' => 'required',
            'direccion' => 'required',
        ], [
            'email.unique' => 'El correo electrónico ya existe.',
        ]);

        // Si hay errores de validación, devolver el error con código 422
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Datos inválidos',
                'message' => $validator->errors()
            ], 422);
        }

        // Convertir la fecha de nacimiento a timestamp Unix
        $fechaNacimientoTimestamp = strtotime($request->fecha_nacimiento);

        // Generar una contraseña aleatoria
        $password = Str::random(10); // Genera una contraseña aleatoria de 10 caracteres

        // Convertir nombres y apellidos a mayúsculas
        $nombres = strtoupper($request->nombres);
        $paterno = strtoupper($request->paterno);
        $materno = strtoupper($request->materno);

        // Creación de la nueva persona
        $nuevaPersona = Persona::create([
            'nombre' => $nombres,
            'paterno' => $paterno,
            'materno' => $materno,
            'ci' => $request->carnet_identidad,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $fechaNacimientoTimestamp,
            'direccion' => $request->direccion,
        ]);

        // Creación del nuevo usuario
        $nuevoUsuario = User::create([
            'email' => $request->email,
            'gauth_type' => 'email',
            'password' => bcrypt($password), // Almacena la contraseña encriptada
            'persona_id' => $nuevaPersona->id,
        ]);

        // Asignación del rol al nuevo usuario


        // Enviar la contraseña al correo electrónico
        Mail::send('emails.welcome', ['password' => $password], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Bienvenido a nuestro servicio');
        });

        // Devolver los datos del usuario creado como JSON con código 200
        return response()->json([
            'user' => $nuevoUsuario,
            'message' => 'Usuario registrado con éxito. Se ha enviado una contraseña al correo electrónico.'
        ], 200);
    }
}
