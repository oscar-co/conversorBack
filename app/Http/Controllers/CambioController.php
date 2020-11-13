<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cambio;

class CambioController extends Controller
{
    /** 
     * En esta funcion se reciben los datos enviados desde el Front en los cuales 
     * vienen la unidad de entrada, unidad de salida y valor de entrada, con esos
     * datos llamamos a la función calcularCambio pasandole los datos descifrados ya
     */ 
    public function cambio(Request $request){

        $json = $request->input('json', null);
        
    	$params = json_decode($json);
    	$params_array = json_decode($json, true);
        Log::emergency('Objeto recibido: '.$json);

        $c = self::calcularCambio($params_array);
        
        $data = array(
            'code'	=>	'200',
            'status'	=>	'success',
            'resultado'	=>	$c
        );

        return response()->json($data, $data['code']);
    }

    /**
     * En la siguiente función se reciben los datos de unidades y valor de entrada 
     * y si la magnitud es Temperatura envia el proceso a la función calculoTemperatura
     * y si es cualquier otra, lo envia a la función del resto de magnitudes.
     */
    public function calcularCambio($params){

        if($params['sMagnitud'] == 'temperatura'){

            $c = self::calculoTemperatura($params);
        }
        else{

            $c = self::calculoMagnitudes($params);
        }

        return $c;
    }

    /**
     * En esta función se calcula la converssión de temperatura, al tener que aplicar
     * diferentes tipos de fórmulas para cada unidad, no se ha podido integrar con el resto
     * de unidades en las cuales basta con consultar el factor de cambio guardado en BD.
     */
    public function calculoTemperatura($params){

        $uniEntrada = $params['uEntrada'];
        $uniSalida = $params['uSalida'];
        $valEntrada = $params['vEntrada'];

        if($uniEntrada == 'ºC'){

            if($uniSalida == 'ºF'){

                return ((($valEntrada * 9) / 5) + 32);
            }
            elseif($uniSalida == 'K'){

                return $valEntrada + 273.15;
            }
            else{
                return $valEntrada;
            }
        }
        elseif($uniEntrada == 'ºF'){

            if($uniSalida == 'ºC'){

                return (($valEntrada - 32) * 5 / 9);
            }
            elseif($uniSalida == 'K'){

                return ((($valEntrada - 32) * 5 / 9 ) + 273.15);
            }
            else{
                return $valEntrada;
            }
        }
        elseif($uniEntrada == 'K'){

            if($uniSalida == 'ºC'){

                return ($valEntrada - 273.15);
            }
            elseif($uniSalida == 'ºF'){

                return ((($valEntrada-273.15)*9)/5)+32;
            }
            else{
                return $valEntrada;
            }
        }
    }

    /**
     * En esta función realizamos la conversión de cualquier unidad que no sea Temperatura, 
     * consultando en BD el factor multiplicador para cada caso en función de la unidad de 
     * entrada y la de salida. Con ese factor obtenido hacemos la conversión y devolvemos
     * el resultado.
     */
    public function calculoMagnitudes($params){

        $uniEntrada = $params['uEntrada'];
        $uniSalida = $params['uSalida'];
        $valEntrada = $params['vEntrada'];

        $result = Cambio::where('u_entrada', $uniEntrada)
                                ->where('u_salida', $uniSalida)
                                ->first('factor');

        $valSalida = $valEntrada * $result['factor'];

        Log::emergency("Valor de entrada1: ". $valSalida);
        Log::emergency('Valor de salida: '.$valSalida);
        Log::emergency('Valor de factor: '.$result);

        return $valSalida;
    }

}
