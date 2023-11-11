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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('persona_id')->nullable()->unique()->index();
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('nombres')->nullable();
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->boolean('estado')->default(0);
            $table->boolean('verificada')->default(0);
            $table->boolean('solicitud')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable()->default('/assets/imagenes/user-default.jpg');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
