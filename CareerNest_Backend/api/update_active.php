<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!is_array($data)) {
    echo json_encode([
        "error" => "Dữ liệu gửi lên không hợp lệ",
        "raw" => $raw
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$userId = intval($data['UserID'] ?? 0);
$status = trim($data['status'] ?? 'online');

if ($userId <= 0) {
    echo json_encode([
        "error" => "Thiếu UserID"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($status !== 'online' && $status !== 'offline') {
    $status = 'online';
}

$stmt = $conn->prepare("UPDATE Users SET Active = ? WHERE UserID = ?");

if (!$stmt) {
    echo json_encode([
        "error" => "Lỗi prepare SQL",
        "detail" => $conn->error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param("si", $status, $userId);

if ($stmt->execute()) {
    echo json_encode([
        "message" => "Cập nhật trạng thái thành công",
        "UserID" => $userId,
        "Active" => $status,
        "affected_rows" => $stmt->affected_rows
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        "error" => "Cập nhật trạng thái thất bại",
        "detail" => $stmt->error
    ], JSON_UNESCAPED_UNICODE);
}