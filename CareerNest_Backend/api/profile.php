<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

function jsonResponse($data) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function buildImageUrl($path) {
    if (!$path) return "";
    if (preg_match('/^https?:\/\//i', $path)) return $path;
    return "http://localhost:9999/CareerNest/CareerNest_Backend/" . ltrim($path, '/');
}

function ensureUserAvatarDir() {
    $uploadDir = __DIR__ . '/../uploads/images/users/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
            throw new Exception("Không tạo được thư mục lưu avatar");
        }
    }
    return $uploadDir;
}

function deletePhysicalFile($relativePath) {
    $relativePath = trim((string)$relativePath);
    if ($relativePath === '') return;

    $fullPath = __DIR__ . '/../' . ltrim($relativePath, '/');
    if (file_exists($fullPath) && is_file($fullPath)) {
        @unlink($fullPath);
    }
}

function getUserProfile($conn, $userId) {
    $sql = "SELECT 
                u.UserID,
                u.FullName,
                u.Email,
                u.Role,
                u.Active,
                u.CreatedAt,
                i.url AS AvatarURL
            FROM Users u
            LEFT JOIN images i
                ON i.page = 'Người dùng'
                AND TRIM(i.position) = TRIM(CAST(u.UserID AS CHAR))
            WHERE u.UserID = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();
    if (!$row) {
        throw new Exception("Không tìm thấy người dùng");
    }

    $row["AvatarFullURL"] = $row["AvatarURL"] ? buildImageUrl($row["AvatarURL"]) : "";
    return $row;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $userId = intval($_GET['UserID'] ?? 0);

    if ($userId <= 0) {
        jsonResponse(["error" => "Thiếu UserID"]);
    }

    try {
        $row = getUserProfile($conn, $userId);
        jsonResponse($row);
    } catch (Exception $e) {
        jsonResponse(["error" => $e->getMessage()]);
    }
}

if ($method === 'POST') {
    $userId = intval($_POST['UserID'] ?? 0);
    $newFullName = trim($_POST['FullName'] ?? '');

    if ($userId <= 0) {
        jsonResponse(["error" => "Thiếu UserID"]);
    }

    if ($newFullName === '') {
        jsonResponse(["error" => "Họ tên không được để trống"]);
    }

    $stmt = $conn->prepare("SELECT UserID, FullName FROM Users WHERE UserID = ? LIMIT 1");
    if (!$stmt) {
        jsonResponse(["error" => $conn->error]);
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $oldUser = $result->fetch_assoc();

    if (!$oldUser) {
        jsonResponse(["error" => "Người dùng không tồn tại"]);
    }

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("UPDATE Users SET FullName = ? WHERE UserID = ?");
        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param("si", $newFullName, $userId);

        if (!$stmt->execute()) {
            throw new Exception("Không cập nhật được họ tên");
        }

        if (isset($_FILES['Avatar']) && $_FILES['Avatar']['error'] === 0) {
            $file = $_FILES['Avatar'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowed, true)) {
                throw new Exception("File ảnh không hợp lệ");
            }

            $uploadDir = ensureUserAvatarDir();

            $newName = time() . '_' . uniqid() . '.' . $ext;
            $fullPath = $uploadDir . $newName;
            $relativePath = 'uploads/images/users/' . $newName;

            if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
                throw new Exception("Upload ảnh thất bại");
            }

            $position = strval($userId);

            $stmt = $conn->prepare("SELECT id, url FROM images WHERE page = 'Người dùng' AND TRIM(position) = TRIM(?) LIMIT 1");
            if (!$stmt) {
                deletePhysicalFile($relativePath);
                throw new Exception($conn->error);
            }

            $stmt->bind_param("s", $position);
            $stmt->execute();
            $imgResult = $stmt->get_result();
            $oldImage = $imgResult->fetch_assoc();

            $desc = "Avatar người dùng";

            if ($oldImage) {
                $stmt = $conn->prepare("UPDATE images SET position = ?, url = ?, description = ? WHERE id = ?");
                if (!$stmt) {
                    deletePhysicalFile($relativePath);
                    throw new Exception($conn->error);
                }

                $stmt->bind_param("sssi", $position, $relativePath, $desc, $oldImage['id']);

                if (!$stmt->execute()) {
                    deletePhysicalFile($relativePath);
                    throw new Exception("Không cập nhật được ảnh đại diện");
                }

                if (!empty($oldImage['url']) && $oldImage['url'] !== $relativePath) {
                    deletePhysicalFile($oldImage['url']);
                }
            } else {
                $stmt = $conn->prepare("INSERT INTO images (page, position, url, description) VALUES ('Người dùng', ?, ?, ?)");
                if (!$stmt) {
                    deletePhysicalFile($relativePath);
                    throw new Exception($conn->error);
                }

                $stmt->bind_param("sss", $position, $relativePath, $desc);

                if (!$stmt->execute()) {
                    deletePhysicalFile($relativePath);
                    throw new Exception("Không thêm được ảnh đại diện");
                }
            }
        }

        $conn->commit();

        $row = getUserProfile($conn, $userId);

        jsonResponse([
            "message" => "Cập nhật hồ sơ thành công",
            "user" => $row
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        jsonResponse(["error" => $e->getMessage()]);
    }
}

jsonResponse(["error" => "Phương thức không hợp lệ"]);
?>