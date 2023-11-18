<?php

namespace Database\Seeders;

use App\Models\Gestion\TipoDocumento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoDocumento::create([
            'nombre' => 'Carnet',
            'descripcion' => 'Descripción del Carnet',
            'estado' => 1,
        ]);

        TipoDocumento::create([
            'nombre' => 'Matrícula',
            'descripcion' => 'Descripción de la Matrícula',
            'estado' => 1,
        ]);

        TipoDocumento::create([
            'nombre' => 'Consentimiento',
            'descripcion' => 'Descripción del Consentimiento',
            'estado' => 1,
        ]);
    }
}
