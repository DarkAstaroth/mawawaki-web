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
        Schema::create('sesiones', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('servicio_id')->constrained('servicios');
            $table->foreignUuid('responsable')->nullable()->constrained('personal');
            $table->foreignUuid('terapeuta')->nullable()->constrained('personal');
            $table->foreignUuid('coterapeuta_id')->nullable()->constrained('personal');
            $table->foreignUuid('apoyo_id')->nullable()->constrained('personal');
            $table->foreignUuid('caballo_id')->nullable()->constrained('caballos');
            $table->bigInteger('fecha_sesion')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado_sesion', ['En Progreso', 'Completado', 'Cancelado', 'Pendiente'])->default('Pendiente');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones');
    }
};
