<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jugada extends Model
{
    protected $fillable = [
        'partida_id',
        'codigo_colores_propuesto',
        'posiciones_correctas',
        'colores_correctos'
    ];

    public function partida()
    {
        return $this->belongsTo(Partida::class);
    }
}
