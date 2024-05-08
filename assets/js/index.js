import axios from "axios";

window.onload = function() {
    const buscarPartidaButton = document.getElementById('buscarPartidaButton');
    const cancelarColaButton = document.getElementById('cancelarColaButton');
    const spinner = document.getElementById('spinner');
    let MAX_RETRIES = 0;
    buscarPartidaButton.addEventListener('click', async () => {
        spinner.style.display = 'block'; // Show spinner and text

        // Update buttons
        buscarPartidaButton.classList.add('d-none');
        cancelarColaButton.classList.remove('d-none');
        longPolling();

        try {
            const response = await fetch('/api/buscarpartida');
            if (!response.ok) {
                throw new Error(`Error al buscar partida: ${response.statusText}`);
            } else {
                // Player added to queue, but no match found yet
                console.log('Jugador agregado a la cola, esperando partida...');
            }
        } catch (error) {
            console.error('Error:', error.message);
        }
    });

    async function longPolling(retryCount = 0) {
        try {
            const eventSource = new EventSource('/long-polling');

            eventSource.onmessage = (event) => {
                const data = JSON.parse(event.data);
                const urlPartida = data.urlPartida;

                if (urlPartida) {
                    window.location.href = urlPartida;
                }
            };

            eventSource.onerror = (error) => {
                console.error("Error:", error);

                if (retryCount < MAX_RETRIES) {
                    const waitTime = calculateWaitTime(retryCount);
                    console.log(`Reintentando en ${waitTime} segundos (intento ${retryCount + 1})`);
                    setTimeout(longPolling, waitTime * 1000, retryCount + 1);
                } else {
                    console.error("Error: Se alcanzó el límite de reintentos (5 reintentos)");
                    // Maneja la terminación después de 5 reintentos (por ejemplo, muestra un mensaje de error al usuario)
                    eventSource.close();
                    // Implementar la lógica de terminación aquí
                }
            };
        } catch (error) {
            console.error("Error:", error);
        }
    }

    cancelarColaButton.addEventListener('click', async () => {
        spinner.style.display = 'block'; // Show spinner while canceling

        try {
            const response = await fetch('/api/cancelarcola');
            if (!response.ok) {
                throw new Error(`Error al cancelar cola: ${response.statusText}`);
            } else {
                // Cola successfully canceled
                console.log('Cola cancelada');

                // Update buttons and spinner
                spinner.style.display = 'none';
                cancelarColaButton.classList.add('d-none');
                buscarPartidaButton.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Error:', error.message);
        }
    });
    const rankingContainer = document.getElementById('container-ranking');

    axios.get('/api/ranking')
        .then(response =>
        {
            const rankingData = response.data;

            // Process and display the ranking data
            rankingContainer.innerHTML = rankingData.map(user => {
                return `
                <div class="ranking-item">
                    <span class="username">${user.name}</span>
                    <span class="puntuacion">${user.puntuacion}</span>
                </div>
            `;
            }).join('');
        })

        .catch(error => {
            console.error('Error fetching ranking:', error);
        });
};