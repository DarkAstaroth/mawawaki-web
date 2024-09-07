<?php

namespace App\Http\Controllers\core;

use App\Http\Controllers\Controller;
use App\Http\Resources\core\UsuarioResource;
use App\Http\Resources\Gestion\ActividadResource;
use App\Models\Gestion\Actividad;
use App\Models\Gestion\Personal;
use App\Models\Persona;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('core.router');
    }

    public function vistaConfiguracion()
    {
        return view('configuraciones.general');
    }

    public function obtenerUsuarios(Request $request)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');
        $parametro = $request->get('parametro');
        $rol = $request->get('rol'); // Nuevo parámetro para el rol

        // Base query
        $usuariosQuery = User::query();

        // Filtrar por parámetro
        if ($parametro === 'activos') {
            $usuariosQuery->where('estado', 1)
                ->where('solicitud', 1)
                ->where('verificada', 1)
                ->whereNotNull('email_verified_at');
        } elseif ($parametro === 'inactivos') {
            $usuariosQuery->where('estado', 0);
        } elseif ($parametro === 'solicitudes') {
            $usuariosQuery->where('estado', 0)
                ->where('solicitud', 1)
                ->where('verificada', 0)
                ->whereNotNull('email_verified_at');
        } elseif ($parametro === 'verificados') {
            $usuariosQuery->whereNotNull('email_verified_at');
        } elseif ($parametro === 'porVerificar') {
            $usuariosQuery->where('estado', 0)
                ->where('solicitud', 0)
                ->where('verificada', 0)
                ->whereNull('email_verified_at');
        }

        // Filtrar por rol si se proporciona
        if ($rol) {
            $usuariosQuery->whereHas('roles', function ($query) use ($rol) {
                $query->where('name', $rol);
            });
        }

        // Filtrar por búsqueda
        if ($busqueda) {
            $busquedaSinEspacios = str_replace(' ', '', $busqueda);
            $usuariosQuery->where(function ($query) use ($busquedaSinEspacios) {
                $query->whereRaw("REPLACE(name, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                    ->orWhereHas('persona', function ($subquery) use ($busquedaSinEspacios) {
                        $subquery->whereRaw("REPLACE(nombre, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                            ->orWhereRaw("REPLACE(paterno, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                            ->orWhereRaw("REPLACE(materno, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
                    })
                    ->orWhereRaw("REPLACE(email, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
            });
        }

        // Paginar los resultados
        $usuarios = $usuariosQuery->paginate($porPagina, ['*'], 'page', $pagina);
        $usuariosIds = $usuarios->pluck('id')->toArray();

        // Cargar roles y persona para los usuarios paginados
        $usuariosConRoles = User::with(['roles', 'persona'])->whereIn('id', $usuariosIds)->get();

        // Agregar campos adicionales a los usuarios
        $usuariosConCamposAdicionales = $usuariosConRoles->map(function ($usuario) {
            $usuario->profile_photo_path = env('APP_URL') . '/' . $usuario->profile_photo_path;
            $usuario->nuevo_campo = "Valor del nuevo campo para este usuario";
            $usuario->estado = $usuario->estado == 1 ? true : false;

            return $usuario;
        });

        // Convertir usuarios a recursos
        $usuariosResource = UsuarioResource::collection($usuariosConCamposAdicionales);
        $resultadoBusqueda = $usuariosResource->isEmpty() ? [] : $usuariosResource;

        // Responder con datos paginados
        return response()->json([
            'usuarios' => $resultadoBusqueda,
            'paginacion' => [
                'total' => $usuarios->total(),
                'porPagina' => $usuarios->perPage(),
                'paginaActual' => $usuarios->currentPage(),
                'ultimaPagina' => $usuarios->lastPage(),
                'desde' => $usuarios->firstItem(),
                'hasta' => $usuarios->lastItem()
            ]
        ]);
    }


    public function obtenerUsuariosSinPaginacion()
    {
        // Obtener solo los usuarios con el rol "personal"
        $users = User::with('persona', 'personal')
            ->role('personal')
            ->get();


        // Devolver la respuesta JSON con la estructura deseada
        return response()->json(['usuarios' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {}


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return redirect()->intended(route('login'))->with('error', 'El correo electrónico proporcionado no existe.');
        }

        if (Auth::attempt($credentials)) {
            if (session()->has('url.intended')) {
                return redirect()->intended();
            } else {
                return redirect()->route('dashboard');
            }
        } else {
            return redirect()->intended(route('login'))->with('error', 'La contraseña proporcionada es incorrecta.');
        }
    }



    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        session()->flush();
        return redirect('/');
    }

    public function verificarCuenta()
    {
        return view('autenticacion.cuenta');
    }

    public function fichasUsuarios(Request $request)
    {
        $rol = $request->query('rol');
        $query = User::query();

        if ($rol === 'cliente') {
            // Filtrar solo usuarios con el rol 'cliente'
            $query->whereHas('roles', function ($query) {
                $query->where('name', 'cliente');
            });
        } else {
            // Filtrar todos los usuarios menos los que tienen el rol 'cliente'
            $query->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'cliente');
            });
        }

        $totalUsuarios = $query->count();

        $activos = $query->where('estado', 1)
            ->where('solicitud', 1)
            ->where('verificada', 1)
            ->whereNotNull('email_verified_at')
            ->count();

        $no_activos = $query->where('estado', 0)
            ->count();

        $solicitudes = $query->where('estado', 0)
            ->where('solicitud', 1)
            ->where('verificada', 0)
            ->whereNotNull('email_verified_at')
            ->count();

        $verificados = $query->whereNotNull('email_verified_at')->count();

        $porVerificados = $query->where('estado', 0)
            ->where('solicitud', 0)
            ->where('verificada', 0)
            ->whereNull('email_verified_at')
            ->count();

        $response = [
            'total_usuarios' => $totalUsuarios,
            'activos' => $activos,
            'no_activos' => $no_activos,
            'solicitudes' => $solicitudes,
            'verificados' => $verificados,
            'por_verificar' => $porVerificados,
        ];

        return response()->json($response);
    }

    public function fichasActividad($id)
    {
        // Consultar las actividades del usuario
        $actividades = Actividad::where('id_usuario', $id)->get();

        // Calcular los totales
        $total = $actividades->count();
        $verificados = $actividades->where('verificada', true)->count();
        $destacados = $actividades->where('destacada', true)->count();

        // Preparar la respuesta
        $fichaActividad = [
            'total' => $total,
            'verificados' => $verificados,
            'destacados' => $destacados,
        ];

        // Retornar la respuesta en formato JSON
        return response()->json(['fichaActividad' => $fichaActividad]);
    }



    public function PerfilUsuario(string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->load('roles', 'permissions', 'persona', 'personal');
        return view('core.usuarios.perfil', compact('usuario'));
    }

    public function UsuarioControl(string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->load('roles', 'permissions', 'persona', 'personal',);
        return view('core.usuarios.control', compact('usuario'));
    }

    public function verificarUsuario(string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->verificada = 1;
        $usuario->estado = 1;


        if ($usuario->email_verified_at === null) {
            $usuario->email_verified_at = now();
        }

        $usuario->save();

        return response()->json([
            'success' => 'El usuario fue verificado correctamente',
            'usuario' => $usuario
        ]);
    }

    public function cambiarEstado(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->estado = !$request->estado;
        $usuario->save();

        return response()->json([
            'success' => 'Estado actualizado correctamente',
            'usuario' => $usuario
        ]);
    }

    public function sincronizarRoles(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->syncRoles($request->roles);
        return response()->json([
            'success' => 'Roles actualizados con exito',
        ]);
    }

    public function actividadesUsuario(string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->load('roles', 'permissions', 'persona');
        return view('core.usuarios.actividades', compact('usuario'));
    }

    public function obtenerActividades($id)
    {
        $porPagina = request('porPagina', 10);
        $pagina = request('page', 1);
        $busqueda = request('busqueda');
        $parametro = request('parametro');

        $usuario = User::findOrFail($id);
        $actividadesQuery = $usuario->actividades();

        if ($busqueda) {
            $actividadesQuery->where(function ($query) use ($busqueda) {
                $query->where('titulo', 'like', "%$busqueda%")
                    ->orWhere('descripcion', 'like', "%$busqueda%");
            });
        }


        $actividades = $actividadesQuery->paginate($porPagina, ['*'], 'page', $pagina);


        $actividadesResource = ActividadResource::collection($actividades);


        $resultadoBusqueda = $actividadesResource->isEmpty() ? [] : $actividadesResource;

        return response()->json([
            'actividades' => $resultadoBusqueda,
            'paginacion' => [
                'total' => $actividades->total(),
                'porPagina' => $actividades->perPage(),
                'paginaActual' => $actividades->currentPage(),
                'ultimaPagina' => $actividades->lastPage(),
                'desde' => $actividades->firstItem(),
                'hasta' => $actividades->lastItem()
            ]
        ]);
    }

    public function registrarActividad(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required',
            'descripcion' => 'required',
            'fecha' => 'required|date',
        ]);


        $usuario = User::findOrFail($id);


        $actividad = new Actividad([
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'fecha' => strtotime($request->input('fecha'))

        ]);


        $actividad->usuario()->associate($usuario);


        $actividad->save();


        return response()->json(['mensaje' => 'Actividad registrada con éxito']);
    }

    public function eliminarActividad($id)
    {
        $actividad = Actividad::find($id);

        if (!$actividad) {
            return response()->json(['mensaje' => 'Actividad no encontrada'], 404);
        }

        $actividad->delete();

        return response()->json(['mensaje' => 'Actividad eliminada correctamente']);
    }

    public function verificarActividad($id)
    {
        $actividad = Actividad::findOrFail($id);
        $actividad->verificada = true;
        $actividad->save();

        return response()->json(['message' => 'Actividad verificada correctamente']);
    }

    public function destacarActividad($id)
    {
        $actividad = Actividad::findOrFail($id);
        $actividad->destacada = true;
        $actividad->save();

        return response()->json(['message' => 'Actividad destacada correctamente']);
    }

    public function obtenerPersonal()
    {
        $personales = Personal::with(['usuario', 'usuario.persona'])->get();
        return response()->json(['personales' => $personales]);
    }

    public function subirFoto(Request $request, $id)
    {
        // Verificar si el usuario existe
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }


        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')[0]; // Obtener el primer elemento del array

            // Obtener el nombre original y la extensión del archivo
            $nombreFoto = $foto->getClientOriginalName();
            $extension = $foto->getClientOriginalExtension();
            $nombreUnico = 'foto_' . $usuario->id . '.' . $extension;
            $foto->storeAs('public/fotos', $nombreUnico);
            $usuario->update(['profile_photo_path' => 'storage/fotos/' . $nombreUnico]);
            return response()->json(['mensaje' => 'Foto subida correctamente'], 200);
        }

        return response()->json(['error' => 'No se encontró ninguna foto para subir'], 400);
    }


    public function modificarUsuario(Request $request, $id)
    {
        $userData = $request->input('usuario');
        $user = User::findOrFail($id);

        $personaData = $userData['persona'] ?? [];
        $personalData = $userData['personal'] ?? [];

        if (!empty($personaData)) {
            if (isset($personaData['fecha_nacimiento'])) {
                $personaData['fecha_nacimiento'] = Carbon::parse($personaData['fecha_nacimiento'])->timestamp;
            }

            if ($user->persona) {
                $user->persona()->update($personaData);
            }
        }

        if (!empty($personalData)) {
            if ($user->personal) {
                $user->personal()->update($personalData);
            }
        }

        return response()->json(['message' => 'Datos modificados correctamente']);
    }

    public function convertirMayusculas()
    {
        $personas = Persona::all();

        foreach ($personas as $persona) {
            $persona->nombre = mb_strtoupper($persona->nombre, 'UTF-8');
            $persona->paterno = mb_strtoupper($persona->paterno, 'UTF-8');
            $persona->materno = mb_strtoupper($persona->materno, 'UTF-8');
            $persona->save();
        }


        return response()->json(['message' => 'Campos convertidos a mayúsculas correctamente'], 200);
    }

    public function generarCodigo($id)
    {
        // Generar un código único de 6 caracteres (números y letras)
        $codigo = Str::random(6);

        // Verificar que el código sea único
        while (Personal::where('codigo_personal', $codigo)->exists()) {
            $codigo = Str::random(6);
        }

        // Actualizar el campo codigo_personal en la fila correspondiente al usuario
        Personal::where('UsuarioID', $id)->update(['codigo_personal' => $codigo]);
        return response()->json(['codigo' => $codigo], 201);
    }

    public function getBirthdays(Request $request)
    {
        $currentMonth = Carbon::now()->month;


        $usersWithBirthdays = User::whereHas('persona', function ($query) use ($currentMonth) {
            $query->whereRaw('MONTH(FROM_UNIXTIME(fecha_nacimiento)) = ?', [$currentMonth]);
        })->with(['persona' => function ($query) {}])->get();

        $formattedUsers = $usersWithBirthdays->map(function ($user) {
            return [
                'email' => $user->email,
                'persona' => [
                    'ci' => $user->persona->ci,
                    'nombre' => $user->persona->nombre,
                    'paterno' => $user->persona->paterno,
                    'materno' => $user->persona->materno,
                    'fecha_nacimiento' => Carbon::createFromTimestamp($user->persona->fecha_nacimiento)->format('Y-m-d'),
                    'telefono' => $user->persona->telefono,
                    'direccion' => $user->persona->direccion,
                ]
            ];
        });

        return response()->json($formattedUsers);
    }
    public function cambiarCorreo(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $usuario = User::findOrFail($id);
        $usuario->email = $request->email;
        $usuario->save();

        return response()->json(['success' => true, 'message' => 'Correo actualizado con éxito']);
    }

    public function cambiarContrasena(Request $request, $id)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8',
        ]);

        $usuario = User::findOrFail($id);

        if (!Hash::check($request->old_password, $usuario->password)) {
            return response()->json(['success' => false, 'message' => 'La contraseña actual es incorrecta'], 400);
        }

        $usuario->password = Hash::make($request->new_password);
        $usuario->save();

        return response()->json(['success' => true, 'message' => 'Contraseña actualizada con éxito']);
    }
}
