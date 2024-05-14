import axios from "axios";

const buscarPartidaButton = document.getElementById('buscarPartidaButton');
    const cancelarColaButton = document.getElementById('cancelarColaButton');
    const spinner = document.getElementById('spinner');
    let isRunning = false;


    buscarPartidaButton.addEventListener('click', () => {
        spinner.style.display = 'block'; // Show spinner and text
        isRunning = true;
        // Update buttons
        buscarPartidaButton.classList.add('d-none');
        cancelarColaButton.classList.remove('d-none');

        fetch('/api/buscarpartida').then(response => {
            if (response.status !== 200) {
                throw new Error(`Error al buscar partida: ${response.statusText}`)
            }
            return true
        }).then(data => {
            console.log('Jugador agregado a la cola, esperando partida...');
            longPolling();
        }).catch(error => {
            console.error('Error:', error);
        })

    });

    function longPolling() {

        try {
            if (isRunning) {
                const eventSource = new EventSource('/long-polling');
                eventSource.onmessage = (event) => {
                    const data = JSON.parse(event.data);
                    const urlPartida = data.urlPartida;

                    if (urlPartida) {
                        window.location.href = urlPartida;
                        return
                    }

                    if (isRunning) {
                        console.log(`Reintentando conexion`);
                    } else {
                        eventSource.close()
                    }
                }


                // let interval = setInterval(() => {
                //     if (retryCount < MAX_RETRIES) {
                //
                //         if (isRunning) {
                //             retryCount++
                //             console.log(`Reintentando (intento ${retryCount})`);
                //         } else {
                //             clearInterval(interval)
                //             eventSource.close()
                //         }// adjust the timeout as needed
                //     } else {
                //         console.error("Error: Se alcanzó el límite de reintentos (5 reintentos)");
                //         isRunning = false;
                //         clearInterval(interval)
                //         eventSource?.close();
                //     }
                // }, 2000)
            }

        } catch (error) {
            console.error('longPolling', error);
        }
    }


    // function handleLongPollingError(retryCount) {
    //     if (retryCount < MAX_RETRIES) {
    //         console.log(`Reintentando (intento ${retryCount + 1})`);
    //         setTimeout(() => {
    //             longPolling(retryCount + 1);
    //         }, 1000); // adjust the timeout as needed
    //     } else {
    //         console.error("Error: Se alcanzó el límite de reintentos (5 reintentos)");
    //         isRunning = false;
    //         // Implementar la lógica de terminación aquí
    //     }
    // }

    cancelarColaButton.addEventListener('click', async () => {
        spinner.style.display = 'block'; // Show spinner while canceling

        try {
            const response = await fetch('/api/cancelarcola');
            isRunning = false
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

    const iconoCorazon = document.querySelector('.text-muted');
    iconoCorazon.addEventListener('click', async () => {
        try {
            const respuesta = await axios.post('/partidas/check-finish-starting', {
            });

            if (!respuesta.ok) {
                throw new Error(`Error al llamar al endpoint: ${respuesta.data.message}`);
            }

            console.log('Respuesta del endpoint:', respuesta.data);

        } catch (error) {
            console.error('Error al llamar al endpoint:', error);
        }
    });
