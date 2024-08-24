<?php

namespace App\Models\Gestion;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $table = 'pagos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'servicio_id',
        'monto',
        'tipo_pago',
        'comprobante',
        'verificado',
        'fecha_pago',
        'id_transaccion',
        'estado',
        'razon_social',
        'nit',
        'facturado',
        'notas'
    ];

    // Relación: Un pago pertenece a una sesión.
    public function sesion()
    {
        return $this->belongsTo(Servicio::class);
    }
}
