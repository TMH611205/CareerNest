<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . '/../config/database.php';

function jsonError($message, $statusCode = 400) {
    http_response_code($statusCode);
    echo json_encode(['error' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method không được hỗ trợ', 405);
}

$input = json_decode(file_get_contents('php://input'), true);

$userId = isset($input['userId']) ? (int)$input['userId'] : 0;
$answers = $input['answers'] ?? [];

if ($userId <= 0) {
    jsonError('Bạn cần đăng nhập trước khi nộp bài để lưu kết quả vào database.');
}

if (!is_array($answers) || count($answers) === 0) {
    jsonError('Danh sách đáp án không hợp lệ.');
}

try {
    $conn->begin_transaction();

    $userCheck = $conn->prepare('SELECT UserID FROM users WHERE UserID = ? LIMIT 1');
    $userCheck->bind_param('i', $userId);
    $userCheck->execute();
    $userExists = $userCheck->get_result()->fetch_assoc();
    $userCheck->close();

    if (!$userExists) {
        throw new Exception('Người dùng không tồn tại.');
    }

    $questionIds = [];
    $answerIds = [];
    foreach ($answers as $item) {
        $qId = (int)($item['questionId'] ?? 0);
        $aId = (int)($item['answerId'] ?? 0);
        if ($qId <= 0 || $aId <= 0) {
            throw new Exception('Có đáp án gửi lên không hợp lệ.');
        }
        $questionIds[$qId] = true;
        $answerIds[] = $aId;
    }

    $placeholders = implode(',', array_fill(0, count($answerIds), '?'));
    $types = str_repeat('i', count($answerIds));

    $sql = "SELECT 
            a.AnswerID,
            a.QuestionID,
            a.RiasecCode,
            a.ScoreValue,
            a.AnswerText,
            q.QuestionText
        FROM answers a
        INNER JOIN questions q ON q.QuestionID = a.QuestionID
        WHERE a.AnswerID IN ($placeholders)
          AND q.IsActive = 1
    ";

    $stmtAnswers = $conn->prepare($sql);
    $stmtAnswers->bind_param($types, ...$answerIds);
    $stmtAnswers->execute();
    $result = $stmtAnswers->get_result();

    $answerMap = [];
    while ($row = $result->fetch_assoc()) {
        $answerMap[(int)$row['AnswerID']] = $row;
    }
    $stmtAnswers->close();

    if (count($answerMap) !== count($answerIds)) {
        throw new Exception('Một hoặc nhiều đáp án không tồn tại trong hệ thống.');
    }

    $questionCount = count($questionIds);

    $scores = [
        'R' => 0,
        'I' => 0,
        'A' => 0,
        'S' => 0,
        'E' => 0,
        'C' => 0,
    ];

    $details = [];

    foreach ($answers as $item) {
        $qId = (int)$item['questionId'];
        $aId = (int)$item['answerId'];
        $answerRow = $answerMap[$aId] ?? null;

        if (!$answerRow) {
            throw new Exception('Không tìm thấy thông tin đáp án.');
        }

        if ((int)$answerRow['QuestionID'] !== $qId) {
            throw new Exception('Đáp án không thuộc câu hỏi tương ứng.');
        }

        $code = $answerRow['RiasecCode'];
        $earned = (int)($answerRow['ScoreValue'] ?? 0);

        if (!isset($scores[$code])) {
            $scores[$code] = 0;
        }
        $scores[$code] += $earned;

        $details[] = [
            'QuestionID' => $qId,
            'AnswerID' => $aId,
            'RiasecCode' => $code,
            'ScoreEarned' => $earned,
        ];
    }

    arsort($scores);
    $topCodes = array_keys($scores);
    $topCodes = array_slice($topCodes, 0, 3);
    $resultSummary = implode('-', $topCodes);

    $stmtAttempt = $conn->prepare(
        'INSERT INTO quiz_attempts (UserID, CompletedAt, TotalQuestions, ScoreR, ScoreI, ScoreA, ScoreS, ScoreE, ScoreC, TopCode1, TopCode2, TopCode3, ResultSummary)
         VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );

    $scoreR = $scores['R'] ?? 0;
    $scoreI = $scores['I'] ?? 0;
    $scoreA = $scores['A'] ?? 0;
    $scoreS = $scores['S'] ?? 0;
    $scoreE = $scores['E'] ?? 0;
    $scoreC = $scores['C'] ?? 0;
    $top1 = $topCodes[0] ?? null;
    $top2 = $topCodes[1] ?? null;
    $top3 = $topCodes[2] ?? null;

    $stmtAttempt->bind_param(
        'iiiiiiiissss',
        $userId,
        $questionCount,
        $scoreR,
        $scoreI,
        $scoreA,
        $scoreS,
        $scoreE,
        $scoreC,
        $top1,
        $top2,
        $top3,
        $resultSummary
    );
    $stmtAttempt->execute();
    $attemptId = $stmtAttempt->insert_id;
    $stmtAttempt->close();

    $stmtDetail = $conn->prepare(
        'INSERT INTO quiz_attempt_details (AttemptID, QuestionID, AnswerID, RiasecCode, ScoreEarned)
         VALUES (?, ?, ?, ?, ?)'
    );

    foreach ($details as $detail) {
        $stmtDetail->bind_param(
            'iiisi',
            $attemptId,
            $detail['QuestionID'],
            $detail['AnswerID'],
            $detail['RiasecCode'],
            $detail['ScoreEarned']
        );
        $stmtDetail->execute();
    }
    $stmtDetail->close();

    $stmtScore = $conn->prepare(
        'INSERT INTO riasec_scores (AttemptID, RiasecCode, TotalScore) VALUES (?, ?, ?)'
    );
    foreach ($scores as $code => $totalScore) {
        $stmtScore->bind_param('isi', $attemptId, $code, $totalScore);
        $stmtScore->execute();
    }
    $stmtScore->close();

    $rankMap = [];
    $rank = 1;
    foreach ($scores as $code => $total) {
        $rankMap[$code] = $rank++;
    }

    $top1Code = $topCodes[0] ?? '';
    $top2Code = $topCodes[1] ?? '';
    $top3Code = $topCodes[2] ?? '';

    $stmtMajors = $conn->prepare(
        "SELECT 
            m.CareerID,
            m.CareerCode,
            m.CareerName,
            m.Category,
            m.Description,
            m.Highlight,
            m.DemandLevel,
            m.SalaryMin,
            m.SalaryMax,
            m.Rating,
            m.RatingCount,
            i.url AS ImageURL,
            SUM(
                CASE 
                    WHEN mr.RiasecCode = ? THEN mr.Weight * 3
                    WHEN mr.RiasecCode = ? THEN mr.Weight * 2
                    WHEN mr.RiasecCode = ? THEN mr.Weight * 1
                    ELSE 0
                END
            ) AS MatchScore
        FROM majors m
        INNER JOIN major_riasec mr ON mr.CareerID = m.CareerID
        LEFT JOIN images i
            ON i.page = 'Ngành học'
            AND LOWER(TRIM(i.position)) = LOWER(TRIM(m.CareerName))
        GROUP BY 
            m.CareerID, m.CareerCode, m.CareerName, m.Category, m.Description,
            m.Highlight, m.DemandLevel, m.SalaryMin, m.SalaryMax,
            m.Rating, m.RatingCount, i.url
        ORDER BY MatchScore DESC, m.Rating DESC, m.CareerName ASC
        LIMIT 6
        "
    );
    $stmtMajors->bind_param('sss', $top1Code, $top2Code, $top3Code);
    $stmtMajors->execute();
    $majorResult = $stmtMajors->get_result();

    $suggestedMajors = [];
    while ($row = $majorResult->fetch_assoc()) {
        $suggestedMajors[] = $row;
    }
    $stmtMajors->close();

    $conn->commit();

    echo json_encode([
        'message' => 'Nộp bài thành công',
        'attemptId' => $attemptId,
        'topCodes' => $topCodes,
        'scores' => $scores,
        'resultSummary' => $resultSummary,
        'suggestedMajors' => $suggestedMajors
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    $conn->rollback();
    jsonError($e->getMessage(), 500);
}
