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
        Schema::create('caballos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('foto', 2048)->nullable()->default('/assets/imagenes/user-default.jpg');
            $table->string('nombre');
            $table->string('apodo')->nullable();
            $table->string('raza');
            $table->string('color_pelaje');
            $table->date('fecha_nacimiento');
            $table->enum('genero', ['macho', 'hembra', 'castrado']);
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caballos');
    }
};
