<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flight_reservation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $origin = $conn->real_escape_string($_POST['origin']);
    $destination = $conn->real_escape_string($_POST['destination']);
    $departure_date = $conn->real_escape_string($_POST['departure_date']);

    $sql = "SELECT * FROM Flights WHERE origin = ? AND destination = ? AND DATE(departure_datetime) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $origin, $destination, $departure_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $flights = [];
    while ($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }

    echo json_encode($flights);

    $stmt->close();
}

$conn->close();
?>