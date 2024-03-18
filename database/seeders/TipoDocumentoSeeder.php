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
            'descripcion' => 'Un carnet es un documento que sirve como identificación oficial de una persona en una organización, institución o grupo particular.',
            'estado' => 1,
        ]);

        TipoDocumento::create([
            'nombre' => 'Matrícula',
            'descripcion' => 'La matrícula es un documento que certifica la inscripción de una persona en un curso, programa educativo o actividad específica.',
            'estado' => 1,
            'unico' => 1,
        ]);

        TipoDocumento::create([
            'nombre' => 'Consentimiento informado',
            'descripcion' => 'El consentimiento informado es un documento en el cual una persona acepta voluntariamente participar en una actividad o procedimiento después de haber sido informada sobre sus riesgos, beneficios y alternativas.',
            'estado' => 1,
            'unico' => 1,
        ]);

        TipoDocumento::create([
            'nombre' => 'Acta de compromiso de voluntariado',
            'descripcion' => 'Un acta de compromiso de voluntariado es un documento que formaliza el compromiso de una persona para realizar actividades de voluntariado en una organización o proyecto específico.',
            'estado' => 1,
            'unico' => 1,
        ]);

        TipoDocumento::create([
            'nombre' => 'Hoja de asistencias',
            'descripcion' => 'Una hoja de asistencias es un documento utilizado para registrar la presencia o asistencia de personas a eventos, reuniones, clases u otras actividades programadas.',
            'estado' => 1,
            'unico' => 0,
        ]);

        TipoDocumento::create([
            'nombre' => 'Hoja de actividades',
            'descripcion' => 'Una hoja de actividades es un documento utilizado para registrar las actividades realizadas dentro de un proyecto, programa o evento, incluyendo detalles como fecha, hora y descripción de la actividad.',
            'estado' => 1,
            'unico' => 0,
        ]);
    }
}
