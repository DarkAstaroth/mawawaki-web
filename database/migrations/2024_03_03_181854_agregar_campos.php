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
        Schema::table('asistencias', function (Blueprint $table) {
            $table->boolean('ingreso_verificado')->nullable();
            $table->boolean('salida_verificado')->nullable();
        });
        DB::table('asistencias')->update(['ingreso_verificado' => 1]);
        DB::table('asistencias')->update(['salida_verificado' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            if (Schema::hasColumn('asistencias', 'ingreso_verificado')) {
                $table->dropColumn('ingreso_verificado');
            }
            if (Schema::hasColumn('asistencias', 'salida_verificado')) {
                $table->dropColumn('salida_verificado');
            }
        });
    }
};
