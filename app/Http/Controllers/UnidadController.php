<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Unidad;
use Illuminate\Support\Facades\Log;

class UnidadController extends Controller
{
    /**
     * Devuelve las unidades correspondientes a una magnitud específica.
     *
     * @param string $magnitud
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($magnitud)
    {
        if (!$magnitud || !is_string($magnitud)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Magnitud no válida.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $unidades = Unidad::where('magnitud', $magnitud)->get();

        if ($unidades->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'magnitud' => $magnitud,
                'unidades' => $unidades
            ], Response::HTTP_OK);
        }

        Log::info("No se encontraron unidades para la magnitud: $magnitud");

        return response()->json([
            'status' => 'error',
            'message' => "No se encontraron unidades para la magnitud '{$magnitud}'"
        ], Response::HTTP_NOT_FOUND);
    }
}
