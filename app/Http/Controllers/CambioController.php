<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Cambio;

class CambioController extends Controller
{
    /**
     * Recibe un JSON con unidad de entrada, salida y valor, y devuelve el resultado convertido.
     */
    public function cambio(Request $request)
    {
        $params_array = $this->decodeJson($request);

        if (!$params_array) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parámetros inválidos o mal formateados.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $resultado = $this->calcularCambio($params_array);

        return response()->json([
            'status' => 'success',
            'resultado' => $resultado
        ]);
    }

    /**
     * Decide qué método usar para calcular el resultado (temperatura u otra magnitud).
     */
    public function calcularCambio(array $params)
    {
        return $params['sMagnitud'] === 'temperatura'
            ? $this->calculoTemperatura($params)
            : $this->calculoMagnitudes($params);
    }

    /**
     * Conversión de temperaturas con fórmulas específicas.
     */
    public function calculoTemperatura(array $params)
    {
        $entrada = $params['uEntrada'];
        $salida = $params['uSalida'];
        $valor = $params['vEntrada'];

        if ($entrada === 'ºC') {
            return match ($salida) {
                'ºF' => ($valor * 9 / 5) + 32,
                'K' => $valor + 273.15,
                default => $valor,
            };
        }

        if ($entrada === 'ºF') {
            return match ($salida) {
                'ºC' => ($valor - 32) * 5 / 9,
                'K' => (($valor - 32) * 5 / 9) + 273.15,
                default => $valor,
            };
        }

        if ($entrada === 'K') {
            return match ($salida) {
                'ºC' => $valor - 273.15,
                'ºF' => (($valor - 273.15) * 9 / 5) + 32,
                default => $valor,
            };
        }

        return $valor; // fallback
    }

    /**
     * Conversión de otras magnitudes usando factor de la base de datos.
     */
    public function calculoMagnitudes(array $params)
    {
        $entrada = $params['uEntrada'];
        $salida = $params['uSalida'];
        $valor = $params['vEntrada'];

        $registro = Cambio::where('u_entrada', $entrada)
            ->where('u_salida', $salida)
            ->first();

        if (!$registro) {
            Log::warning("Factor de conversión no encontrado entre $entrada y $salida");
            return null;
        }

        $factor = $registro->factor ?? 1;

        return $valor * $factor;
    }

    /**
     * Decodifica el JSON recibido en la petición.
     */
    private function decodeJson(Request $request): ?array
    {
        $json = $request->input('json', null);

        if (!$json) return null;

        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : null;
    }
}
