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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('mensaje');
            $table->boolean('leido')->default(false);
            $table->enum('tipo', ['info', 'warning', 'success', 'error'])->default('info');
            $table->bigInteger('fecha_unix')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
