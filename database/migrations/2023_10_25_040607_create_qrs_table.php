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
        Schema::create('qrs', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('EventoID');
            $table->bigInteger('fecha_vencimiento')->nullable();
            $table->Integer('cantidad_usos')->default(-1);
            $table->Integer('cuota')->default(0)->nullable();
            $table->uuid('CodigoQR')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        // Agregar clave foránea a eventos
        Schema::table('qrs', function (Blueprint $table) {
            $table->foreign('EventoID')->references('id')->on('eventos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qrs');
    }
};
