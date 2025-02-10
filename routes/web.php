<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('partidas');
})->name('partidas');

Route::get('/tablero/{id}', function ($id) {
    return view('tablero', ['partidaId' => $id]);
})->name('tablero');
