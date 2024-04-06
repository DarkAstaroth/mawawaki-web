<?php

use App\Http\Controllers\Configuraciones\ModuloController;
use App\Http\Controllers\Configuraciones\PermisoController;
use App\Http\Controllers\Configuraciones\RolController;
use App\Http\Controllers\core\InvitadoController;
use App\Http\Controllers\core\UsuarioController;
use App\Http\Controllers\Gestion\CaballosController;
use App\Http\Controllers\Gestion\AsistenciasController;
use App\Http\Controllers\Gestion\AvisoController;
use App\Http\Controllers\Gestion\ClienteController;
use App\Http\Controllers\Gestion\DocumentacionController;
use App\Http\Controllers\Gestion\EventosController;
use App\Http\Controllers\Gestion\NotificacionController;
use App\Http\Controllers\Gestion\PacientesController;
use App\Http\Controllers\Gestion\PDFController;
use App\Http\Controllers\Gestion\QRcontroller;
use App\Http\Controllers\Gestion\TerapiasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('roles', [RolController::class, 'obtenerRoles']);
Route::get('roles/{id}', [RolController::class, 'edit']);
Route::post('roles/agregar', [RolController::class, 'store']);
Route::put('roles/{id}', [RolController::class, 'update']);
Route::delete('roles/{id}', [RolController::class, 'destroy']);


Route::get('modulos', [ModuloController::class, 'obtenerModulos']);
Route::get('modulos/{id}', [ModuloController::class, 'edit']);
Route::post('modulos/agregar', [ModuloController::class, 'store']);
Route::put('modulos/{id}', [ModuloController::class, 'update']);
Route::delete('modulos/{id}', [ModuloController::class, 'destroy']);

Route::get('avisos/globales', [ModuloController::class, 'obtenerAvisosGlobales']);
Route::post('aviso/crear', [AvisoController::class, 'crearNuevoAviso']);
Route::get('avisos', [AvisoController::class, 'obtenerAvisos']);
Route::delete('avisos/{id}', [AvisoController::class, 'eliminarAviso']);


// Lista de modulos con sus respectivos permisos
Route::get('modulo/permisos', [ModuloController::class, 'modulosPermisos']);


Route::get('permisos', [PermisoController::class, 'obtenerPermisos']);
Route::get('permisos/{id}', [PermisoController::class, 'edit']);
Route::post('permisos/agregar', [PermisoController::class, 'store']);
Route::put('permisos/{id}', [PermisoController::class, 'update']);
Route::delete('permisos/{id}', [PermisoController::class, 'destroy']);


// asignacion de permisos
Route::post('asignar/permisos/rol/{rolId}', [RolController::class, 'asignarPermisos']);


// Api para usuarios
Route::get('usuarios', [UsuarioController::class, 'obtenerUsuarios']);
Route::get('usuarios/filtro', [UsuarioController::class, 'obtenerUsuariosSinPaginacion']);
Route::post('imagen/usuario/{id}', [UsuarioController::class, 'subirFoto']);
Route::put('usuario/{id}', [UsuarioController::class, 'modificarUsuario']);


Route::get('fichasUsuarios', [UsuarioController::class, 'fichasUsuarios']);
Route::post('/asistencias/usuario/{id}', [AsistenciasController::class, 'obtenerAsistencias']);
Route::post('asistencias/total', [AsistenciasController::class, 'calcularTotalAsistencias']);
Route::post('asistencias/verificar/{id}', [AsistenciasController::class, 'verificarAsistencia']);


Route::put('verificar/usuario/{id}', [UsuarioController::class, 'verificarUsuario']);
Route::put('estado/usuario/{id}', [UsuarioController::class, 'cambiarEstado']);
Route::put('sincronizar/rol/usuario/{id}', [UsuarioController::class, 'sincronizarRoles']);
Route::get('convertir-mayusculas', [UsuarioController::class, 'convertirMayusculas']);
Route::post('personal/generar-codigo/{id}', [UsuarioController::class, 'generarCodigo']);



// Api para caballos
Route::get('caballos', [CaballosController::class, 'obtenerCaballos']);
Route::post('caballo/agregar', [CaballosController::class, 'agregarCaballo']);
Route::patch('caballo/{id}', [CaballosController::class, 'editarCaballo']);
Route::delete('caballo/{id}', [CaballosController::class, 'destroy']);

