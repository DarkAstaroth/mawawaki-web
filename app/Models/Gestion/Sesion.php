<?php

namespace App\Models\Gestion;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sesion extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;


    protected $table = 'sesiones';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    // Relación: Una sesión pertenece a un servicio.
    // public function servicio()
    // {
    //     return $this->belongsTo(Servicio::class);
    // }

    protected $fillable = [
        'servicio_id',
        'responsable',
        'terapeuta',
        'coterapeuta_id',
        'apoyo_id',
        'caballo_id',
        'fecha_sesion',
        'observaciones',
        'estado_sesion',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id', 'id');
    }

    public function responsable()
    {
        return $this->belongsTo(Personal::class, 'responsable', 'id');
    }

    public function terapeuta()
    {
        return $this->belongsTo(Personal::class, 'terapeuta', 'id');
    }

    public function coterapeuta()
    {
        return $this->belongsTo(Personal::class, 'coterapeuta_id', 'id');
    }

    public function apoyo()
    {
        return $this->belongsTo(Personal::class, 'apoyo_id', 'id');
    }

    public function caballo()
    {
        return $this->belongsTo(Caballo::class, 'caballo_id', 'id');
    }
}
