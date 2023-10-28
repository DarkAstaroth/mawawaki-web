<?php

namespace App\Models\Gestion;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caballo extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'foto',
        'nombre',
        'raza',
        'color_pelaje',
        'fecha_nacimiento',
        'genero',
        'notas_comentarios',
    ];
}
