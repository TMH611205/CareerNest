<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'GET':
        $careerId = intval($_GET['CareerID'] ?? 0);

        if ($careerId <= 0) {
            echo json_encode(["error" => "Thiếu CareerID"]);
            exit;
        }

        $sql = "SELECT 
                    r.ReviewID,
                    r.CareerID,
                    r.UserID,
                    r.Rating,
                    r.Comment,
                    r.CreatedAt,
                    r.Status,
                    u.FullName,
                    i.url AS AvatarURL
                FROM reviews r
                LEFT JOIN Users u ON r.UserID = u.UserID
                LEFT JOIN images i
                    ON i.page = 'Người dùng'
                    AND LOWER(TRIM(i.position)) = LOWER(TRIM(u.FullName))
                WHERE r.CareerID = $careerId
                AND r.Status = 'Đã duyệt'
                ORDER BY r.CreatedAt DESC
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
        break;

    case 'POST':
        $raw = file_get_contents("php://input");
        $input = json_decode($raw, true);

        if (!is_array($input)) {
            echo json_encode([
                "error" => "Dữ liệu gửi lên không hợp lệ",
                "raw" => $raw
            ]);
            exit;
        }

        $careerId = intval($input['CareerID'] ?? 0);
        $userId   = intval($input['UserID'] ?? 0);
        $rating   = intval($input['Rating'] ?? 0);
        $comment  = trim($input['Comment'] ?? '');

        if ($careerId <= 0) {
            echo json_encode(["error" => "CareerID không hợp lệ"]);
            exit;
        }

        if ($userId <= 0) {
            echo json_encode(["error" => "UserID không hợp lệ"]);
            exit;
        }

        if ($rating < 1 || $rating > 5) {
            echo json_encode(["error" => "Rating phải từ 1 đến 5"]);
            exit;
        }

        if ($comment === '') {
            echo json_encode(["error" => "Vui lòng nhập nội dung đánh giá"]);
            exit;
        }

        $commentEscaped = $conn->real_escape_string($comment);

        // Nếu muốn bỏ giới hạn 1 user / 1 ngành thì xóa đoạn check này
        $checkSql = "SELECT ReviewID FROM reviews WHERE CareerID = $careerId AND UserID = $userId LIMIT 1";
        $check = $conn->query($checkSql);

        if ($check && $check->num_rows > 0) {
            echo json_encode(["error" => "Bạn đã đánh giá ngành này rồi"]);
            exit;
        }

        $sql = "INSERT INTO reviews (CareerID, UserID, Rating, Comment, Status)
                VALUES ($careerId, $userId, $rating, '$commentEscaped', 'Chờ duyệt')";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Gửi đánh giá thành công, vui lòng chờ duyệt"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    default:
        echo json_encode(["error" => "Phương thức không được hỗ trợ"]);
        break;
}