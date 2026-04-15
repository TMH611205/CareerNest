<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";

header("Content-Type: application/json; charset=UTF-8");

$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

function jsonResponse($data) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function getImageSubFolder($page) {
    $page = trim((string)$page);

    if ($page === 'Người dùng') {
        return 'users';
    }

    if ($page === 'Ngành học') {
        return 'majors';
    }

    if ($page === 'Blog' || $page === 'Blogs' || $page === 'Bài viết') {
        return 'blogs';
    }

    if (
        $page === 'Trang chủ' ||
        $page === 'Site' ||
        $page === 'Website' ||
        $page === 'Hệ thống'
    ) {
        return 'site';
    }

    return 'others';
}

function ensureUploadDir($subFolder) {
    $uploadDir = __DIR__ . '/../uploads/images/' . $subFolder . '/';

    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
            return false;
        }
    }

    return $uploadDir;
}

function isValidImageFile($file) {
    if (!isset($file['name'])) {
        return [false, "Không tìm thấy file ảnh"];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($ext, $allowed, true)) {
        return [false, "File ảnh không hợp lệ. Chỉ chấp nhận jpg, jpeg, png, gif, webp"];
    }

    return [true, $ext];
}

function uploadImageFile($file, $page) {
    list($valid, $result) = isValidImageFile($file);
    if (!$valid) {
        return [false, $result];
    }

    $ext = $result;
    $subFolder = getImageSubFolder($page);
    $uploadDir = ensureUploadDir($subFolder);

    if ($uploadDir === false) {
        return [false, "Không tạo được thư mục upload"];
    }

    $newName = time() . '_' . uniqid() . '.' . $ext;
    $fullPath = $uploadDir . $newName;
    $relativePath = 'uploads/images/' . $subFolder . '/' . $newName;

    if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
        return [false, "Upload ảnh thất bại"];
    }

    return [true, $relativePath];
}

function deletePhysicalFile($relativePath) {
    $relativePath = trim((string)$relativePath);
    if ($relativePath === '') {
        return;
    }

    $filePath = __DIR__ . '/../' . ltrim($relativePath, '/');
    if (file_exists($filePath) && is_file($filePath)) {
        @unlink($filePath);
    }
}

switch ($method) {

    case 'GET':
        $result = $conn->query("SELECT id, page, position, url, description FROM images ORDER BY id DESC");

        if (!$result) {
            jsonResponse(["error" => $conn->error]);
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        jsonResponse($data);
        break;

    case 'POST':
        $page = trim($_POST['page'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($page === '' || $position === '') {
            jsonResponse(["error" => "Vui lòng nhập trang và vị trí"]);
        }

        if (!isset($_FILES['image']) || (int)$_FILES['image']['error'] !== 0) {
            jsonResponse(["error" => "Vui lòng chọn ảnh"]);
        }

        list($success, $uploadResult) = uploadImageFile($_FILES['image'], $page);
        if (!$success) {
            jsonResponse(["error" => $uploadResult]);
        }

        $relativePath = $uploadResult;

        $stmt = $conn->prepare("
            INSERT INTO images (page, position, url, description)
            VALUES (?, ?, ?, ?)
        ");

        if (!$stmt) {
            deletePhysicalFile($relativePath);
            jsonResponse(["error" => $conn->error]);
        }

        $stmt->bind_param("ssss", $page, $position, $relativePath, $description);

        if ($stmt->execute()) {
            jsonResponse([
                "message" => "Thêm ảnh thành công",
                "id" => $conn->insert_id,
                "url" => $relativePath
            ]);
        }

        deletePhysicalFile($relativePath);
        jsonResponse(["error" => $stmt->error ?: $conn->error]);
        break;

    case 'PUT':
        $id = (int)($_POST['id'] ?? 0);
        $page = trim($_POST['page'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($id <= 0) {
            jsonResponse(["error" => "Thiếu id ảnh"]);
        }

        if ($page === '' || $position === '') {
            jsonResponse(["error" => "Vui lòng nhập trang và vị trí"]);
        }

        $stmt = $conn->prepare("SELECT id, page, position, url, description FROM images WHERE id = ? LIMIT 1");
        if (!$stmt) {
            jsonResponse(["error" => $conn->error]);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $old = $result->fetch_assoc();

        if (!$old) {
            jsonResponse(["error" => "Không tìm thấy ảnh"]);
        }

        $relativePath = $old['url'];
        $newUploaded = false;

        if (isset($_FILES['image']) && (int)$_FILES['image']['error'] === 0) {
            list($success, $uploadResult) = uploadImageFile($_FILES['image'], $page);
            if (!$success) {
                jsonResponse(["error" => $uploadResult]);
            }

            $relativePath = $uploadResult;
            $newUploaded = true;
        }

        $stmt = $conn->prepare("
            UPDATE images
            SET page = ?, position = ?, url = ?, description = ?
            WHERE id = ?
        ");

        if (!$stmt) {
            if ($newUploaded) {
                deletePhysicalFile($relativePath);
            }
            jsonResponse(["error" => $conn->error]);
        }

        $stmt->bind_param("ssssi", $page, $position, $relativePath, $description, $id);

        if ($stmt->execute()) {
            if ($newUploaded && !empty($old['url']) && $old['url'] !== $relativePath) {
                deletePhysicalFile($old['url']);
            }

            jsonResponse([
                "message" => "Cập nhật ảnh thành công",
                "url" => $relativePath
            ]);
        }

        if ($newUploaded) {
            deletePhysicalFile($relativePath);
        }

        jsonResponse(["error" => $stmt->error ?: $conn->error]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $input);
        $id = (int)($input['id'] ?? 0);

        if ($id <= 0) {
            jsonResponse(["error" => "Thiếu id"]);
        }

        $stmt = $conn->prepare("SELECT id, url FROM images WHERE id = ? LIMIT 1");
        if (!$stmt) {
            jsonResponse(["error" => $conn->error]);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            jsonResponse(["error" => "Không tìm thấy ảnh"]);
        }

        $stmt = $conn->prepare("DELETE FROM images WHERE id = ?");
        if (!$stmt) {
            jsonResponse(["error" => $conn->error]);
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            deletePhysicalFile($row['url']);
            jsonResponse(["message" => "Xóa ảnh thành công"]);
        }

        jsonResponse(["error" => $stmt->error ?: $conn->error]);
        break;

    default:
        jsonResponse(["error" => "Phương thức không hợp lệ"]);
        break;
}
?>