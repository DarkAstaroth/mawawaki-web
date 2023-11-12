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
            $table->foreignUuid('profesor')->constrained('personal');
            $table->foreignUuid('terapeuta')->constrained('personal');
            $table->foreignUuid('coterapeuta_id')->nullable()->constrained('personal');
            $table->foreignUuid('apoyo_id')->nullable()->constrained('personal');
            $table->foreignUuid('caballo_id')->nullable()->constrained('caballos');
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
