<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

function jsonResponse($data) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function buildImageUrl($path) {
    if (!$path) return "";
    if (preg_match('/^https?:\/\//i', $path)) return $path;
    return "http://localhost:9999/CareerNest/CareerNest_Backend/" . ltrim($path, '/');
}

switch ($method) {

    case 'GET':
        $sql = "SELECT 
                    u.UserID,
                    u.FullName,
                    u.Email,
                    u.Role,
                    u.CreatedAt,
                    u.Active,
                    i.url AS AvatarURL
                FROM Users u
                LEFT JOIN images i
                    ON i.page = 'Người dùng'
                    AND TRIM(i.position) = TRIM(CAST(u.UserID AS CHAR))
                ORDER BY u.UserID DESC";

        $result = $conn->query($sql);

        if (!$result) {
            jsonResponse(["error" => $conn->error]);
        }

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $row['AvatarFullURL'] = !empty($row['AvatarURL'])
                ? buildImageUrl($row['AvatarURL'])
                : '';
            $data[] = $row;
        }

        jsonResponse($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        $name = trim($input['FullName'] ?? '');
        $email = trim($input['Email'] ?? '');
        $passwordRaw = trim($input['Password'] ?? '');
        $role = trim($input['Role'] ?? 'Student');

        if ($name === '' || $email === '' || $passwordRaw === '') {
            jsonResponse(["error" => "Vui lòng nhập đầy đủ họ tên, email và mật khẩu"]);
        }

        $password = password_hash($passwordRaw, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO Users (FullName, Email, PasswordHash, Role)
            VALUES (?, ?, ?, ?)
        ");

        if (!$stmt) {
            jsonResponse(["error" => $conn->error]);
        }

        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            jsonResponse(["message" => "Thêm thành công"]);
        } else {
            jsonResponse(["error" => $stmt->error ?: $conn->error]);
        }
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = intval($input['UserID'] ?? 0);
        $name = trim($input['FullName'] ?? '');
        $email = trim($input['Email'] ?? '');
        $role = trim($input['Role'] ?? '');
        $passwordRaw = trim($input['Password'] ?? '');

        if ($id <= 0) {
            jsonResponse(["error" => "Thiếu UserID"]);
        }

        if ($name === '' || $email === '' || $role === '') {
            jsonResponse(["error" => "Vui lòng nhập đầy đủ thông tin"]);
        }

        if ($passwordRaw !== '') {
            $password = password_hash($passwordRaw, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                UPDATE Users
                SET FullName = ?, Email = ?, Role = ?, PasswordHash = ?
                WHERE UserID = ?
            ");

            if (!$stmt) {
                jsonResponse(["error" => $conn->error]);
            }

            $stmt->bind_param("ssssi", $name, $email, $role, $password, $id);
        } else {
            $stmt = $conn->prepare("
                UPDATE Users
                SET FullName = ?, Email = ?, Role = ?
                WHERE UserID = ?
            ");

            if (!$stmt) {
                jsonResponse(["error" => $conn->error]);
            }

            $stmt->bind_param("sssi", $name, $email, $role, $id);
        }

        if ($stmt->execute()) {
            jsonResponse(["message" => "Cập nhật thành công"]);
        } else {
            jsonResponse(["error" => $stmt->error ?: $conn->error]);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $input);
        $id = intval($input['UserID'] ?? 0);

        if ($id <= 0) {
            jsonResponse(["error" => "Thiếu UserID"]);
        }

        $stmt = $conn->prepare("DELETE FROM Users WHERE UserID = ?");
        if (!$stmt) {
            jsonResponse(["error" => $conn->error]);
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            jsonResponse(["message" => "Xóa thành công"]);
        } else {
            jsonResponse(["error" => $stmt->error ?: $conn->error]);
        }
        break;

    default:
        jsonResponse(["error" => "Phương thức không hợp lệ"]);
}
?>