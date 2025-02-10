<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jugadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partida_id')->constrained()->onDelete('cascade');
            $table->json('codigo_colores_propuesto');
            $table->integer('posiciones_correctas');
            $table->integer('colores_correctos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jugadas');
    }
};
