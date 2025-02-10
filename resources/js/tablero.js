import axios from 'axios';

axios.defaults.baseURL = '/';

const url = new URL(window.location.href);
const partidaId = url.pathname.split('/').pop();

const coloresMapeados = {
    'rojo': 'bg-red-500',
    'verde': 'bg-green-500',
    'azul': 'bg-blue-500',
    'violeta': 'bg-purple-500',
    'naranja': 'bg-orange-500',
    'negro': 'bg-gray-900',
};

window.addEventListener('DOMContentLoaded', () => {
    axios.get(`api/partidas/${partidaId}`)
        .then(response => {
            const partida = response.data;
            mostrarInfoPartida(partida);
            actualizarIntentos(partida.jugadas);
        })
        .catch(error => {
            console.error('Error al obtener la información de la partida:', error);
        });
});

// Mostrar INFO de la partida en la UI
const mostrarInfoPartida = (partida) => {

    const nombreJugador = document.getElementById('nombreJugador');
    nombreJugador.textContent = partida.nombre || 'Jugador Desconocido';

    // const estadoPartida = document.getElementById('estadoPartida');
    // estadoPartida.textContent = partida.estado;

    const estadoEtiqueta = document.getElementById('estadoEtiqueta');
    const formularioIntento = document.getElementById('formularioIntento');
    const parrafoIntentos = document.getElementById('infoIntentos');
    const intentosRestantes = document.getElementById('intentosRestantes');
    const contenedorJugada = document.getElementById('contenedorJugada')

    // Cambiar el color y texto de la etiqueta segun el estado
    if (partida.estado === 'finalizada_victoria') {
        estadoEtiqueta.textContent = 'Victoria';
        estadoEtiqueta.classList.add('bg-green-500');
        estadoEtiqueta.classList.remove('bg-red-500', 'bg-yellow-500');
    } else if (partida.estado === 'finalizada_derrota') {
        estadoEtiqueta.textContent = 'Derrota';
        estadoEtiqueta.classList.add('bg-red-500');
        estadoEtiqueta.classList.remove('bg-green-500', 'bg-yellow-500');
    } else if (partida.estado === 'en_juego') {
        estadoEtiqueta.textContent = 'Jugando';
        estadoEtiqueta.classList.add('bg-yellow-500');
        estadoEtiqueta.classList.remove('bg-green-500', 'bg-red-500');
    }

    if (partida.estado === 'finalizada_victoria' || partida.estado === 'finalizada_derrota') {
        formularioIntento.style.display = 'none';
        parrafoIntentos.style.display = 'none';
        contenedorJugada.style.display = 'none';
    } else {
        formularioIntento.style.display = 'block';
        parrafoIntentos.style.display = 'block';
        intentosRestantes.textContent = partida.intentos_restantes;
        contenedorJugada.style.display = 'block';

    }
};

// Mostrar intentos en la UI
const actualizarIntentos = (jugadas) => {
    // console.log(jugadas);
    const intentos = document.querySelectorAll('#tablero .intento');

    jugadas.forEach((jugada, i) => {
        // destructuring
        const { codigo_colores_propuesto, colores_correctos, posiciones_correctas } = jugada;

        const intento = intentos[i];

        const bolitas = intento.querySelectorAll('.bolita');
        codigo_colores_propuesto.forEach((color, index) => {
            bolitas[index].classList.remove('bg-gray-300');
            bolitas[index].classList.add(coloresMapeados[color]);
        });

        const resultados = intento.querySelector('.contenedor_respuestas');
        const respuestas = resultados.querySelectorAll('.respuesta');

        respuestas.forEach((respuesta) => {
            respuesta.classList.remove('bg-black', 'bg-blue-400', 'bg-gray-400');
        });

        let bolitasRespuesta = [];

        // Posiciones correctas
        for (let i = 0; i < posiciones_correctas; i++) {
            bolitasRespuesta.push('bg-black');
        }

        // Colores correctos
        for (let i = 0; i < colores_correctos; i++) {
            bolitasRespuesta.push('bg-blue-400');
        }

        // Agregar grises
        while (bolitasRespuesta.length < 4) {
            bolitasRespuesta.push('bg-gray-400');
        }

        respuestas.forEach((respuesta, index) => {
            respuesta.classList.add(bolitasRespuesta[index]);
        });
    });
};

// Array de colores a enviar
let coloresSeleccionados = [];
document.querySelectorAll('.color-seleccion').forEach(color => {
    color.addEventListener('click', () => {
        const colorSeleccionado = color.getAttribute('data-color');

        if (coloresSeleccionados.includes(colorSeleccionado)) {
            alert('Color ya seleccionado, selecciona otro.');
            return;
        }

        if (coloresSeleccionados.length < 4) {
            coloresSeleccionados.push(colorSeleccionado);
            // console.log('Colores seleccionados:', coloresSeleccionados);

            llenarCírculo(colorSeleccionado);
        }
    });
});

// Llenar circulos con color
const llenarCírculo = (color) => {
    const intentoActual = document.querySelector('.jugada:not(.completado)');

    if (intentoActual) {
        const bolitas = intentoActual.querySelectorAll('.bolitaJugada');

        for (let i = 0; i < bolitas.length; i++) {
            if (!bolitas[i].classList.contains('color-aplicado')) {
                limpiarColoresPrevios(bolitas[i]);
                bolitas[i].classList.add(coloresMapeados[color], 'color-aplicado');
                break;
            }
        }
    }
};

// Limpiar colores previos
const limpiarColoresPrevios = (bolita) => {
    Object.values(coloresMapeados).forEach(colorClass => {
        bolita.classList.remove(colorClass);
    });
};

// Enviar colores
document.getElementById('enviarIntento').addEventListener('click', () => {

    if (coloresSeleccionados.length < 4) {
        alert('Debes seleccionar exactamente 4 colores antes de enviar la jugada.');
        return;
    }

    const boton = document.getElementById('enviarIntento');
    boton.disabled = true;

    // Enviar la jugada a la API
    axios.post(`/api/partidas/${partidaId}`, {
        codigo_colores_propuesto: coloresSeleccionados
    })
        .then(response => {
            // console.log('Respuesta del servidor:', response.data);
            actualizarIntentos(response.data);
            limpiarJugada();
        })
        .catch(error => {
            console.error('Error al enviar la jugada:', error);
            alert('Hubo un error al enviar la jugada. Inténtalo de nuevo.');
        })
        .finally(() => {
            boton.disabled = false;
            window.location.reload()
        });
});

// Limpiar Jugada
const limpiarJugada = () => {
    const intentoActual = document.querySelector('.jugada:not(.completado)');

    if (intentoActual) {
        const bolitas = intentoActual.querySelectorAll('.bolitaJugada');

        bolitas.forEach(bolita => {
            limpiarColoresPrevios(bolita);
            bolita.classList.remove('color-aplicado');
        });
    }

    coloresSeleccionados = [];
    // console.log('Jugada reseteada.');
};

// Limpiar jugada con botón reset
const botonReset = document.getElementById('resetJugada');
botonReset.addEventListener('click', limpiarJugada);
