<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

function jsonResponse($data) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

switch ($method) {

    // =========================
    // GET
    // =========================
    case 'GET':
        $mode = $_GET['mode'] ?? '';

        // ===== FRONTEND QUIZ MODE =====
        if ($mode === 'quiz') {
            $sql = "
                SELECT 
                    q.QuestionID,
                    q.QuestionText,
                    q.QuestionType,
                    q.IsActive,
                    q.CreatedAt,
                    a.AnswerID,
                    a.AnswerText,
                    a.RiasecCode,
                    a.ScoreValue
                FROM questions q
                LEFT JOIN answers a ON q.QuestionID = a.QuestionID
                WHERE q.IsActive = 1
                ORDER BY q.QuestionID ASC, a.AnswerID ASC
            ";

            $result = $conn->query($sql);

            if (!$result) {
                jsonResponse(["error" => $conn->error]);
            }

            $questions = [];

            while ($row = $result->fetch_assoc()) {
                $qid = (int)$row['QuestionID'];

                if (!isset($questions[$qid])) {
                    $questions[$qid] = [
                        "QuestionID" => $qid,
                        "QuestionText" => $row['QuestionText'],
                        "QuestionType" => $row['QuestionType'],
                        "IsActive" => (int)$row['IsActive'],
                        "CreatedAt" => $row['CreatedAt'],
                        "Answers" => []
                    ];
                }

                if (!empty($row['AnswerID'])) {
                    $questions[$qid]["Answers"][] = [
                        "AnswerID" => (int)$row['AnswerID'],
                        "AnswerText" => $row['AnswerText'],
                        "RiasecCode" => $row['RiasecCode'],
                        "ScoreValue" => (int)$row['ScoreValue']
                    ];
                }
            }

            jsonResponse(array_values($questions));
        }

        // ===== ADMIN MODE =====
        $result = $conn->query("SELECT * FROM questions ORDER BY QuestionID DESC");

        if (!$result) {
            jsonResponse(["error" => $conn->error]);
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        jsonResponse($data);
        break;


    // =========================
    // POST
    // =========================
    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        if (!is_array($input)) {
            jsonResponse(["error" => "Dữ liệu gửi lên không hợp lệ"]);
        }

        $action = $input['action'] ?? '';

        // ===== NỘP BÀI TRẮC NGHIỆM =====
        if ($action === 'submit_quiz') {
            $userId = intval($input['UserID'] ?? 0);
            $answers = $input['Answers'] ?? [];

            if (!is_array($answers) || count($answers) === 0) {
                jsonResponse(["error" => "Chưa có câu trả lời nào"]);
            }

            $scores = [
                "R" => 0,
                "I" => 0,
                "A" => 0,
                "S" => 0,
                "E" => 0,
                "C" => 0
            ];

            $details = [];
            $totalQuestions = 0;

            foreach ($answers as $item) {
                $questionId = intval($item['QuestionID'] ?? 0);
                $answerId = intval($item['AnswerID'] ?? 0);

                if ($questionId <= 0 || $answerId <= 0) {
                    continue;
                }

                $sqlAnswer = "
                    SELECT AnswerID, QuestionID, RiasecCode, ScoreValue
                    FROM answers
                    WHERE AnswerID = $answerId AND QuestionID = $questionId
                    LIMIT 1
                ";

                $resultAnswer = $conn->query($sqlAnswer);
                if (!$resultAnswer || $resultAnswer->num_rows === 0) {
                    continue;
                }

                $answerRow = $resultAnswer->fetch_assoc();

                $riasec = $answerRow['RiasecCode'];
                $scoreValue = intval($answerRow['ScoreValue']);

                if (isset($scores[$riasec])) {
                    $scores[$riasec] += $scoreValue;
                }

                $details[] = [
                    "QuestionID" => $questionId,
                    "AnswerID" => $answerId,
                    "RiasecCode" => $riasec,
                    "ScoreEarned" => $scoreValue
                ];

                $totalQuestions++;
            }

            if ($totalQuestions === 0) {
                jsonResponse(["error" => "Không có câu trả lời hợp lệ"]);
            }

            // Sắp xếp top 3 mã RIASEC
            $sortedScores = $scores;
            arsort($sortedScores);
            $topCodes = array_keys($sortedScores);

            $top1 = $topCodes[0] ?? null;
            $top2 = $topCodes[1] ?? null;
            $top3 = $topCodes[2] ?? null;

            $resultSummary = trim(($top1 ?? '') . ($top2 ?? '') . ($top3 ?? ''));

            $attemptId = 0;

            // Chỉ lưu lịch sử nếu có UserID hợp lệ
            if ($userId > 0) {
                $stmt = $conn->prepare("
                    INSERT INTO quiz_attempts
                    (UserID, StartedAt, CompletedAt, TotalQuestions, ScoreR, ScoreI, ScoreA, ScoreS, ScoreE, ScoreC, TopCode1, TopCode2, TopCode3, ResultSummary)
                    VALUES
                    (?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                if ($stmt) {
                    $stmt->bind_param(
                        "iiiiiiisssss",
                        $userId,
                        $totalQuestions,
                        $scores['R'],
                        $scores['I'],
                        $scores['A'],
                        $scores['S'],
                        $scores['E'],
                        $scores['C'],
                        $top1,
                        $top2,
                        $top3,
                        $resultSummary
                    );

                    // bind_param ở trên thiếu 1 biến string cho ResultSummary, nên sửa chuẩn:
                    $stmt->close();

                    $stmt = $conn->prepare("
                        INSERT INTO quiz_attempts
                        (UserID, StartedAt, CompletedAt, TotalQuestions, ScoreR, ScoreI, ScoreA, ScoreS, ScoreE, ScoreC, TopCode1, TopCode2, TopCode3, ResultSummary)
                        VALUES
                        (?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");

                    $stmt->bind_param(
                        "iiiiiiiisssss",
                        $userId,
                        $totalQuestions,
                        $scores['R'],
                        $scores['I'],
                        $scores['A'],
                        $scores['S'],
                        $scores['E'],
                        $scores['C'],
                        $top1,
                        $top2,
                        $top3,
                        $resultSummary
                    );

                    if ($stmt->execute()) {
                        $attemptId = $stmt->insert_id;
                    }

                    $stmt->close();
                }

                // Lưu chi tiết từng câu
                if ($attemptId > 0) {
                    foreach ($details as $d) {
                        $qId = intval($d['QuestionID']);
                        $aId = intval($d['AnswerID']);
                        $code = $conn->real_escape_string($d['RiasecCode']);
                        $earned = intval($d['ScoreEarned']);

                        $conn->query("
                            INSERT INTO quiz_attempt_details
                            (AttemptID, QuestionID, AnswerID, RiasecCode, ScoreEarned)
                            VALUES
                            ($attemptId, $qId, $aId, '$code', $earned)
                        ");
                    }

                    // Lưu bảng điểm RIASEC
                    foreach ($scores as $code => $total) {
                        $codeEscaped = $conn->real_escape_string($code);
                        $total = intval($total);

                        $conn->query("
                            INSERT INTO riasec_scores
                            (AttemptID, RiasecCode, TotalScore)
                            VALUES
                            ($attemptId, '$codeEscaped', $total)
                        ");
                    }
                }
            }

            // Gợi ý ngành theo top 3 mã
            $topCodesEscaped = array_map(function ($c) use ($conn) {
                return "'" . $conn->real_escape_string($c) . "'";
            }, array_slice($topCodes, 0, 3));

            $inClause = implode(",", $topCodesEscaped);

            $suggestSql = "
                SELECT 
                    m.CareerID,
                    m.CareerCode,
                    m.CareerName,
                    m.Category,
                    m.Description,
                    m.Highlight,
                    m.DemandLevel,
                    m.SalaryMin,
                    m.SalaryMax,
                    COALESCE(SUM(mr.Weight), 0) AS MatchScore
                FROM majors m
                INNER JOIN major_riasec mr ON m.CareerID = mr.CareerID
                WHERE mr.RiasecCode IN ($inClause)
                GROUP BY 
                    m.CareerID, m.CareerCode, m.CareerName, m.Category,
                    m.Description, m.Highlight, m.DemandLevel, m.SalaryMin, m.SalaryMax
                ORDER BY MatchScore DESC, m.CareerID ASC
                LIMIT 6
            ";

            $suggestResult = $conn->query($suggestSql);

            $suggestedMajors = [];
            if ($suggestResult) {
                while ($row = $suggestResult->fetch_assoc()) {
                    $suggestedMajors[] = $row;
                }
            }

            // Mô tả RIASEC
            $riasecMeta = [
                "R" => [
                    "name" => "Realistic",
                    "title" => "Nhóm Kỹ thuật",
                    "desc" => "Bạn thiên về thực hành, thích máy móc, công cụ, môi trường kỹ thuật và công việc tạo ra kết quả cụ thể."
                ],
                "I" => [
                    "name" => "Investigative",
                    "title" => "Nhóm Nghiên cứu",
                    "desc" => "Bạn thích phân tích, tìm hiểu, khám phá quy luật, giải quyết vấn đề bằng tư duy logic."
                ],
                "A" => [
                    "name" => "Artistic",
                    "title" => "Nhóm Nghệ thuật",
                    "desc" => "Bạn thiên về sáng tạo, thích thể hiện ý tưởng, cảm xúc và tạo ra giá trị mới mẻ."
                ],
                "S" => [
                    "name" => "Social",
                    "title" => "Nhóm Xã hội",
                    "desc" => "Bạn thích hỗ trợ, hướng dẫn, chăm sóc và làm việc với con người."
                ],
                "E" => [
                    "name" => "Enterprising",
                    "title" => "Nhóm Quản lý",
                    "desc" => "Bạn có xu hướng lãnh đạo, thuyết phục, tổ chức và theo đuổi mục tiêu rõ ràng."
                ],
                "C" => [
                    "name" => "Conventional",
                    "title" => "Nhóm Nghiệp vụ",
                    "desc" => "Bạn phù hợp với công việc có quy trình, dữ liệu, tính chính xác và sự ổn định."
                ]
            ];

            jsonResponse([
                "message" => "Nộp bài thành công",
                "AttemptID" => $attemptId,
                "TotalQuestions" => $totalQuestions,
                "Scores" => $scores,
                "TopCodes" => array_slice($topCodes, 0, 3),
                "PrimaryGroup" => $riasecMeta[$top1] ?? null,
                "ResultSummary" => $resultSummary,
                "SuggestedMajors" => $suggestedMajors
            ]);
        }

        // ===== THÊM CÂU HỎI (ADMIN) =====
        $text = $conn->real_escape_string($input['QuestionText'] ?? '');
        $type = $conn->real_escape_string($input['QuestionType'] ?? 'single');
        $active = intval($input['IsActive'] ?? 1);

        if (!$text) {
            jsonResponse(["error" => "Thiếu nội dung câu hỏi"]);
        }

        $sql = "INSERT INTO questions (QuestionText, QuestionType, IsActive)
                VALUES ('$text', '$type', $active)";

        jsonResponse(
            $conn->query($sql)
                ? ["message" => "Thêm câu hỏi thành công"]
                : ["error" => $conn->error]
        );
        break;


    // =========================
    // PUT
    // =========================
    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = intval($input['QuestionID'] ?? 0);
        $text = $conn->real_escape_string($input['QuestionText'] ?? '');
        $type = $conn->real_escape_string($input['QuestionType'] ?? 'single');
        $active = intval($input['IsActive'] ?? 1);

        if ($id <= 0) {
            jsonResponse(["error" => "Thiếu QuestionID"]);
        }

        if (!$text) {
            jsonResponse(["error" => "Thiếu nội dung câu hỏi"]);
        }

        $sql = "UPDATE questions
                SET QuestionText='$text', QuestionType='$type', IsActive=$active
                WHERE QuestionID=$id";

        jsonResponse(
            $conn->query($sql)
                ? ["message" => "Cập nhật câu hỏi thành công"]
                : ["error" => $conn->error]
        );
        break;


    // =========================
    // DELETE
    // =========================
    case 'DELETE':
        parse_str(file_get_contents("php://input"), $input);
        $id = intval($input['QuestionID'] ?? 0);

        if ($id <= 0) {
            jsonResponse(["error" => "Thiếu QuestionID"]);
        }

        // Xóa answers trước để tránh lỗi khóa ngoại nếu có
        $conn->query("DELETE FROM answers WHERE QuestionID = $id");

        jsonResponse(
            $conn->query("DELETE FROM questions WHERE QuestionID = $id")
                ? ["message" => "Xóa câu hỏi thành công"]
                : ["error" => $conn->error]
        );
        break;

}
?>