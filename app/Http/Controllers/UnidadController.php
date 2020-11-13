<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Unidad;
use App\Models\Cambio;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use DB;

class UnidadController extends Controller
{
	/**  En la siguiente funcion se recibe la magnitud seleccionada y con ella consulto a la BD
	* para obtener las unidades correspondientes a dicha magnitud y devolverlo a la web mediante Json
	* junto con el estado. Y si no hay unidades para dicha magnitud devuelve un error 404 
	*/
    public function index($magnitud){

		$unidades = Unidad::where('magnitud', $magnitud)->get();
		//var_dump(count($unidades));

		if(count($unidades) > 0){

			$data = array(
    			'code'	=>	'200',
	    		'status'	=>	'success',
	    		'unidades'	=>	$unidades
    		);
		}else{
			
			$data = array(
    			'code'	=>	'404',
	    		'status'	=>	'error',
	    		'error'	=>	'No existen unidades para '.$magnitud
    		);
		}
    		
    	
    	return response()->json($data, $data['code']);
    }
}
