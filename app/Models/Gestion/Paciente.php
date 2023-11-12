<?php

namespace App\Models\Gestion;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;


    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    // Relación: Un paciente pertenece a un cliente.
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación: Un paciente puede tener muchos servicios.
    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    // Relación: Un paciente puede tener muchos diagnósticos a través de la tabla pivote 'diagnostico_paciente'.
    public function diagnosticos()
    {
        return $this->belongsToMany(Diagnostico::class, 'diagnostico_paciente');
    }
}
