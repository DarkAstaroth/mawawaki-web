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

        // Volver a agregar la columna 'cliente_id' a la tabla 'pacientes'
        Schema::table('pacientes', function (Blueprint $table) {
            $table->foreignUuid('cliente_id')->constrained('clientes')->nullable(); // Reagregar la clave foránea
            $table->dropForeign(['UsuarioID']); // Eliminar la clave foránea si existe
            $table->dropColumn(['UsuarioID', 'codigo', 'profile_photo_path']); // Eliminar las columnas añadidas
        });

        // Eliminar las nuevas columnas de la tabla 'pagos'
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn([
                'monto',
                'tipo_pago',
                'comprobante',
                'verificado',
                'fecha_pago',
                'estado',
                'notas'
            ]);
        });
    }
}
