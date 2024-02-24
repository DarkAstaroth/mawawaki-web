<?php

namespace App\Models\Gestion;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'imagen_evento',
        'nombre',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'tipo',
        'lugar',
        'latitud',
        'longitud',
        'solo_ingreso',
        'principal',
        'descripcion',
        'usuarios_ids', 
    ];
    public function qrs()
    {
        return $this->hasMany(QR::class, 'EventoID', 'id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'EventoID', 'id');
    }
}
