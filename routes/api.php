<?php

use App\Http\Controllers\Api\PartidaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'mensaje' => 'API functionando correctamente!'
    ]);
});

// Lista de partidas en el juego
Route::get('/partidas', [PartidaController::class, 'listarPartidas']);

// Consulta de los datos de una partida y las jugadas realizadas
Route::get('/partidas/{id}', [PartidaController::class, 'obtenerPartida']);

// Creación de una nueva partida
Route::post('/partidas', [PartidaController::class, 'crearPartida']);

// Envío jugada
Route::post('/partidas/{id}', [PartidaController::class, 'envioJugada']);
