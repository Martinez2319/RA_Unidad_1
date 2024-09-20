<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flight_reservation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $flight_id = $conn->real_escape_string($_POST['flight_id']);

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Verificar disponibilidad de asientos
        $sql = "SELECT available_seats FROM Flights WHERE flight_id = ? FOR UPDATE";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $flight_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $flight = $result->fetch_assoc();

        if ($flight['available_seats'] > 0) {
            // Crear reserva
            $sql = "INSERT INTO Reservations (user_id, flight_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $flight_id);
            $stmt->execute();

            // Actualizar asientos disponibles
            $sql = "UPDATE Flights SET available_seats = available_seats - 1 WHERE flight_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $flight_id);
            $stmt->execute();

            // Confirmar transacción
            $conn->commit();
            echo json_encode(["status" => "success", "message" => "Reservation successful"]);
        } else {
            echo json_encode(["status" => "error", "message" => "No seats available"]);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
}

$conn->close();
?>