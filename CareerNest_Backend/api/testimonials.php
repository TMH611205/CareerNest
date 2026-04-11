<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

$sql = "SELECT 
        r.ReviewID,
        r.Rating,
        r.Comment,
        r.CreatedAt,
        u.FullName,
        m.CareerName,
        i.url AS AvatarURL
    FROM reviews r
    LEFT JOIN Users u ON r.UserID = u.UserID
    LEFT JOIN Majors m ON r.CareerID = m.CareerID
    LEFT JOIN images i
        ON i.page = 'Người dùng'
        AND LOWER(TRIM(i.position)) = LOWER(TRIM(u.FullName))
    WHERE r.Status = 'Đã duyệt'
    ORDER BY r.CreatedAt DESC
    LIMIT 3
";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);