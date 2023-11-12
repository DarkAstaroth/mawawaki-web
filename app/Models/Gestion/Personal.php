<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personal extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;


    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    // Relación muchos a muchos
    public function universidades()
    {
        return $this->belongsToMany(Universidad::class, 'personal_universidad', 'personal_id', 'universidad_id')
            ->withPivot('Matricula')
            ->withTimestamps();
    }

    // Relación de pertenencia a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'UsuarioID', 'id');
    }
}
