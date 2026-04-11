<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // GET - lấy toàn bộ đánh giá cho admin
    case 'GET':
    $sql = "SELECT 
            r.ReviewID,
            r.CareerID,
            r.UserID,
            r.Rating,
            r.Comment,
            r.CreatedAt,
            r.Status,
            u.FullName,
            m.CareerName,
            i.url AS AvatarURL
        FROM reviews r
        LEFT JOIN Users u ON r.UserID = u.UserID
        LEFT JOIN Majors m ON r.CareerID = m.CareerID
        LEFT JOIN images i 
            ON i.page = 'Người dùng'
            AND LOWER(TRIM(i.position)) = LOWER(TRIM(u.FullName))
        ORDER BY r.ReviewID DESC
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

    // PUT - cập nhật trạng thái đánh giá
    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $reviewId = intval($input['ReviewID'] ?? 0);
        $status = trim($input['Status'] ?? '');

        if ($reviewId <= 0 || !in_array($status, ['Chờ duyệt', 'Đã duyệt', 'Từ chối'])) {
            echo json_encode(["error" => "Dữ liệu không hợp lệ"]);
            exit;
        }

        // cập nhật trạng thái review
        $statusEscaped = $conn->real_escape_string($status);
        $sql = "UPDATE reviews SET Status = '$statusEscaped' WHERE ReviewID = $reviewId";

        if (!$conn->query($sql)) {
            echo json_encode(["error" => $conn->error]);
            exit;
        }

        // lấy CareerID để cập nhật rating trong bảng majors
        $careerResult = $conn->query("SELECT CareerID FROM reviews WHERE ReviewID = $reviewId");
        if ($careerResult && $careerResult->num_rows > 0) {
            $careerRow = $careerResult->fetch_assoc();
            $careerId = intval($careerRow['CareerID'] ?? 0);

            if ($careerId > 0) {
                $avgSql = "UPDATE Majors
                    SET
                        Rating = (
                            SELECT IFNULL(ROUND(AVG(Rating), 1), 0)
                            FROM reviews
                            WHERE CareerID = $careerId
                              AND Status = 'Đã duyệt'
                        ),
                        RatingCount = (
                            SELECT COUNT(*)
                            FROM reviews
                            WHERE CareerID = $careerId
                              AND Status = 'Đã duyệt'
                        )
                    WHERE CareerID = $careerId
                ";
                $conn->query($avgSql);
            }
        }

        echo json_encode(["message" => "Cập nhật trạng thái thành công"]);
        break;

    // DELETE - xóa review
    case 'DELETE':
        parse_str(file_get_contents("php://input"), $input);
        $reviewId = intval($input['ReviewID'] ?? 0);

        if ($reviewId <= 0) {
            echo json_encode(["error" => "Thiếu ReviewID"]);
            exit;
        }

        // lấy CareerID trước khi xóa để cập nhật lại rating
        $careerId = 0;
        $careerResult = $conn->query("SELECT CareerID FROM reviews WHERE ReviewID = $reviewId");
        if ($careerResult && $careerResult->num_rows > 0) {
            $careerRow = $careerResult->fetch_assoc();
            $careerId = intval($careerRow['CareerID'] ?? 0);
        }

        if (!$conn->query("DELETE FROM reviews WHERE ReviewID = $reviewId")) {
            echo json_encode(["error" => $conn->error]);
            exit;
        }

        // cập nhật lại rating sau khi xóa
        if ($careerId > 0) {
            $avgSql = "UPDATE Majors
                SET
                    Rating = (
                        SELECT IFNULL(ROUND(AVG(Rating), 1), 0)
                        FROM reviews
                        WHERE CareerID = $careerId
                          AND Status = 'Đã duyệt'
                    ),
                    RatingCount = (
                        SELECT COUNT(*)
                        FROM reviews
                        WHERE CareerID = $careerId
                          AND Status = 'Đã duyệt'
                    )
                WHERE CareerID = $careerId
            ";
            $conn->query($avgSql);
        }

        echo json_encode(["message" => "Xóa đánh giá thành công"]);
        break;
}