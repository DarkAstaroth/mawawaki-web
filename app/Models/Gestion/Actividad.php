<?php

namespace App\Models\Gestion;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Actividad extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'actividades';

    protected $fillable = [
        'id_usuario',
        'titulo',
        'descripcion',
        'fecha',
        'verificada',
        'destacada',
        'ubicacion',
        'ruta_imagen',
    ];

    protected $casts = [
        'verificada' => 'boolean',
        'destacada' => 'boolean'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
