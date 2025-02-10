<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jugada;
use App\Models\Partida;
use Illuminate\Http\Request;

class PartidaController extends Controller
{
    // Lista de partidas
    public function listarPartidas()
    {
        try {
            $partidasEnJuego = Partida::where('estado', 'en_juego')->get();
            $partidasFinalizadasVictoria = Partida::where('estado', 'finalizada_victoria')->get();
            $partidasFinalizadasDerrota = Partida::where('estado', 'finalizada_derrota')->get();

            if ($partidasEnJuego->isEmpty() && $partidasFinalizadasVictoria->isEmpty() && $partidasFinalizadasDerrota->isEmpty()) {
                return response()->json([
                    'mensaje' => 'No hay partidas que mostrar.'
                ], 400);
            }

            return response()->json([
                'en_juego' => $partidasEnJuego,
                'finalizadas_victoria' => $partidasFinalizadasVictoria,
                'finalizadas_derrota' => $partidasFinalizadasDerrota
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Hubo un error al obtener las partidas.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Consultar datos de una partida
    public function obtenerPartida($id)
    {
        try {
            $partida = Partida::with('jugadas')->find($id);

            if (!$partida) {
                return response()->json(['error' => 'Partida no encontrada.'], 404);
            }

            return response()->json([
                'id' => $partida->id,
                'nombre' => $partida->nombre,
                'created_at' => $partida->created_at,
                'updated_at' => $partida->updated_at,
                'codigo_colores' => json_decode($partida->codigo_colores),
                'estado' => $partida->estado,
                'intentos_restantes' => $partida->intentos_restantes,
                'jugadas' => $partida->jugadas->map(function ($jugada) {
                    $jugada->codigo_colores_propuesto = json_decode($jugada->codigo_colores_propuesto);
                    return $jugada;
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Hubo un error al obtener los datos de la partida.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Creacion nueva partida
    public function crearPartida(Request $request)
    {
        try {
            $nombre = $request->input('nombre');
            $codigoSecreto = Partida::generarCodigo();

            $partida = Partida::create([
                'nombre' => $nombre ?? 'Jugador',
                'codigo_colores' => json_encode($codigoSecreto),
                'estado' => 'en_juego',
                'intentos_restantes' => 10
            ]);

            $partida->makeHidden('codigo_colores');
            return response()->json($partida);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Hubo un error al crear la partida.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Envio jugada
    public function envioJugada(Request $request, $id)
    {
        try {
            $partida = Partida::find($id);

            if ($partida->estado !== 'en_juego') {
                return response()->json(['error' => 'La partida ha finalizado.'], 400);
            }

            $codigoPropuesto = $request->input('codigo_colores_propuesto', []);
            if (empty($codigoPropuesto)) {
                return response()->json(['error' => 'El código de colores propuesto es obligatorio para jugar.'], 400);
            }
            $validar = $partida->validarColores($codigoPropuesto);
            if ($validar) return $validar;

            $evaluacion = $partida->evaluarJugada($codigoPropuesto);
            $posicionesCorrectas = $evaluacion[0];
            $coloresCorrectos = $evaluacion[1];

            $partida->intentos_restantes--;

            $jugada = new Jugada([
                'partida_id' => $partida->id,
                'codigo_colores_propuesto' => json_encode($codigoPropuesto),
                'posiciones_correctas' => $posicionesCorrectas,
                'colores_correctos' => $coloresCorrectos
            ]);
            $jugada->save();

            $estado = $partida->evaluarEstado($posicionesCorrectas);

            $partida->estado = $estado;
            $partida->save();

            if ($estado === 'finalizada_derrota') {
                return response()->json([
                    'mensaje' => 'Has perdido la partida :(',
                    'estado' => $estado
                ]);
            }

            if ($estado === 'finalizada_victoria') {
                return response()->json([
                    'mensaje' => 'Has ganado la partida! :)',
                    'estado' => $estado
                ]);
            }

            return response()->json([
                'posiciones_correctas' => $posicionesCorrectas,
                'colores_correctos' => $coloresCorrectos,
                'intentos_restantes' => $partida->intentos_restantes,
                'estado' => $estado
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Hubo un error al enviar la jugada.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
