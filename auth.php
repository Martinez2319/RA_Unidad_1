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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'register') {
            $user = $conn->real_escape_string($_POST['username']);
            $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $email = $conn->real_escape_string($_POST['email']);
            
            $sql = "INSERT INTO Users (username, password, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $user, $pass, $email);
            
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Registration successful"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
            }
            $stmt->close();
        } elseif ($_POST['action'] == 'login') {
            $user = $conn->real_escape_string($_POST['username']);
            $pass = $_POST['password'];
            
            $sql = "SELECT user_id, username, password FROM Users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if (password_verify($pass, $row['password'])) {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = $row['username'];
                    echo json_encode(["status" => "success", "message" => "Login successful"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "User not found"]);
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>