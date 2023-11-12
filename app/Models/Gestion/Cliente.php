<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;


    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    // Relación: Un cliente puede tener muchos pacientes.
    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }

    // Relación: Un cliente puede tener muchos servicios.
    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
}
