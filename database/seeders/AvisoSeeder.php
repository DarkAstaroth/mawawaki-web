<?php

namespace Database\Seeders;

use App\Models\Gestion\Aviso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AvisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Aviso::create([
            'usuario_id' => '9b69b932-17d2-4f94-a01c-04b81394b8d3',
            'titulo' => 'Módulo de Documentos habilitado',
            'descripcion' => 'Ya puedes subir documentos al sistema desde tu perfil.',
            'url' => null,
            'imagen' => '/assets/ilustraciones/archivos.svg',
            'recursos' => ['ruta/a/la/imagen.jpg'],
            'tipo' => 'success',
            'roles' => ['admin', 'user'],
            'estado' => true,
            'global' => true,
            'main' => false,
            'fecha_publicacion' => now(),
            'fecha_vencimiento' => null,
        ]);

        Aviso::create([
            'usuario_id' => '9b69b932-17d2-4f94-a01c-04b81394b8d3',
            'titulo' => 'Fotos de perfil habilitadas',
            'descripcion' => 'Ya puedes subir tu foto de perfil para tu credencial en tu perfil.',
            'url' => null,
            'imagen' => '/assets/ilustraciones/perfil.svg',
            'recursos' => ['ruta/a/la/imagen.jpg'],
            'tipo' => 'success',
            'roles' => ['admin', 'user'],
            'estado' => true,
            'global' => true,
            'main' => false,
            'fecha_publicacion' => now(),
            'fecha_vencimiento' => null,
        ]);

        Aviso::create([
            'usuario_id' => '9b69b932-17d2-4f94-a01c-04b81394b8d3',
            'titulo' => 'Código único en proceso',
            'descripcion' => 'Los perfiles que cumplan todos los requisitos obtendrán su código único y con ello su credencial :D',
            'url' => null,
            'imagen' => '/assets/ilustraciones/credencial.svg',
            'recursos' => ['ruta/a/la/imagen.jpg'],
            'tipo' => 'success',
            'roles' => ['admin', 'user'],
            'estado' => true,
            'global' => true,
            'main' => false,
            'fecha_publicacion' => now(),
            'fecha_vencimiento' => null,
        ]);

        Aviso::create([
            'usuario_id' => '9b69b932-17d2-4f94-a01c-04b81394b8d3',
            'titulo' => 'Ya se detectaron a los primeros trampos@s',
            'descripcion' => 'Ya se tiene identificado a las personas que hicieron trampa en las asistencias :(',
            'url' => null,
            'imagen' => '/assets/ilustraciones/tramposo.svg',
            'recursos' => ['ruta/a/la/imagen.jpg'],
            'tipo' => 'success',
            'roles' => ['admin', 'user'],
            'estado' => true,
            'global' => true,
            'main' => false,
            'fecha_publicacion' => now(),
            'fecha_vencimiento' => null,
        ]);
    }
}
