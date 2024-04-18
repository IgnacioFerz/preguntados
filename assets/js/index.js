window.onload = function() {
    const buscarPartidaButton = document.getElementById('buscarPartidaButton');
    buscarPartidaButton.addEventListener('click', buscarPartida);
}

async function buscarPartida() {
    try {
        const response = await fetch('/api/buscarpartida');
        if (!response.ok) {
            throw new Error(`Error al unirte a la cola: ${response.statusText}`);
        }

        const data = await response.json();
        const userId = data.email;

        document.getElementById('userIdDisplay').textContent = `ID de usuario: ${userId}`;
    } catch (error) {
        console.error('Error:', error.message);
    }
}