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
        Schema::create('servicios', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('paciente_id')->nullable()->constrained('pacientes');
            $table->enum('tipo_servicio', ['EQUITACION', 'EQUINOTERAPIA'])->default('EQUINOTERAPIA');
            $table->text('observaciones')->nullable();
            $table->bigInteger('fecha_ingreso')->nullable();
            $table->bigInteger('fecha_final')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
