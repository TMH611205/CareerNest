<?php
require_once __DIR__ . "/../config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['UserID'])) {
    echo json_encode(["error" => "Missing UserID"]);
    exit;
}

$UserID = (int)$data['UserID'];
$status = $data['status'] ?? 'online';

// 👇 xử lý theo status
if ($status === 'offline') {
    $sql = "UPDATE Users SET Active='offline' WHERE UserID=$UserID";
} else {
    $sql = "UPDATE Users SET Active='online' WHERE UserID=$UserID";
}

$conn->query($sql);

echo json_encode(["success" => true]);