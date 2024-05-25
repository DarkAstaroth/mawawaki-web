<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar la tabla qrs
        Schema::table('qrs', function (Blueprint $table) {
            // Verificar si la restricción de clave foránea ya existe
            if (!Schema::hasColumn('qrs', 'EventoID')) {
                $table->uuid('EventoID');
            }
            // Agregar la clave foránea con eliminación en cascada si no existe
            if (!Schema::hasTable('qrs_eventoid_foreign')) {
                $table->foreign('EventoID')
                    ->references('id')->on('eventos')
                    ->onDelete('cascade')
                    ->name('qrs_eventoid_foreign');
            }
        });

        // Modificar la tabla asistencias
        Schema::table('asistencias', function (Blueprint $table) {
            // Verificar si la restricción de clave foránea ya existe
            if (!Schema::hasColumn('asistencias', 'EventoID')) {
                $table->uuid('EventoID');
            }
            // Agregar la clave foránea con eliminación en cascada si no existe
            if (!Schema::hasTable('asistencias_eventoid_foreign')) {
                $table->foreign('EventoID')
                    ->references('id')->on('eventos')
                    ->onDelete('cascade')
                    ->name('asistencias_eventoid_foreign');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir los cambios en la tabla qrs
        Schema::table('qrs', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea si existe
            if (Schema::hasTable('qrs_eventoid_foreign')) {
                $table->dropForeign(['EventoID']);
            }
        });

        // Revertir los cambios en la tabla asistencias
        Schema::table('asistencias', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea si existe
            if (Schema::hasTable('asistencias_eventoid_foreign')) {
                $table->dropForeign(['EventoID']);
            }
        });
    }
};
