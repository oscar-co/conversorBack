<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Patron;
use App\Models\Patron_punto;
use App\Http\Controllers\CambioController;

class PatronController extends Controller
{
    /**
     * Cálculo principal: convierte el valor de entrada y busca los patrones disponibles.
     */
    public function calculoMain(Request $request)
    {
        $params_array = $this->decodeJson($request);

        if (!$params_array) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parámetros inválidos o mal formateados.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $valorConvertido = $this->tipoMagnitud($params_array);
        $patrones = $this->buscarPatrones($valorConvertido, $params_array['sMagnitudPat']);

        return response()->json([
            'status' => 'success',
            'patrones' => $patrones
        ]);
    }

    /**
     * Cálculo de la incertidumbre asociada a un patrón.
     */
    public function calculoIncertidumbre(Request $request)
    {
        $params_array = $this->decodeJson($request);

        if (
            !$params_array ||
            empty($params_array['patronPat']) ||
            empty($params_array['sMagnitudPat']) ||
            empty($params_array['uEntradaPat']) ||
            !isset($params_array['vEntradaPat'])
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Faltan parámetros necesarios para calcular la incertidumbre.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $valor = $this->tipoMagnitud($params_array);
        $patronNombre = $params_array['patronPat'];

        $patronId = Patron::where('ptn', $patronNombre)->value('id');

        if (!$patronId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patrón no encontrado.'
            ], Response::HTTP_NOT_FOUND);
        }

        $incertidumbre = Patron_punto::where('ptn_id', $patronId)
            ->where('lectura_patron', '>', $valor)
            ->orderBy('lectura_patron')
            ->value('incertidumbre');

        return response()->json([
            'status' => 'success',
            'valor' => $incertidumbre
        ]);
    }

    /**
     * Convierte el valor de entrada a una unidad común según la magnitud.
     */
    private function tipoMagnitud(array $params_array)
    {
        $magnitud = $params_array['sMagnitudPat'];
        $uniEntrada = $params_array['uEntradaPat'];
        $valEntrada = $params_array['vEntradaPat'];

        $cambio = new CambioController;

        $convertir = [
            'uEntrada' => $uniEntrada,
            'vEntrada' => $valEntrada
        ];

        switch ($magnitud) {
            case 'temperatura':
                $convertir['uSalida'] = 'ºC';
                return $cambio->calculoTemperatura($convertir);

            case 'presion':
                $convertir['uSalida'] = 'mbar';
                return $cambio->calculoMagnitudes($convertir);

            case 'masa':
                $convertir['uSalida'] = 'g';
                return $cambio->calculoMagnitudes($convertir);

            default:
                return $valEntrada;
        }
    }

    /**
     * Busca los patrones que aplican a una magnitud y un valor determinado.
     */
    private function buscarPatrones($valor, string $magnitud): array
    {
        return Patron::where('magnitud', $magnitud)
            ->where('valor_minimo', '<', $valor)
            ->where('valor_maximo', '>', $valor)
            ->pluck('ptn')
            ->toArray();
    }

    /**
     * Decodifica el JSON del request.
     */
    private function decodeJson(Request $request): ?array
    {
        $json = $request->input('json', null);

        if (!$json) return null;

        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : null;
    }
}
