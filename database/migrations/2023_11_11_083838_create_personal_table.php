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
        Schema::create('personal', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('UsuarioID')->constrained('users');
            $table->string('universidad')->nullable();;
            $table->string('facultad')->nullable();;
            $table->string('carrera')->nullable();;
            $table->enum('estado_estudio', ['En curso', 'Finalizado'])->nullable();
            $table->string('documentacion')->nullable();;
            $table->string('rango_nivel')->nullable();;
            $table->string('codigo_personal')->nullable();;
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal');
    }
};
