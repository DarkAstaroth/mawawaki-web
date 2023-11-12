<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalUniversidad extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;


    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    // Relación muchos a muchos con las tablas Personal y Universidad
    public function personal()
    {
        return $this->belongsToMany(Personal::class, 'personal_universidad', 'personal_id', 'universidad_id')
            ->withPivot('Matricula')
            ->withTimestamps();
    }

    public function universidad()
    {
        return $this->belongsToMany(Universidad::class, 'personal_universidad', 'universidad_id', 'personal_id')
            ->withPivot('Matricula')
            ->withTimestamps();
    }
}
