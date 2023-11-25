<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notificacion extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $table = 'notificaciones';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'usuario_id',
        'mensaje',
        'leido',
        'tipo',
        'fecha_unix',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
