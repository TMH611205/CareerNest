<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "CareerNest";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// echo "Kết nối thành công";
?>