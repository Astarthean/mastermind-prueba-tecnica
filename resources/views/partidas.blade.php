<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mastermind</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body>
    <div id="app" class="max-w-4xl mx-auto p-6">
        <h1 class="text-4xl font-bold text-center text-blue-600 mb-8">
            Mastermind
        </h1>

        <div class="text-center mb-6">
            <input id="nombrePartida" type="text" placeholder="Nombre (opcional)" class="border p-2 rounded-md" />
            <button id="crearPartida" class="bg-blue-500 text-white px-6 py-2 rounded-lg shadow-lg hover:bg-blue-600">
                Nueva Partida
            </button>
        </div>

        <ul id="lista_partidas" class="space-y-4">

        </ul>

        <p id="mensajeNoPartidas" class="text-center text-lg text-gray-500 hidden">
            No hay partidas en curso.
        </p>
    </div>

    @vite(['resources/js/partidas.js'])
</body>

</html>
