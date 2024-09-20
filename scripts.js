document.addEventListener('DOMContentLoaded', () => {
    // Obtener reservas
    const reservationsForm = document.getElementById('reservationsForm');
    if (reservationsForm) {
        reservationsForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(reservationsForm);
            try {
                const response = await fetch('manage_reservations.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                displayReservations(data);
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }

    // Búsqueda de vuelos
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(searchForm);
            try {
                const response = await fetch('search_flights.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                displayFlights(data);
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }

    // Obtener ubicación
    document.getElementById('getLocation').addEventListener('click', () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;

                // Usar una API para convertir lat/lon a una dirección
                fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}&localityLanguage=en`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('origin').value = data.locality || data.city || data.principalSubdivision;
                    })
                    .catch(error => {
                        console.error('Error al obtener la dirección:', error);
                    });
            }, (error) => {
                console.error('Error al obtener la ubicación:', error);
            });
        } else {
            alert('Geolocalización no es soportada por este navegador.');
        }
    });
});

function displayFlights(flights) {
    const resultsDiv = document.getElementById('results');
    if (flights.length === 0) {
        resultsDiv.innerHTML = '<p>No se encontraron vuelos.</p>';
        return;
    }
    let html = '<table><tr><th>Vuelo</th><th>Origen</th><th>Destino</th><th>Salida</th><th>Acción</th></tr>';
    flights.forEach(flight => {
        html += `<tr>
            <td>${flight.flight_number}</td>
            <td>${flight.origin}</td>
            <td>${flight.destination}</td>
            <td>${flight.departure_datetime}</td>
            <td><button onclick="reserveFlight(${flight.flight_id})">Reservar</button></td>
        </tr>`;
    });
    html += '</table>';
    resultsDiv.innerHTML = html;
}

function displayReservations(reservations) {
    const reservationsDiv = document.getElementById('reservations');
    if (reservations.length === 0) {
        reservationsDiv.innerHTML = '<p>No tienes reservas.</p>';
        return;
    }

    let html = '<h2>Reservas</h2>';
    html += '<table class="report-table"><thead><tr><th>ID Reserva</th><th>Vuelo</th><th>Origen</th><th>Destino</th><th>Salida</th><th>Estado</th></tr></thead><tbody>';
    
    reservations.forEach(reservation => {
        html += `<tr>
            <td>${reservation.reservation_id}</td>
            <td>${reservation.flight_number}</td>
            <td>${reservation.origin}</td>
            <td>${reservation.destination}</td>
            <td>${reservation.departure_datetime}</td>
            <td>${reservation.status}</td>
        </tr>`;
    });

    html += '</tbody></table>';
    reservationsDiv.innerHTML = html;
}
