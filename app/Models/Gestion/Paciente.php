<?php

namespace App\Models\Gestion;

use App\Models\Persona;
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

    protected $fillable = [
        'persona_id',
        'cliente_id',
        'tipo_paciente',
        'fecha_ingreso',
        'estado_salud',
        'estado',
        'verificado',
        'precondicion',
        'contacto_emergencia_nombre',
        'contacto_emergencia_telefono',
    ];

    protected $casts = [
        'estado' => 'boolean', // Si el campo 'estado' es booleano en la base de datos
    ];

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
    // Relación: Un paciente pertenece a una persona.
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
