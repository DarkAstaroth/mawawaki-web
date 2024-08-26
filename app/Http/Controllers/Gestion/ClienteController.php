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

    // public function obtenerPacientes(Request $request, $id)
    // {
    //     $porPagina = $request->get('porPagina', 10);
    //     $pagina = $request->get('page', 1);
    //     $busqueda = $request->get('busqueda');
    //     $parametro = $request->get('parametro');

    //     $pacientesQuery = Paciente::where('UsuarioID', $id)->with(['persona']);

    //     $pacientes = $pacientesQuery->paginate($porPagina, ['*'], 'page', $pagina);

    //     $pacientesResource = PacienteResource::collection($pacientes);

    //     return response()->json([
    //         'pacientes' => $pacientesResource,
    //         'paginacion' => [
    //             'total' => $pacientes->total(),
    //             'porPagina' => $pacientes->perPage(),
    //             'paginaActual' => $pacientes->currentPage(),
    //             'ultimaPagina' => $pacientes->lastPage(),
    //             'desde' => $pacientes->firstItem(),
    //             'hasta' => $pacientes->lastItem()
    //         ]
    //     ]);
    // }


    public function obtenerPacientes(Request $request, $id)
    {
        $pacientes = Paciente::where('UsuarioID', $id)
            ->with(['persona'])
            ->get();

        return PacienteResource::collection($pacientes);
    }


    // public function solicitudPaciente(Request $request)
    // {
    //     // Validación de datos
    //     $request->validate([
    //         'nombre' => 'required|string',
    //         'paterno' => 'required|string',
    //         'materno' => 'required|string',
    //         'ci' => 'required|string',
    //         'fechaNacimiento' => 'required|date',
    //         'estadoSalud' => 'nullable|string',
    //         'precondicion' => 'nullable|string',
    //         'contactoEmergenciaNombre' => 'nullable|string',
    //         'contactoEmergenciaTelefono' => 'nullable|string',
    //     ]);

    //     // Crear una nueva persona
    //     $persona = Persona::create([
    //         'nombre' => $request->input('nombre'),
    //         'paterno' => $request->input('paterno'),
    //         'materno' => $request->input('materno'),
    //         'ci' => $request->input('ci'),
    //         'fecha_nacimiento' => strtotime($request->input('fechaNacimiento')),
    //     ]);

    //     // Crear un nuevo paciente asociado a la persona
    //     $paciente = Paciente::create([
    //         'UsuarioID' => $request->usuario,
    //         'persona_id' => $persona->id,
    //         'codigo' => 'codigoxd',
    //         'estado_salud' => $request->input('estadoSalud'),
    //         'precondicion' => $request->input('precondicion'),
    //         'contacto_emergencia_nombre' => $request->input('contactoEmergenciaNombre'),
    //         'contacto_emergencia_telefono' => $request->input('contactoEmergenciaTelefono'),
    //         'verificado' => 1,
    //         'estado ' => 1,
    //     ]);

    //     return response()->json(['message' => 'Paciente registrado con éxito', 'paciente' => $paciente]);
    // }

    // public function solicitudPaciente(Request $request)
    // {
    //     // Validación de datos
    //     $request->validate([
    //         'nombre' => 'required|string',
    //         'paterno' => 'required|string',
    //         'materno' => 'required|string',
    //         'ci' => 'required|string',
    //         'fechaNacimiento' => 'required|date',
    //         'estadoSalud' => 'nullable|string',
    //         'precondicion' => 'nullable|string',
    //         'contactoEmergenciaNombre' => 'nullable|string',
    //         'contactoEmergenciaTelefono' => 'nullable|string',
    //     ]);

    //     // Convertir los datos a mayúsculas
    //     $datosEnMayusculas = array_map(function ($valor) {
    //         return is_string($valor) ? strtoupper($valor) : $valor;
    //     }, $request->all());

    //     // Crear una nueva persona
    //     $persona = Persona::create([
    //         'nombre' => $datosEnMayusculas['nombre'],
    //         'paterno' => $datosEnMayusculas['paterno'],
    //         'materno' => $datosEnMayusculas['materno'],
    //         'ci' => $datosEnMayusculas['ci'],
    //         'fecha_nacimiento' => strtotime($request->input('fechaNacimiento')),
    //     ]);

    //     // Crear un nuevo paciente asociado a la persona
    //     $paciente = Paciente::create([
    //         'UsuarioID' => $request->usuario,
    //         'persona_id' => $persona->id,
    //         'codigo' => 'CODIGOXD',
    //         'estado_salud' => $datosEnMayusculas['estadoSalud'] ?? null,
    //         'precondicion' => $datosEnMayusculas['precondicion'] ?? null,
    //         'contacto_emergencia_nombre' => $datosEnMayusculas['contactoEmergenciaNombre'] ?? null,
    //         'contacto_emergencia_telefono' => $datosEnMayusculas['contactoEmergenciaTelefono'] ?? null,
    //         'verificado' => 1,
    //         'estado' => 1,
    //     ]);

    //     return response()->json(['message' => 'Paciente registrado con éxito', 'paciente' => $paciente]);
    // }


    // public function solicitudPaciente(Request $request)
    // {
    //     // Validación de datos
    //     $request->validate([
    //         'nombre' => 'required|string',
    //         'paterno' => 'required|string',
    //         'materno' => 'required|string',
    //         'ci' => 'required|string',
    //         'fechaNacimiento' => 'required|date',
    //         'estadoSalud' => 'nullable|string',
    //         'precondicion' => 'nullable|string',
    //         'contactoEmergenciaNombre' => 'nullable|string',
    //         'contactoEmergenciaTelefono' => 'nullable|string',
    //     ]);

    //     // Convertir los datos a mayúsculas
    //     $datosEnMayusculas = array_map(function ($valor) {
    //         return is_string($valor) ? strtoupper($valor) : $valor;
    //     }, $request->all());

    //     // Generar un código único de 8 caracteres
    //     do {
    //         $codigo = strtoupper(Str::random(8));
    //     } while (Paciente::where('codigo', $codigo)->exists());

    //     // Crear una nueva persona
    //     $persona = Persona::create([
    //         'nombre' => $datosEnMayusculas['nombre'],
    //         'paterno' => $datosEnMayusculas['paterno'],
    //         'materno' => $datosEnMayusculas['materno'],
    //         'ci' => $datosEnMayusculas['ci'],
    //         'fecha_nacimiento' => strtotime($request->input('fechaNacimiento')),
    //     ]);

    //     // Crear un nuevo paciente asociado a la persona
    //     $paciente = Paciente::create([
    //         'UsuarioID' => $request->usuario,
    //         'persona_id' => $persona->id,
    //         'codigo' => $codigo,
    //         'estado_salud' => $datosEnMayusculas['estadoSalud'] ?? null,
    //         'precondicion' => $datosEnMayusculas['precondicion'] ?? null,
    //         'contacto_emergencia_nombre' => $datosEnMayusculas['contactoEmergenciaNombre'] ?? null,
    //         'contacto_emergencia_telefono' => $datosEnMayusculas['contactoEmergenciaTelefono'] ?? null,
    //         'verificado' => 1,
    //         'estado' => 1,
    //     ]);

    //     return response()->json(['message' => 'Paciente registrado con éxito', 'paciente' => $paciente]);
    // }


    public function solicitudPaciente(Request $request)
    {
        // Validación de datos
        $request->validate([
            'nombre' => 'required|string',
            'paterno' => 'required|string',
            'materno' => 'required|string',
            'ci' => 'required|string',
            'fechaNacimiento' => 'required|date',
            'contraindicacion' => 'nullable|string',
            'contactoEmergenciaNombre' => 'nullable|string',
            'contactoEmergenciaTelefono' => 'nullable|string',
        ]);

        // Convertir los datos a mayúsculas
        $datosEnMayusculas = array_map(function ($valor) {
            return is_string($valor) ? strtoupper($valor) : $valor;
        }, $request->all());

        // Generar un código único de 8 caracteres (números y letras mayúsculas y minúsculas)
        do {
            $codigo = $this->generateUniqueCode(8);
        } while (Paciente::where('codigo', $codigo)->exists());

        // Crear una nueva persona
        $persona = Persona::create([
            'nombre' => $datosEnMayusculas['nombre'],
            'paterno' => $datosEnMayusculas['paterno'],
            'materno' => $datosEnMayusculas['materno'],
            'ci' => $datosEnMayusculas['ci'],
            'fecha_nacimiento' => strtotime($request->input('fechaNacimiento')),
        ]);

        // Crear un nuevo paciente asociado a la persona
        $paciente = Paciente::create([
            'UsuarioID' => $request->usuario,
            'persona_id' => $persona->id,
            'codigo' => $codigo,
            'estado_salud' => "NINGUNA",
            'precondicion' => $datosEnMayusculas['contraindicacion'] ?? null,
            'contacto_emergencia_nombre' => $datosEnMayusculas['contactoEmergenciaNombre'] ?? null,
            'contacto_emergencia_telefono' => $datosEnMayusculas['contactoEmergenciaTelefono'] ?? null,
            'verificado' => 1,
            'estado' => 1,
        ]);

        return response()->json(['message' => 'Paciente registrado con éxito', 'paciente' => $paciente]);
    }

    private function generateUniqueCode($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
        }

        return $code;
    }

    public function registrarUsuario(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nombres' => 'required',
            'paterno' => 'required',
            'materno' => 'required',
            'email' => 'required|email|unique:users,email',
            'carnet_identidad' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
        ], [
            'email.unique' => 'El correo electrónico ya existe.',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'error' => 'Datos inválidos',
                'message' => $validator->errors()
            ], 422);
        }


        $fechaNacimientoTimestamp = strtotime($request->fecha_nacimiento);
        $password = Str::random(10);


        $nombres = strtoupper($request->nombres);
        $paterno = strtoupper($request->paterno);
        $materno = strtoupper($request->materno);

        $nuevaPersona = Persona::create([
            'nombre' => $nombres,
            'paterno' => $paterno,
            'materno' => $materno,
            'ci' => $request->carnet_identidad,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $fechaNacimientoTimestamp,
            'direccion' => $request->direccion,
        ]);


        $nuevoUsuario = User::create([
            'email' => $request->email,
            'gauth_type' => 'email',
            'password' => bcrypt($password),
            'persona_id' => $nuevaPersona->id,
        ]);


        Mail::send('emails.welcome', ['password' => $password], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Bienvenido a nuestro servicio');
        });


        return response()->json([
            'user' => $nuevoUsuario,
            'message' => 'Usuario registrado con éxito. Se ha enviado una contraseña al correo electrónico.'
        ], 200);
    }
}
