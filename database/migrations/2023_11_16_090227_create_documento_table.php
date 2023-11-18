<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipo_documento', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('documento', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users');
            $table->string('nombre_archivo')->nullable();
            $table->string('ruta_archivo')->nullable();
            $table->boolean('completado')->default(false);
            $table->unsignedBigInteger('tamano_archivo')->nullable();
            $table->dateTime('fecha_creacion')->nullable();
            $table->dateTime('fecha_modificacion')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('formato_archivo')->nullable();
            $table->unsignedBigInteger('usuario_cargador_id')->nullable();
            $table->dateTime('fecha_vencimiento')->nullable();
            $table->string('estado_revision')->nullable();
            $table->json('etiquetas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('documento_tipo_documento', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('UUID()'));
            $table->foreignUuid('documento_id')->constrained('documento');
            $table->foreignUuid('tipo_documento_id')->constrained('tipo_documento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_tipo_documento');

        Schema::table('documento', function (Blueprint $table) {
            $table->dropForeign(['tipo_id']);
        });

        Schema::dropIfExists('documento');
        Schema::dropIfExists('tipo_documento');
    }
};
