<?php

namespace App\Http\Controllers\core;

use App\Http\Controllers\Controller;
use App\Http\Resources\core\UsuarioResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('core.usuarios.index');
    }

    public function obtenerUsuarios(Request $request)
    {
        $porPagina = $request->get('porPagina', 10);
        $pagina = $request->get('page', 1);
        $busqueda = $request->get('busqueda');
        $parametro = $request->get('parametro');

        if ($parametro === 'todos') {
            $usuariosQuery = User::query();
        }

        if ($parametro === 'activos') {
            $usuariosQuery = User::query()
                ->where('estado', 1)
                ->where('solicitud', 1)
                ->where('verificada', 1)
                ->whereNotNull('email_verified_at');
        }

        if ($parametro === 'inactivos') {
            $usuariosQuery = User::query()
                ->where('estado', 0)
                ->where('solicitud', 1)
                ->where('verificada', 1)
                ->whereNotNull('email_verified_at');
        }

        if ($parametro === 'solicitudes') {
            $usuariosQuery = User::query()
                ->where('estado', 0)
                ->where('solicitud', 1)
                ->where('verificada', 0)
                ->whereNotNull('email_verified_at');
        }

        if ($parametro === 'verificados') {
            $usuariosQuery = User::query()
                ->whereNotNull('email_verified_at');
        }

        if ($parametro === 'porVerificar') {
            $usuariosQuery = User::query()
                ->where('estado', 0)
                ->where('solicitud', 0)
                ->where('verificada', 0)
                ->whereNull('email_verified_at');
        }

        if ($busqueda) {
            $busquedaSinEspacios = str_replace(' ', '', $busqueda);
            $usuariosQuery->where(function ($query) use ($busquedaSinEspacios) {
                $query->whereRaw("REPLACE(name, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"])
                    ->orWhereRaw("REPLACE(email, ' ', '') LIKE ?", ["%$busquedaSinEspacios%"]);
            });
        }

        $usuarios = $usuariosQuery->paginate($porPagina, ['*'], 'page', $pagina);
        $usuariosIds = $usuarios->pluck('id')->toArray();
        $usuariosConRoles = User::with('roles')->whereIn('id', $usuariosIds)->get();

        $usuariosConCamposAdicionales = $usuariosConRoles->map(function ($usuario) {
            $usuario->profile_photo_path = env('APP_URL') . '/' . $usuario->profile_photo_path;
            $usuario->nuevo_campo = "Valor del nuevo campo para este usuario";
            return $usuario;
        });

        $usuariosResource = UsuarioResource::collection($usuariosConCamposAdicionales);
        $resultadoBusqueda = $usuariosResource->isEmpty() ? [] : $usuariosResource;

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

    public function fichasUsuarios()
    {
        $response = [];

        // Usuarios activos (estado = 1)
        $activos = User::where('estado', 1)
            ->where('solicitud', 1)
            ->where('verificada', 1)
            ->whereNotNull('email_verified_at')
            ->count();
        $response['activos'] = $activos;

        $no_activos = User::where('estado', 0)
            ->where('solicitud', 1)
            ->where('verificada', 1)
            ->whereNotNull('email_verified_at')
            ->count();
        $response['no_activos'] = $no_activos;

        // Usuarios con solicitudes (estado = 0, solicitud = 1, verificado = 1)
        $solicitudes = User::where('estado', 0)
            ->where('solicitud', 1)
            ->where('verificada', 0)
            ->whereNotNull('email_verified_at')
            ->count();
        $response['solicitudes'] = $solicitudes;

        // Usuarios verificados (email_verified_at no es null)
        $verificados = User::whereNotNull('email_verified_at')->count();
        $response['verificados'] = $verificados;

        $porVerificados = User::where('estado', 0)
            ->where('solicitud', 0)
            ->where('verificada', 0)
            ->whereNull('email_verified_at')
            ->count();
        $response['por_verificar'] = $porVerificados;

        return response()->json($response);
    }



    public function PerfilUsuario(string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->load('roles', 'permissions');
        return view('core.usuarios.perfil', compact('usuario'));
    }

    public function UsuarioControl(string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->load('roles', 'permissions');
        return view('core.usuarios.control', compact('usuario'));
    }

    public function verificarUsuario(string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->verificada = 1;
        $usuario->estado = 1;
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
}
