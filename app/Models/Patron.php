<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patron extends Model
{
    protected $table = 'patrones';

	public function patron_puntos(){
		return $this->hasMany('App\Patron_punto');	
	}
}
