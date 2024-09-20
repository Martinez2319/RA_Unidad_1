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

// Buscar vuelos
$origin = $_POST['origin'];
$destination = $_POST['destination'];
$date = $_POST['date'];
$sql = "SELECT * FROM Flights WHERE origin='$origin' AND destination='$destination' AND date='$date'";
$result = $conn->query($sql);
$flights = [];
while ($row = $result->fetch_assoc()) {
    $flights[] = $row;
}
echo json_encode(["status" => "success", "flights" => $flights]);

$conn->close();
?>