// Api para eventos
Route::get('eventos', [EventosController::class, 'obtenerEventos']);
Route::post('evento', [EventosController::class, 'store']);
Route::put('evento/{id}', [EventosController::class, 'update']);
Route::get('eventos/usuario/{id}', [EventosController::class, 'obtenerEventosUsuario']);
Route::post('asistencia/registrar', [AsistenciasController::class, 'registrarMarcado'])->name('registrar.marcado');
Route::get('eventos/privados', [EventosController::class, 'obtenerEventosPrivados']);
Route::get('evento/principal', [EventosController::class, 'obtenerEventosPrincipal']);
Route::get('eventos/{id}/asistentes', [EventosController::class, 'getAsistentes']);


// Api para asistencias
Route::post('asistencia/solicitar/registro', [AsistenciasController::class, 'registrarAsistenciaVerificar']);
Route::delete('asistencia/{id}', [AsistenciasController::class, 'eliminarAsistencia']);
Route::put('asistencias/modificar/salida/{id}', [AsistenciasController::class, 'modificarSalida']);




// Api para gestion de QRs
Route::post('qr/generar', [QRcontroller::class, 'generarQR']);
Route::get('qr/evento/{id}', [QRcontroller::class, 'cargarQRS']);

// Api para invitados
Route::post('invitado/enviar/solicitud', [InvitadoController::class, 'enviarSolicitud']);

// Api para documentacion
Route::post('subir-archivo/{usuarioId}', [DocumentacionController::class, 'subirArchivo']);
Route::get('obtener-documentacion/{usuario_id}', [DocumentacionController::class, 'obtenerDocumentosUsuario']);
Route::get('tipos-documento', [DocumentacionController::class, 'obtenerTiposDocumento']);
Route::delete('eliminar/archivo/{id}', [DocumentacionController::class, 'destroy']);
Route::put('documentos/{id}/revision', [DocumentacionController::class, 'cambiarEstadoRevision']);


// Api para clientes
Route::get('pacientes/cliente/{id}', [ClienteController::class, 'obtenerPacientes']);
Route::post('pacientes/registrar', [ClienteController::class, 'solicitudPaciente']);

// Api para pacientes
Route::get('pacientes', [PacientesController::class, 'obtenerPacientes']);
Route::put('verificar-paciente/{id}', [PacientesController::class, 'verificarPaciente']);
Route::put('cambiar-estado-paciente/{id}', [PacientesController::class, 'cambiarEstado']);

// Api para notificaciones
Route::post('enviar-notificacion/{id}', [NotificacionController::class, 'enviarNotificacion']);
Route::get('notificaciones/usuario/{id}', [NotificacionController::class, 'obtenerNotificaciones']);
Route::put('notificaciones/marcar-leidas/{usuarioId}', [NotificacionController::class, 'marcarTodasLeidas']);

// Api para actividades
Route::get('actividades/usuario/{id}', [UsuarioController::class, 'obtenerActividades']);
Route::post('registrar/actividad/usuario/{id}', [UsuarioController::class, 'registrarActividad']);
Route::delete('eliminar/actividad/{id}', [UsuarioController::class, 'eliminarActividad']);
Route::patch('/verificar/actividad/{id}', [UsuarioController::class, 'verificarActividad']);
Route::patch('/destacar/actividad/{id}', [UsuarioController::class, 'destacarActividad']);


//Api para servicios
Route::get('servicios', [TerapiasController::class, 'obtenerServicio']);
Route::post('servicios', [TerapiasController::class, 'store']);
Route::post('sesiones', [TerapiasController::class, 'registrarSesion']);


//Api para obtener personal
Route::get('personal', [UsuarioController::class, 'obtenerPersonal']);

// API para configuraciones generales
Route::get('eventos/principal', [EventosController::class, 'verificarEventoPrincipal']);
Route::get('eventos/principal-false', [EventosController::class, 'eventosPrincipalFalse']);
Route::put('eventos/asignar', [EventosController::class, 'establecerPrincipal']);


//API para los PDF
Route::get('pdf/usuarios', [PDFController::class, 'usuariosSistema']);
Route::post('pdf/asistencias/principal/usuario', [AsistenciasController::class, 'generarPDFAsistencias']);
