<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Patron;

class PatronPunto extends Model
{
    protected $table = 'patron_puntos';

    protected $fillable = ['ptn_id', 'lectura_patron', 'incertidumbre'];

    public function patron()
    {
        return $this->belongsTo(Patron::class, 'ptn_id');
    }
}