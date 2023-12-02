<?php

namespace App\Models\Gestion;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $table = 'servicios';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'paciente_id',
        'tipo_servicio',
        'observaciones',
        'fecha_ingreso',
        'fecha_final',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    // Relación: Un servicio pertenece a un cliente.
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación: Un servicio pertenece a un paciente.
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    // Relación: Un servicio puede tener muchas sesiones.
    public function sesiones()
    {
        return $this->hasMany(Sesion::class);
    }
}
