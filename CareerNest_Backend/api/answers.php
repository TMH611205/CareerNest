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
        $sql = "SELECT a.*, q.QuestionText
                FROM answers a
                LEFT JOIN questions q ON a.QuestionID = q.QuestionID
                ORDER BY a.AnswerID DESC";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        $questionId = intval($input['QuestionID'] ?? 0);
        $answerText = $conn->real_escape_string($input['AnswerText'] ?? '');
        $riasec = $conn->real_escape_string($input['RiasecCode'] ?? 'R');
        $score = intval($input['ScoreValue'] ?? 1);

        if ($questionId <= 0 || !$answerText) {
            echo json_encode(["error" => "Thiếu dữ liệu đáp án"]);
            exit;
        }

        $sql = "INSERT INTO answers (QuestionID, AnswerText, RiasecCode, ScoreValue)
                VALUES ($questionId, '$answerText', '$riasec', $score)";

        echo json_encode($conn->query($sql)
            ? ["message" => "Thêm đáp án thành công"]
            : ["error" => $conn->error]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = intval($input['AnswerID'] ?? 0);
        $questionId = intval($input['QuestionID'] ?? 0);
        $answerText = $conn->real_escape_string($input['AnswerText'] ?? '');
        $riasec = $conn->real_escape_string($input['RiasecCode'] ?? 'R');
        $score = intval($input['ScoreValue'] ?? 1);

        if ($id <= 0) {
            echo json_encode(["error" => "Thiếu AnswerID"]);
            exit;
        }

        $sql = "UPDATE answers
                SET QuestionID=$questionId,
                    AnswerText='$answerText',
                    RiasecCode='$riasec',
                    ScoreValue=$score
                WHERE AnswerID=$id";

        echo json_encode($conn->query($sql)
            ? ["message" => "Cập nhật đáp án thành công"]
            : ["error" => $conn->error]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $input);
        $id = intval($input['AnswerID'] ?? 0);

        if ($id <= 0) {
            echo json_encode(["error" => "Thiếu AnswerID"]);
            exit;
        }

        echo json_encode($conn->query("DELETE FROM answers WHERE AnswerID=$id")
            ? ["message" => "Xóa đáp án thành công"]
            : ["error" => $conn->error]);
        break;
}
?>