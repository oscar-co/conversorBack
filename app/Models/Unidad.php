<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table = 'unidades';

    protected $fillable = ['nombre', 'magnitud', 'simbolo'];

    // Si algún día haces tabla de magnitudes:
    // public function magnitud() {
    //     return $this->belongsTo(Magnitud::class);
    // }
}