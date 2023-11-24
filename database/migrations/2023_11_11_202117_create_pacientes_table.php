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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('persona_id')->constrained('personas')->nullable();
            $table->foreignUuid('cliente_id')->constrained('clientes')->nullable();
            $table->boolean('verificado')->default(false);
            $table->enum('tipo_paciente', ['interno', 'externo'])->default('externo');
            $table->timestamp('fecha_ingreso')->nullable();
            $table->string('estado_salud')->nullable();
            $table->boolean('estado')->default(false);
            $table->string('precondicion')->nullable();
            $table->string('contacto_emergencia_nombre')->nullable();
            $table->string('contacto_emergencia_telefono')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
