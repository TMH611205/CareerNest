<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";

function buildImageUrl($path) {
    if (!$path) return "";
    if (preg_match('/^https?:\/\//i', $path)) return $path;
    return "http://localhost:9999/CareerNest/CareerNest_Backend/" . ltrim($path, '/');
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode([
        "message" => "Thiếu email hoặc mật khẩu"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$email = trim($data['email']);
$password = $data['password'];

$sql = "SELECT 
            u.UserID,
            u.FullName,
            u.Email,
            u.PasswordHash,
            u.Role,
            u.Active,
            i.url AS AvatarURL
        FROM Users u
        LEFT JOIN images i
            ON i.page = 'Người dùng'
            AND LOWER(TRIM(i.position)) = LOWER(TRIM(u.FullName))
        WHERE u.Email = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "message" => "Lỗi prepare SQL",
        "error" => $conn->error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['PasswordHash'])) {
        echo json_encode([
            "message" => "Đăng nhập thành công",
            "user" => [
                "id" => (int)$row['UserID'],
                "name" => $row['FullName'],
                "email" => $row['Email'],
                "role" => $row['Role'],
                "avatar" => $row['AvatarURL'] ? buildImageUrl($row['AvatarURL']) : "",
                "active" => $row['Active'] ?? "offline"
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

echo json_encode([
    "message" => "Sai email hoặc mật khẩu"
], JSON_UNESCAPED_UNICODE);