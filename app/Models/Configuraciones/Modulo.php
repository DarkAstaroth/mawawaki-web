<?php

namespace App\Models\Configuraciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Modulo extends Model
{
    use HasFactory;
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
