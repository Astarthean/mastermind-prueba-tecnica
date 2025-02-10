<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mastermind - Tablero</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body class="bg-gray-100">
    <div id="app" class="max-w-4xl mx-auto p-6">
        <h1 id="titulo" class="text-3xl font-bold text-center text-blue-700 mb-6 drop-shadow-md">
            Mastermind - Tablero
        </h1>
        <h2 class="text-2xl font-bold text-center text-blue-700 mb-6 drop-shadow-md">
            <a href="{{ route('partidas') }}">
                Volver a las partidas
            </a>
        </h2>


        <div class="grid grid-cols-2 gap-6 bg-white shadow-lg rounded-lg border border-gray-300 p-6">
            {{-- Tablero de intentos --}}
            <div id="tablero" class="space-y-2">
                @for ($i = 0; $i < 10; $i++)
                    <div class="flex justify-center items-center p-2">
                        {{-- Intentos --}}
                        <div class="intento flex space-x-2 p-2 border border-gray-300 rounded-lg bg-white">
                            @for ($j = 0; $j < 4; $j++)
                                <div class="bolita w-8 h-8 rounded-full bg-gray-300 border border-gray-500"
                                    data-index="{{ $j }}" data-intento="{{ $i }}">
                                </div>
                            @endfor

                            {{-- Respuestas de cada intento --}}
                            <div
                                class="contenedor_respuestas grid grid-cols-4 gap-1 ml-3 bg-gray-200 p-1 rounded-lg shadow-inner">
                                @for ($j = 0; $j < 4; $j++)
                                    <div class="respuesta w-3 h-3 rounded-full border border-gray-600">
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            {{-- FIN Tablero de intentos --}}


            <div class="flex flex-col justify-center items-center h-full">
                {{-- Información de la partida --}}
                <div class="w-full p-4 bg-gray-100 rounded-lg shadow-md text-center">
                    <h2 class="text-lg font-semibold text-gray-700">
                        Información
                    </h2>
                    <p class="text-sm text-gray-600 mt-2">
                        Nombre:
                        <span id="nombreJugador" class="font-bold"></span>
                    </p>
                    <p class="text-sm text-gray-600 mt-2">
                        Estado:
                        <span id="estadoPartida" class="font-bold"></span>
                        <span id="estadoEtiqueta" class="inline-block ml-2 px-3 py-1 text-white text-sm rounded"></span>
                    </p>
                    <p class="text-sm text-gray-600 mt-2" id="infoIntentos">
                        Intentos restantes:
                        <span id="intentosRestantes" class="font-bold"></span>
                    </p>

                    {{-- JUGADA --}}
                    <div id="contenedorJugada">
                        <h2 class="mt-4 font-semibold text-gray-600">Jugada</h2>
                        <div class="jugada flex justify-center items-center gap-2 mt-2">
                            @for ($j = 0; $j < 4; $j++)
                                <div class="bolitaJugada w-8 h-8 rounded-full border border-gray-500"
                                    data-index="{{ $j }}" data-intento="{{ $i }}">
                                </div>
                            @endfor
                        </div>
                    </div>
                    {{-- FIN - JUGADA --}}
                </div>
                {{-- FIN Información de la partida --}}


                {{-- Formulario de selección de colores --}}
                <div id="formularioIntento" class="w-full mt-4 p-4 bg-gray-50 rounded-lg shadow-md text-center">
                    <h2 class="text-md font-semibold mb-3 text-gray-700">Selecciona un color</h2>

                    {{-- Colores --}}
                    <div class="flex justify-center space-x-2">
                        @php
                            $colores = ['rojo', 'verde', 'azul', 'violeta', 'naranja', 'negro'];

                            $coloresMapeados = [
                                'rojo' => 'red-500',
                                'verde' => 'green-500',
                                'azul' => 'blue-500',
                                'violeta' => 'purple-500',
                                'naranja' => 'orange-500',
                                'negro' => 'gray-900',
                            ];
                        @endphp
                        @foreach ($colores as $color)
                            <div class="color-seleccion w-8 h-8 rounded-full bg-{{ $coloresMapeados[$color] }} cursor-pointer border-2 border-transparent hover:border-gray-600 hover:scale-110 transition"
                                data-color="{{ $color }}"></div>
                        @endforeach
                    </div>

                    <button id="enviarIntento"
                        class="mt-4 w-full bg-blue-600 text-white font-bold py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                        Enviar Intento
                    </button>
                    <button id="resetJugada"
                        class="mt-4 w-full bg-gray-500 text-white font-bold py-2 rounded-lg shadow-md hover:bg-gray-700 transition">
                        Reset jugada
                    </button>
                </div>
                {{-- FIN Formulario de selección de colores --}}
            </div>
        </div>
    </div>

    @vite(['resources/js/tablero.js'])
</body>

</html>
