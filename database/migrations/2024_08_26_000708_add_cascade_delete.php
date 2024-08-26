<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // Elimina la clave foránea existente si la hay
            $table->dropForeign(['persona_id']);

            // Añade la nueva clave foránea con la opción de cascada
            $table->foreign('persona_id')
                ->references('id')
                ->on('personas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // Elimina la clave foránea con cascada
            $table->dropForeign(['persona_id']);

            // Vuelve a crear la clave foránea sin la opción de cascada
            $table->foreign('persona_id')
                ->references('id')
                ->on('personas');
        });
    }
};
