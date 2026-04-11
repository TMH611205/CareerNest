<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

header("Content-Type: application/json; charset=UTF-8");

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data || !isset($data['message'])) {
    http_response_code(400);
    echo json_encode([
        "error" => "Thiếu nội dung câu hỏi"
    ]);
    exit;
}

$message = trim($data['message'] ?? '');
$history = $data['history'] ?? [];

if ($message === '') {
    http_response_code(400);
    echo json_encode([
        "error" => "Câu hỏi không được để trống"
    ]);
    exit;
}

// =========================
// CẤU HÌNH GEMINI
// =========================
$apiKey = getenv('GEMINI_API_KEY');

if (!$apiKey) {
    $apiKey = 'AIzaSyCg5b3VrWZP882Lr63NbSYapTICM65JN8c';
}

$model = 'gemini-3-flash-preview';

// =========================
// SYSTEM PROMPT
// =========================
$systemInstruction = <<<EOT
Bạn là trợ lý AI CareerNest – chuyên tư vấn ngành học và nghề nghiệp.

PHONG CÁCH:
- Thân thiện, chuyên nghiệp, rõ ràng
- Viết dễ hiểu, tự nhiên, không máy móc
- Trả lời bằng tiếng Việt
- Không sai chính tả

QUY TẮC:
1. Trả lời đúng trọng tâm câu hỏi
2. Không vòng vo, không lan man
3. Ngắn gọn nhưng đủ ý
4. Khi tư vấn ngành học, ưu tiên:
   - Ngành này học gì
   - Phù hợp với ai
   - Cơ hội việc làm
   - Mức lương tham khảo
   - Lộ trình phát triển
5. Nếu người dùng hỏi thêm, trả lời tiếp nối tự nhiên theo ngữ cảnh hội thoại
6. Nếu thông tin chưa chắc chắn, nói theo hướng tham khảo, không khẳng định quá mức
7. Không dùng từ ngữ quá học thuật, hãy thân thiện với học sinh/sinh viên
8. Không dùng markdown như ###, ##, *, **. Chỉ dùng văn bản thường, xuống dòng rõ ràng.

CÁCH TRÌNH BÀY:
- Ưu tiên câu ngắn, rõ
- Có thể xuống dòng để dễ đọc
- Khi phù hợp, dùng gạch đầu dòng ngắn

EOT;

// =========================
// BUILD CONTENTS
// =========================
$contents = [];

if (is_array($history)) {
    foreach ($history as $item) {
        $role = $item['role'] ?? '';
        $text = trim($item['text'] ?? '');

        if ($text === '') continue;

        if ($role === 'user') {
            $contents[] = [
                "role" => "user",
                "parts" => [
                    ["text" => $text]
                ]
            ];
        } else {
            $contents[] = [
                "role" => "model",
                "parts" => [
                    ["text" => $text]
                ]
            ];
        }
    }
}

$contents[] = [
    "role" => "user",
    "parts" => [
        ["text" => $message]
    ]
];

// =========================
// CALL GEMINI
// =========================
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

$payload = [
    "systemInstruction" => [
        "parts" => [
            ["text" => $systemInstruction]
        ]
    ],
    "contents" => $contents,
    "generationConfig" => [
        "temperature" => 0.7,
        "topP" => 0.95,
        "topK" => 40,
        "maxOutputTokens" => 2000
    ]
];

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_TIMEOUT => 60
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

curl_close($ch);

if ($curlError) {
    http_response_code(500);
    echo json_encode([
        "error" => "Lỗi kết nối Gemini: " . $curlError
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$result = json_decode($response, true);

if ($httpCode < 200 || $httpCode >= 300) {
    http_response_code($httpCode ?: 500);
    echo json_encode([
        "error" => $result['error']['message'] ?? "Gemini API request failed",
        "debug" => $result
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$reply = '';

if (!empty($result['candidates'][0]['content']['parts'])) {
    foreach ($result['candidates'][0]['content']['parts'] as $part) {
        $reply .= $part['text'] ?? '';
    }
}

$reply = trim($reply);

if ($reply === '') {
    http_response_code(500);
    echo json_encode([
        "error" => "AI không trả về nội dung"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    "reply" => $reply
], JSON_UNESCAPED_UNICODE);

