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
        Schema::create('personal_universidad', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('personal_id')->constrained('personal');
            $table->foreignUuid('universidad_id')->constrained('universidades');
            $table->string('matricula');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_universidad');
    }
};
