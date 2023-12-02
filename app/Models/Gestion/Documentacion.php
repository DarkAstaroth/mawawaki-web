<?php

namespace App\Models\Gestion;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documentacion extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'documento';

    protected $fillable = [
        'user_id',
        'nombre_archivo',
        'ruta_archivo',
        'completado',
        'tamano_archivo',
        'fecha_creacion',
        'fecha_modificacion',
        'descripcion',
        'formato_archivo',
        'usuario_cargador_id',
        'fecha_vencimiento',
        'estado_revision',
        'etiquetas',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function tiposDocumento()
    // {
    //     return $this->belongsToMany(TipoDocumento::class, 'documento_tipo_documento', 'documento_id', 'tipo_documento_id')
    //         ->withPivot(['completado', 'nombre_archivo', 'ruta_archivo', 'tamano_archivo', 'formato_archivo']);
    // }

    public function tiposDocumento()
    {
        return $this->belongsToMany(TipoDocumento::class, 'documento_tipo_documento', 'documento_id', 'tipo_documento_id');
    }
}
