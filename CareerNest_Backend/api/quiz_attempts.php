<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT qa.*, u.FullName
                FROM quiz_attempts qa
                LEFT JOIN users u ON qa.UserID = u.UserID
                ORDER BY qa.AttemptID DESC";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        $userId = intval($input['UserID'] ?? 0);
        $completedAt = !empty($input['CompletedAt']) ? "'" . $conn->real_escape_string($input['CompletedAt']) . "'" : "NULL";
        $total = intval($input['TotalQuestions'] ?? 0);
        $scoreR = intval($input['ScoreR'] ?? 0);
        $scoreI = intval($input['ScoreI'] ?? 0);
        $scoreA = intval($input['ScoreA'] ?? 0);
        $scoreS = intval($input['ScoreS'] ?? 0);
        $scoreE = intval($input['ScoreE'] ?? 0);
        $scoreC = intval($input['ScoreC'] ?? 0);
        $top1 = $conn->real_escape_string($input['TopCode1'] ?? '');
        $top2 = $conn->real_escape_string($input['TopCode2'] ?? '');
        $top3 = $conn->real_escape_string($input['TopCode3'] ?? '');
        $summary = $conn->real_escape_string($input['ResultSummary'] ?? '');

        $sql = "INSERT INTO quiz_attempts
                (UserID, CompletedAt, TotalQuestions, ScoreR, ScoreI, ScoreA, ScoreS, ScoreE, ScoreC, TopCode1, TopCode2, TopCode3, ResultSummary)
                VALUES
                ($userId, $completedAt, $total, $scoreR, $scoreI, $scoreA, $scoreS, $scoreE, $scoreC, '$top1', '$top2', '$top3', '$summary')";

        echo json_encode($conn->query($sql)
            ? ["message" => "Thêm kết quả thành công"]
            : ["error" => $conn->error]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = intval($input['AttemptID'] ?? 0);
        $completedAt = !empty($input['CompletedAt']) ? "'" . $conn->real_escape_string($input['CompletedAt']) . "'" : "NULL";
        $total = intval($input['TotalQuestions'] ?? 0);
        $scoreR = intval($input['ScoreR'] ?? 0);
        $scoreI = intval($input['ScoreI'] ?? 0);
        $scoreA = intval($input['ScoreA'] ?? 0);
        $scoreS = intval($input['ScoreS'] ?? 0);
        $scoreE = intval($input['ScoreE'] ?? 0);
        $scoreC = intval($input['ScoreC'] ?? 0);
        $top1 = $conn->real_escape_string($input['TopCode1'] ?? '');
        $top2 = $conn->real_escape_string($input['TopCode2'] ?? '');
        $top3 = $conn->real_escape_string($input['TopCode3'] ?? '');
        $summary = $conn->real_escape_string($input['ResultSummary'] ?? '');

        $sql = "UPDATE quiz_attempts
                SET CompletedAt=$completedAt,
                    TotalQuestions=$total,
                    ScoreR=$scoreR,
                    ScoreI=$scoreI,
                    ScoreA=$scoreA,
                    ScoreS=$scoreS,
                    ScoreE=$scoreE,
                    ScoreC=$scoreC,
                    TopCode1='$top1',
                    TopCode2='$top2',
                    TopCode3='$top3',
                    ResultSummary='$summary'
                WHERE AttemptID=$id";

        echo json_encode($conn->query($sql)
            ? ["message" => "Cập nhật kết quả thành công"]
            : ["error" => $conn->error]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $input);
        $id = intval($input['AttemptID'] ?? 0);

        echo json_encode($conn->query("DELETE FROM quiz_attempts WHERE AttemptID=$id")
            ? ["message" => "Xóa kết quả thành công"]
            : ["error" => $conn->error]);
        break;
}
?>