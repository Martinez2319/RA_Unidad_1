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

$user_id = $_SESSION['user_id'];
$sql = "SELECT r.reservation_id, f.flight_number, f.origin, f.destination, f.departure_datetime, r.status 
        FROM Reservations r 
        JOIN Flights f ON r.flight_id = f.flight_id 
        WHERE r.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

echo json_encode(["status" => "success", "reservations" => $reservations]);

$stmt->close();
$conn->close();
?>