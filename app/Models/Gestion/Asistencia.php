<?php

namespace App\Models\Gestion;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asistencia extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['UsuarioID', 'EventoID', 'fecha_hora_entrada', 'fecha_hora_salida', 'global', 'CodigoQR', 'ingreso_verificado', 'salida_verificado'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'UsuarioID', 'id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'EventoID', 'id');
    }
}
