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
            $table->dropForeign(['persona_id']); // Eliminar la clave foránea si existe
            $table->dropColumn('persona_id'); // Eliminar la columna
        });

        // Agregar la columna 'UsuarioID' a la tabla 'pacientes'
        Schema::table('pacientes', function (Blueprint $table) {
            $table->foreignUuid('UsuarioID')->constrained('users'); // Agregar la clave foránea
        });

        // Luego, eliminar la tabla 'clientes'
        Schema::dropIfExists('clientes');
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
            $table->dropColumn('UsuarioID'); // Eliminar la columna
        });
    }
}
