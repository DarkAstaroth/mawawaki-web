<?php

namespace App\Models\Configuraciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;
    protected $table = "modulos";
    protected $fillable = ['nombre', 'descripcion'];
}
