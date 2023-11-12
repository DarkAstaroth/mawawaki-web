<?php

namespace App\Models\Gestion;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiagnosticoPaciente extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    // Relación: Un diagnóstico-paciente pertenece a un cliente.
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación: Un diagnóstico-paciente pertenece a un paciente.
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    // Relación: Un diagnóstico-paciente pertenece a un diagnóstico.
    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }
}
