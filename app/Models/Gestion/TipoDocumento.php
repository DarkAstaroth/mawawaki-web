<?php

namespace App\Models\Gestion;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDocumento extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tipo_documento';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];


    public function documentos()
    {
        return $this->belongsToMany(Documento::class, 'documento_tipo_documento', 'tipo_documento_id', 'documento_id');
    }
}
