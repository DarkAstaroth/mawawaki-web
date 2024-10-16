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

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    // Relación: Un caballo puede estar asociado a muchas sesiones.
    public function sesiones()
    {
        return $this->hasMany(Sesion::class);
    }


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
