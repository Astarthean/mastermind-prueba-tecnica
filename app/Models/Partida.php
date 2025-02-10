<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $fillable = [
        'nombre',
        'codigo_colores',
        'estado',
        'jugadas',
        'intentos_restantes'
    ];

    public function jugadas()
    {
        return $this->hasMany(Jugada::class);
    }

    public static function generarCodigo()
    {
        $colores = ['rojo', 'verde', 'azul', 'violeta', 'naranja', 'negro'];
        shuffle($colores);
        $codigo_secreto = array_slice($colores, 0, 4);
        return $codigo_secreto;
    }

    public function validarColores(array $coloresPropuestos)
    {
        $coloresDisponibles = ['rojo', 'verde', 'azul', 'violeta', 'naranja', 'negro'];

        if (count($coloresPropuestos) !== 4) {
            return response()->json([
                'error' => "Se requieren 4 colores para evaluar la jugada. Colores disponibles: " . implode(', ', $coloresDisponibles)
            ], 400);
        }

        if (count(array_unique($coloresPropuestos)) < 4) {
            return response()->json([
                'error' => "No puedes seleccionar colores repetidos. Colores disponibles: " . implode(', ', $coloresDisponibles)
            ], 400);
        }

        foreach ($coloresPropuestos as $color) {
            if (!in_array($color, $coloresDisponibles)) {
                return response()->json([
                    'error' => "El color '$color' no es válido. Colores disponibles: " . implode(', ', $coloresDisponibles)
                ], 400);
            }
        }

        return null;
    }

    public function evaluarEstado($posicionesCorrectas)
    {
        if ($posicionesCorrectas === 4) {
            $this->estado = 'finalizada_victoria';
            return 'finalizada_victoria';
        }

        if ($this->intentos_restantes <= 0) {
            $this->estado = 'finalizada_derrota';
            return 'finalizada_derrota';
        }

        return 'en_juego';
    }

    public function evaluarJugada($codigoPropuesto)
    {
        if ($codigoPropuesto === null) {
            return [0, 0];
        }

        $codigoCorrecto = json_decode($this->codigo_colores);
        $posicionesCorrectas = 0;
        $coloresCorrectos = 0;

        $propuesto = $codigoPropuesto;
        $correcto = $codigoCorrecto;

        // Contar posiciones correctas (exactas)
        for ($i = 0; $i < 4; $i++) {
            if ($propuesto[$i] === $correcto[$i]) {
                $posicionesCorrectas++;
                unset($propuesto[$i]);
                unset($correcto[$i]);
            }
        }

        // Contar colores correctos (sin importar la posición)
        foreach ($propuesto as $color) {
            $key = array_search($color, $correcto);
            if ($key !== false) {
                $coloresCorrectos++;
                unset($correcto[$key]);
            }
        }

        return [$posicionesCorrectas, $coloresCorrectos];
    }
}
