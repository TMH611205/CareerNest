<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

function buildImageUrl($path) {
    if (!$path) return "";
    if (preg_match('/^https?:\/\//i', $path)) return $path;
    return "http://localhost:9999/CareerNest/CareerNest_Backend/" . ltrim($path, '/');
}

$sql = "SELECT 
            r.ReviewID,
            r.Rating,
            r.Comment,
            r.CreatedAt,
            u.UserID,
            u.FullName,
            m.CareerName,
            i.url AS AvatarURL
        FROM reviews r
        LEFT JOIN Users u 
            ON r.UserID = u.UserID
        LEFT JOIN Majors m 
            ON r.CareerID = m.CareerID
        LEFT JOIN images i
            ON i.page = 'Người dùng'
            AND TRIM(i.position) = TRIM(CAST(u.UserID AS CHAR))
        WHERE r.Status = 'Đã duyệt'
        ORDER BY r.CreatedAt DESC
        LIMIT 3";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => $conn->error], JSON_UNESCAPED_UNICODE);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $row['AvatarFullURL'] = !empty($row['AvatarURL']) ? buildImageUrl($row['AvatarURL']) : '';
    $data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);