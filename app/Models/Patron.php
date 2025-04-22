<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PatronPunto;

class Patron extends Model
{
    protected $table = 'patrones';

    protected $fillable = ['ptn', 'magnitud', 'valor_minimo', 'valor_maximo'];

    public function patronPuntos()
    {
        return $this->hasMany(PatronPunto::class, 'ptn_id');
    }
}