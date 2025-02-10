import axios from 'axios';

const listaPartidas = document.getElementById('lista_partidas');
const mensajeNoPartidas = document.getElementById('mensajeNoPartidas');
const botonNuevaPartida = document.getElementById('crearPartida');
const nombrePartidaInput = document.getElementById('nombrePartida');

// Lista de partidas
axios.get('/api/partidas')
    .then(response => {
        // Limpiar la lista antes de agregar nuevas partidas
        listaPartidas.innerHTML = '';

        if (response.data.en_juego.length === 0 && response.data.finalizadas_victoria.length === 0 && response.data.finalizadas_derrota.length === 0) {
            mensajeNoPartidas.classList.remove('hidden');
        } else {
            mensajeNoPartidas.classList.add('hidden');

            function obtenerEtiquetaEstado(estado) {
                const estadoEtiqueta = document.createElement('span');
                estadoEtiqueta.classList.add('inline-block', 'ml-2', 'px-4', 'py-1', 'rounded-full', 'text-white');

                if (estado === 'finalizada_victoria') {
                    estadoEtiqueta.textContent = 'Victoria';
                    estadoEtiqueta.classList.add('bg-green-500');
                    estadoEtiqueta.classList.remove('bg-red-500', 'bg-yellow-500');
                } else if (estado === 'finalizada_derrota') {
                    estadoEtiqueta.textContent = 'Derrota';
                    estadoEtiqueta.classList.add('bg-red-500');
                    estadoEtiqueta.classList.remove('bg-green-400', 'bg-yellow-500');
                } else if (estado === 'en_juego') {
                    estadoEtiqueta.textContent = 'Jugando';
                    estadoEtiqueta.classList.add('bg-yellow-500');
                    estadoEtiqueta.classList.remove('bg-green-500', 'bg-red-500');
                }

                return estadoEtiqueta;
            }

            // merge de arrays
            const partidas = [
                ...response.data.en_juego,
                ...response.data.finalizadas_victoria,
                ...response.data.finalizadas_derrota
            ];

            partidas.forEach(partida => {
                const li = document.createElement('li');
                li.classList.add(
                    'bg-white',
                    'p-4',
                    'rounded-lg',
                    'shadow-md',
                    'flex',
                    'items-center',
                    'justify-between',
                    'hover:bg-gray-50',
                    'transition-colors'
                );

                const estadoEtiqueta = obtenerEtiquetaEstado(partida.estado);

                li.innerHTML = `
                    <span class="font-semibold">${partida.nombre || 'Jugador'}</span>
                    <div class="flex items-center gap-2">
                        ${estadoEtiqueta.outerHTML}
                        <button class="bg-blue-500 text-white px-4 py-1 rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300" data-id="${partida.id}">
                            Detalles
                        </button>
                    </div>
                `;

                listaPartidas.appendChild(li);
            });

            const botonesDetalles = document.querySelectorAll('button[data-id]');
            botonesDetalles.forEach(button => {
                button.addEventListener('click', function () {
                    const partidaId = this.getAttribute('data-id');
                    window.location.href = `/tablero/${partidaId}`;
                });
            });
        }
    })
    .catch(error => {
        if (error.response && error.response.data) {
            const mensajeError = error.response.data.mensaje;
            mensajeNoPartidas.textContent = mensajeError;
            mensajeNoPartidas.classList.remove('hidden');
        }
    });

// Crear partida
botonNuevaPartida.addEventListener('click', () => {

    const nombrePartida = nombrePartidaInput.value.trim();

    axios.post('api/partidas', { nombre: nombrePartida })
        .then(response => {
            // console.log(response);
            console.log('Partida creada!');
        })
        .catch(error => {
            // console.log(error);
            alert(error.response.data.mensaje);
        })
        .finally(() => {
            window.location.reload();
        })
});
