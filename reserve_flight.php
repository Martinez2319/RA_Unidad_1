<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flight_reservation";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Reservar vuelo
$user_id = $_POST['user_id'];
$flight_id = $_POST['flight_id'];
$sql = "INSERT INTO Reservations (user_id, flight_id) VALUES ('$user_id', '$flight_id')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Reservation successful"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
}

$conn->close();
?>
