<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Primero, eliminar la clave foránea y la columna 'cliente_id' de la tabla 'pacientes'
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']); // Eliminar la clave foránea si existe
            $table->dropColumn('cliente_id'); // Eliminar la columna
        });

        // Agregar la columna 'UsuarioID' a la tabla 'pacientes'
        Schema::table('pacientes', function (Blueprint $table) {
            $table->foreignUuid('UsuarioID')->constrained('users');
            $table->string('codigo')->nullable();
            $table->string('profile_photo_path', 2048)->nullable()->default('assets/imagenes/user-default.jpg');
        });

        // Luego, eliminar la tabla 'clientes'
        Schema::dropIfExists('clientes');

        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['sesion_id']); // Eliminar la clave foránea si existe
            $table->dropColumn('sesion_id'); // Eliminar la columna
        });

        // Agregar nuevas columnas a la tabla 'pagos'
        Schema::table('pagos', function (Blueprint $table) {
            $table->foreignUuid('servicio_id')->constrained('servicios');
            $table->decimal('monto', 10, 2)->after('servicio_id');
            $table->string('tipo_pago')->after('monto');
            $table->string('comprobante')->nullable()->after('tipo_pago');
            $table->boolean('verificado')->default(false)->after('comprobante');
            $table->boolean('consumido')->default(false)->after('verificado');
            $table->bigInteger('fecha_pago')->nullable();
            $table->string('id_transaccion')->nullable();
            $table->string('estado')->default('pendiente')->after('fecha_pago');
            $table->string('razon_social')->nullable();
            $table->string('nit')->nullable();
            $table->boolean('facturado')->default(false)->after('nit');
            $table->text('notas')->nullable()->after('estado');
        });

        Schema::table('servicios', function (Blueprint $table) {
            $table->bigInteger('ultima_actualizacion')->nullable();
            $table->decimal('precio_sesion', 10, 2)->default(0);
            $table->integer('sesiones_disponibles')->default(0);
            $table->integer('sesiones_realizadas')->default(0);
            $table->decimal('saldo_disponible', 10, 2)->default(0);
            $table->decimal('saldo_consumido', 10, 2)->default(0);
        });

        Schema::table('sesiones', function (Blueprint $table) {
            $table->dropForeign(['terapeuta']);
            $table->dropForeign(['coterapeuta_id']);
            $table->dropColumn(['terapeuta', 'coterapeuta_id']);

            $table->bigInteger('fecha_asistencia')->nullable()->after('fecha_sesion');
            $table->string('usuario_scanner')->nullable()->after('fecha_asistencia');
            $table->dropForeign(['apoyo_id']);
            $table->dropColumn('apoyo_id');

            // Luego, volvemos a agregar la columna como string
            $table->string('asistente_id')->nullable()->after('responsable');
            $table->enum('estado_sesion', ['En Progreso', 'Completado', 'Cancelado', 'Pendiente', 'Programado', 'Finalizado'])->default('Pendiente')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Volver a crear la tabla 'clientes'
        Schema::create('clientes', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('UsuarioID')->constrained('users');
            $table->string('ocupacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Revertir cambios en la tabla 'pacientes'
        Schema::table('pacientes', function (Blueprint $table) {
            $table->foreignUuid('cliente_id')->constrained('clientes')->nullable();
            $table->dropForeign(['UsuarioID']);
            $table->dropColumn(['UsuarioID', 'codigo', 'profile_photo_path']);
        });

        // Revertir cambios en la tabla 'pagos'
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['servicio_id']);
            $table->dropColumn([
                'servicio_id',
                'monto',
                'tipo_pago',
                'comprobante',
                'verificado',
                'consumido',
                'fecha_pago',
                'id_transaccion',
                'estado',
                'razon_social',
                'nit',
                'facturado',
                'notas'
            ]);
            $table->foreignUuid('sesion_id')->constrained('sesiones');
        });

        // Revertir cambios en la tabla 'servicios'
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn([
                'ultima_actualizacion',
                'precio_sesion',
                'sesiones_disponibles',
                'sesiones_realizadas',
                'saldo_disponible',
                'saldo_consumido'
            ]);
        });

        // Revertir cambios en la tabla 'sesiones'
        Schema::table('sesiones', function (Blueprint $table) {
            $table->foreignUuid('terapeuta')->nullable()->constrained('personal');
            $table->foreignUuid('coterapeuta_id')->nullable()->constrained('personal');
            $table->foreignUuid('apoyo_id')->nullable()->constrained('personal');
            $table->dropColumn(['fecha_asistencia', 'usuario_scanner', 'asistente_id']);
            $table->enum('estado_sesion', ['En Progreso', 'Completado', 'Cancelado', 'Pendiente'])->default('Pendiente')->change();
        });
    }
}
