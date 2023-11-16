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
        Schema::create('diagnostico_paciente', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            // Definir las claves foráneas
            $table->foreignUuid('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignUuid('diagnostico_id')->constrained('diagnosticos')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostico_paciente');
    }
};
