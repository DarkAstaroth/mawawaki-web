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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('UsuarioID');
            $table->uuid('EventoID');
            $table->bigInteger('fecha_hora_entrada');
            $table->bigInteger('fecha_hora_salida')->nullable();
            $table->boolean('global');
            $table->string('CodigoQR')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Agregar claves foráneas a eventos y usuarios (ajusta según tus necesidades)
        Schema::table('asistencias', function (Blueprint $table) {
            $table->foreign('EventoID')->references('id')->on('eventos');
            $table->foreign('UsuarioID')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
