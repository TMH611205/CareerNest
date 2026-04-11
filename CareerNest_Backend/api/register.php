<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . "/../config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['fullName']) || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["message" => "Thiếu dữ liệu"]);
    exit;
}

$fullName = $data['fullName'];
$email = $data['email'];
$password = $data['password'];

// Hash password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Check email tồn tại
$check = $conn->prepare("SELECT UserID FROM Users WHERE Email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["message" => "Email đã tồn tại"]);
    exit;
}

// Insert user
$stmt = $conn->prepare("INSERT INTO Users (FullName, Email, PasswordHash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fullName, $email, $passwordHash);

if ($stmt->execute()) {
    echo json_encode(["message" => "Đăng ký thành công"]);
} else {
    echo json_encode(["message" => "Đăng ký thất bại"]);
}