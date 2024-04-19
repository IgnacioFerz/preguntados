window.onload = function() {
    const buscarPartidaButton = document.getElementById('buscarPartidaButton');
    const cancelarColaButton = document.getElementById('cancelarColaButton');
    const spinner = document.getElementById('spinner');

    buscarPartidaButton.addEventListener('click', async () => {
        spinner.style.display = 'block'; // Show spinner and text

        // Update buttons
        buscarPartidaButton.classList.add('d-none');
        cancelarColaButton.classList.remove('d-none');

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
};