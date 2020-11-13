<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cambio;
use App\Models\Patron;
use App\Models\Patron_punto;

class PatronController extends Controller
{
    public function calculoMain(Request $request){

        $json = $request->input('json', null);
        
    	$params = json_decode($json);
        $params_array = json_decode($json, true);

        /* Llamo a la función tipoMagnitud que me devuelve el valor (universal de la app) 
        para cada magnitud */
        $c = self::tipoMagnitud($params_array);

        /**Llamo a la funcion de buscar los patrones que coinciden con la magnitud y el 
         * valor introducido para convertir
         */
        $p = self::buscarPatrones($c, $params_array['sMagnitudPat']);
 
        return $p;


        //Log::emergency('Objeto recibido: '.$json);
    }

    public function buscarPatrones($valor, $magnitud){

        /*echo $valor;
        echo $magnitud;*/

        $p = Patron::where('magnitud', $magnitud)
                        ->where('valor_minimo', '<', $valor)
                        ->where('valor_maximo', '>', $valor)
                        ->pluck('ptn')->toArray();

        return $p;


    }

    /**
     * En esta función recojo los valores introducidos y llamo a las funciones de 
     * conversion del CambioController pasandole la unidad de entrada y el valor de
     * entrada y poniendo en cada Case el valor de salida (universal de la app) para
     * luego buscar patrones, etc
     */
    public function tipoMagnitud($params_array){

        $magnitud = $params_array['sMagnitudPat'];
        $uniEntrada = $params_array['uEntradaPat'];
        $valEntrada = $params_array['vEntradaPat'];

        $cC = new CambioController;

        switch($magnitud){

            case 'temperatura':

                $convertir = array(
                    "uEntrada" => $uniEntrada,
                    "uSalida" => "ºC",
                    "vEntrada"   => $valEntrada
                );

                $c = $cC->calculoTemperatura($convertir);
            break;

            case 'presion':

                $convertir = array(
                    "uEntrada" => $uniEntrada,
                    "uSalida" => 'mbar',
                    "vEntrada"   => $valEntrada
                );
 
                $c = $cC->calculoMagnitudes($convertir);
            break;

            case 'masa':

                $convertir = array(
                    "uEntrada" => $uniEntrada,
                    "uSalida" => 'g',
                    "vEntrada"   => $valEntrada
                );
                $c = $cC->calculoMagnitudes($convertir);
            break;
        }
        return $c;
    }

    public function calculoIncertidumbre(Request $request){

        $json = $request->input('json', null);
        
    	$params = json_decode($json);
        $params_array = json_decode($json, true);

        /*if(is_null($params_array['lectura_patron'])){

            $data = array(
    			'code'	=>	'404',
	    		'status'	=>	'error',
	    		'error'	=>	'No hay lectura_patron'
    		);
        }*/

        $valEntrada = self::tipoMagnitud($params_array);
        
        $patron = $params_array['patronPat'];

        $ptn_id = Patron::where('ptn', $patron)->pluck('id')->toArray();
    	$i = Patron_punto::where('ptn_id', $ptn_id)
    							->where('lectura_patron', '>', $valEntrada)
    							->pluck('incertidumbre')
                                ->first();

            $data = array(
                'code'	=>	'200',
                'status'	=>	'success',
                'valor'	=>	$i
            );
            return response()->json($data, $data['code']);
        //return $i;            
    }
}
