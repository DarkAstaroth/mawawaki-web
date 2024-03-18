<?php

namespace App\Models\Gestion;

use App\Models\User;
use App\Models\Gestion\TipoDocumento;
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

    protected $table = 'documentos';

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
        'tipo_documento_id'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class);
    }
}
