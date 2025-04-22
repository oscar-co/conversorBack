<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cambio extends Model
{
    protected $table = 'cambios';

    protected $fillable = ['u_entrada', 'u_salida', 'factor'];
}
