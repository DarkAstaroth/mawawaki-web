<?php

namespace App\Models\Configuraciones;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission;

class Modulo extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = "modulos";
    protected $fillable = ['nombre', 'descripcion'];

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function permisos()
    {
        return $this->hasMany(Permission::class, 'modulo_id');
    }
}
