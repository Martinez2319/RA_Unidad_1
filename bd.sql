CREATE DATABASE flight_reservation;
USE flight_reservation;

CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Flights (
    flight_id INT AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(10) NOT NULL,
    origin VARCHAR(50) NOT NULL,
    destination VARCHAR(50) NOT NULL,
    departure_datetime DATETIME NOT NULL,
    arrival_datetime DATETIME NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    available_seats INT NOT NULL
);

CREATE TABLE Reservations (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    flight_id INT,
    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (flight_id) REFERENCES Flights(flight_id)
);

-- Insertar algunos vuelos de ejemplo
INSERT INTO Flights (flight_number, origin, destination, departure_datetime, arrival_datetime, price, available_seats) VALUES
('FL001', 'New York', 'Los Angeles', '2024-10-01 08:00:00', '2024-10-01 11:00:00', 299.99, 150),
('FL002', 'Chicago', 'Miami', '2024-10-02 10:00:00', '2024-10-02 13:30:00', 249.99, 180),
('FL003', 'San Francisco', 'Seattle', '2024-10-03 14:00:00', '2024-10-03 16:00:00', 199.99, 120);