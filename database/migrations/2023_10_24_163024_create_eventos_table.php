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
        Schema::create('eventos', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('imagen_evento', 2048)->nullable();
            $table->string('nombre');
            $table->bigInteger('fecha_hora_inicio')->nullable();
            $table->bigInteger('fecha_hora_fin')->nullable();
            $table->enum('tipo', ['publico', 'privado']);
            $table->string('lugar');
            $table->string('latitud');
            $table->string('longitud');
            $table->string('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
