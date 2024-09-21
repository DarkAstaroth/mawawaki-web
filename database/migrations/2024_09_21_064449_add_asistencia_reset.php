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
        Schema::table('asistencias', function (Blueprint $table) {
            // Agregar los nuevos campos
            $table->string('tipo_asignacion')->nullable()->default(null);
            $table->date('fecha_asignacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            // Eliminar los campos si se revierte la migración
            $table->dropColumn('tipo_asignacion');
            $table->dropColumn('fecha_asignacion');
        });
    }
};
