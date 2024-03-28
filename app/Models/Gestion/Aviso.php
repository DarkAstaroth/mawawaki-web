<?php

namespace App\Models\Gestion;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aviso extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public $table = 'avisos';

    protected $fillable = [
        'usuario_id',
        'titulo',
        'descripcion',
        'url',
        'imagen',
        'recursos',
        'tipo',
        'roles',
        'estado',
        'global',
        'main',
        'fecha_publicacion',
        'fecha_vencimiento',
    ];

    protected $casts = [
        'roles' => 'array',
        'recursos' => 'array',
        'estado' => 'boolean',
        'global' => 'boolean',
        'main' => 'boolean',
        'fecha_publicacion' => 'datetime',
        'fecha_vencimiento' => 'datetime',
    ];
}
