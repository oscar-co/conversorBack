<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patron_punto extends Model
{
    protected $table = 'patron_puntos';

    public function patrones(){
		return $this->belongsTo('App\Patron');
	}

	/*public function tmp_patron_puntos(){
		return $this->hasMany('App\TmpPuntosPatron');	
	}*/
	
}