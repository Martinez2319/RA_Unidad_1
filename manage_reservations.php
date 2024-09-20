<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flight_reservation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener reservas
$email = $_POST['email'];
$sql = "SELECT * FROM Reservations r JOIN Users u ON r.user_id = u.user_id WHERE u.email='$email'";
$result = $conn->query($sql);
$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}
echo json_encode($reservations);

$conn->close();
?>
