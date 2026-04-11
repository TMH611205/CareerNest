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
        $sql = "SELECT d.*, q.QuestionText, a.AnswerText
                FROM quiz_attempt_details d
                LEFT JOIN questions q ON d.QuestionID = q.QuestionID
                LEFT JOIN answers a ON d.AnswerID = a.AnswerID
                ORDER BY d.DetailID DESC";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        $attemptId = intval($input['AttemptID'] ?? 0);
        $questionId = intval($input['QuestionID'] ?? 0);
        $answerId = intval($input['AnswerID'] ?? 0);
        $riasec = $conn->real_escape_string($input['RiasecCode'] ?? 'R');
        $score = intval($input['ScoreEarned'] ?? 1);

        $sql = "INSERT INTO quiz_attempt_details (AttemptID, QuestionID, AnswerID, RiasecCode, ScoreEarned)
                VALUES ($attemptId, $questionId, $answerId, '$riasec', $score)";

        echo json_encode($conn->query($sql)
            ? ["message" => "Thêm chi tiết kết quả thành công"]
            : ["error" => $conn->error]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = intval($input['DetailID'] ?? 0);
        $attemptId = intval($input['AttemptID'] ?? 0);
        $questionId = intval($input['QuestionID'] ?? 0);
        $answerId = intval($input['AnswerID'] ?? 0);
        $riasec = $conn->real_escape_string($input['RiasecCode'] ?? 'R');
        $score = intval($input['ScoreEarned'] ?? 1);

        $sql = "UPDATE quiz_attempt_details
                SET AttemptID=$attemptId,
                    QuestionID=$questionId,
                    AnswerID=$answerId,
                    RiasecCode='$riasec',
                    ScoreEarned=$score
                WHERE DetailID=$id";

        echo json_encode($conn->query($sql)
            ? ["message" => "Cập nhật chi tiết kết quả thành công"]
            : ["error" => $conn->error]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $input);
        $id = intval($input['DetailID'] ?? 0);

        echo json_encode($conn->query("DELETE FROM quiz_attempt_details WHERE DetailID=$id")
            ? ["message" => "Xóa chi tiết kết quả thành công"]
            : ["error" => $conn->error]);
        break;
}
?>