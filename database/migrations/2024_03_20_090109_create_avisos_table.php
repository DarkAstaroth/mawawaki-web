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
        Schema::create('avisos', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('url')->nullable();
            $table->string('imagen')->nullable();
            $table->json('recursos')->nullable();;
            $table->string('tipo')->nullable();
            $table->json('roles')->nullable();;
            $table->boolean('estado')->default(true);
            $table->boolean('global')->default(false);
            $table->boolean('main')->default(false);
            $table->timestamp('fecha_publicacion')->nullable();
            $table->timestamp('fecha_vencimiento')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avisos');
    }
};
