<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    use HasUuids;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nombre', 'paterno', 'materno', 'fecha_nacimiento', 'genero', 'telefono', 'direccion', 'email', 'ci'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'persona_id');
    }
}
