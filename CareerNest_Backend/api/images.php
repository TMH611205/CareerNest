<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";

header("Content-Type: application/json");

$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // lấy danh sách ảnh
    case 'GET':
        $result = $conn->query("SELECT id, page, position, url, description FROM images");

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
        break;

    // thêm ảnh
    case 'POST':
        $page = $_POST['page'] ?? '';
        $position = $_POST['position'] ?? '';
        $description = $_POST['description'] ?? '';

        if (empty($page) || empty($position)) {
            echo json_encode(["error" => "Vui lòng nhập trang và vị trí"]);
            exit;
        }

        if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
            echo json_encode(["error" => "Vui lòng chọn ảnh"]);
            exit;
        }

        $file = $_FILES['image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            echo json_encode(["error" => "File ảnh không hợp lệ"]);
            exit;
        }

        $uploadDir = __DIR__ . '/../uploads/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newName = time() . '_' . uniqid() . '.' . $ext;
        $fullPath = $uploadDir . $newName;
        $relativePath = 'uploads/images/' . $newName;

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            echo json_encode(["error" => "Upload ảnh thất bại"]);
            exit;
        }

        $sql = "INSERT INTO images (page, position, url, description)
                VALUES ('$page', '$position', '$relativePath', '$description')";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Thêm ảnh thành công"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    // sửa ảnh
    case 'PUT':
        $id = $_POST['id'] ?? 0;
        $page = $_POST['page'] ?? '';
        $position = $_POST['position'] ?? '';
        $description = $_POST['description'] ?? '';

        if (empty($id)) {
            echo json_encode(["error" => "Thiếu id ảnh"]);
            exit;
        }

        if (empty($page) || empty($position)) {
            echo json_encode(["error" => "Vui lòng nhập trang và vị trí"]);
            exit;
        }

        $result = $conn->query("SELECT * FROM images WHERE id = $id");
        $old = $result->fetch_assoc();

        if (!$old) {
            echo json_encode(["error" => "Không tìm thấy ảnh"]);
            exit;
        }

        $relativePath = $old['url'];

        // nếu có chọn ảnh mới thì upload ảnh mới
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $file = $_FILES['image'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowed)) {
                echo json_encode(["error" => "File ảnh không hợp lệ"]);
                exit;
            }

            $uploadDir = __DIR__ . '/../uploads/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newName = time() . '_' . uniqid() . '.' . $ext;
            $fullPath = $uploadDir . $newName;
            $relativePath = 'uploads/images/' . $newName;

            if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
                echo json_encode(["error" => "Upload ảnh mới thất bại"]);
                exit;
            }

            $oldFile = __DIR__ . '/../' . $old['url'];
            if (!empty($old['url']) && file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }

        $sql = "UPDATE images
                SET page='$page',
                    position='$position',
                    url='$relativePath',
                    description='$description'
                WHERE id=$id";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Cập nhật ảnh thành công"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    // xóa ảnh
    case 'DELETE':
        parse_str(file_get_contents("php://input"), $input);
        $id = $input['id'] ?? 0;

        if (empty($id)) {
            echo json_encode(["error" => "Thiếu id"]);
            exit;
        }

        $result = $conn->query("SELECT * FROM images WHERE id = $id");
        $row = $result->fetch_assoc();

        if (!$row) {
            echo json_encode(["error" => "Không tìm thấy ảnh"]);
            exit;
        }

        $filePath = __DIR__ . '/../' . $row['url'];
        if (!empty($row['url']) && file_exists($filePath)) {
            @unlink($filePath);
        }

        if ($conn->query("DELETE FROM images WHERE id=$id")) {
            echo json_encode(["message" => "Xóa ảnh thành công"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;
}