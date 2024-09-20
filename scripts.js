document.addEventListener('DOMContentLoaded', () => {
    // Registro de usuario
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            formData.append('action', 'register');
            try {
                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                alert(data.message);
                if (data.status === 'success') {
                    window.location.href = 'login.html';
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }

    // Inicio de sesión
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);
            formData.append('action', 'login');
            try {
                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                alert(data.message);
                if (data.status === 'success') {
                    window.location.href = 'search.html';
                }
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

    // Mostrar reservas
    const reservationsDiv = document.getElementById('reservations');
    if (reservationsDiv) {
        fetchReservations();
    }
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

async function reserveFlight(flightId) {
    try {
        const response = await fetch('reserve_flight.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `flight_id=${flightId}`
        });
        const data = await response.json();
        alert(data.message);
        if (data.status === 'success') {
            window.location.href = 'reservations.html';
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function fetchReservations() {
    try {
        const response = await fetch('manage_reservations.php');
        const data = await response.json();
        displayReservations(data.reservations);
    } catch (error) {
        console.error('Error:', error);
    }
}

function displayReservations(reservations) {
    const reservationsDiv = document.getElementById('reservations');
    if (reservations.length === 0) {
        reservationsDiv.innerHTML = '<p>No tienes reservas.</p>';
        return;
    }
    let html = '<table><tr><th>ID Reserva</th><th>Vuelo</th><th>Origen</th><th>Destino</th><th>Salida</th><th>Estado</th></tr>';
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
    html += '</table>';
    reservationsDiv.innerHTML = html;
}