<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

$rawInput = file_get_contents("php://input");
$jsonInput = json_decode($rawInput, true);
$method = $_SERVER['REQUEST_METHOD'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && strtoupper($_POST['_method']) === 'PUT') {
    $method = 'PUT';
}

function jsonResponse($data)
{
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function makeSlug($text)
{
    $text = trim($text);
    $text = mb_strtolower($text, 'UTF-8');

    $map = [
        'à' => 'a', 'á' => 'a', 'ạ' => 'a', 'ả' => 'a', 'ã' => 'a',
        'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ậ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a',
        'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ặ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a',
        'è' => 'e', 'é' => 'e', 'ẹ' => 'e', 'ẻ' => 'e', 'ẽ' => 'e',
        'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ệ' => 'e', 'ể' => 'e', 'ễ' => 'e',
        'ì' => 'i', 'í' => 'i', 'ị' => 'i', 'ỉ' => 'i', 'ĩ' => 'i',
        'ò' => 'o', 'ó' => 'o', 'ọ' => 'o', 'ỏ' => 'o', 'õ' => 'o',
        'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ộ' => 'o', 'ổ' => 'o', 'ỗ' => 'o',
        'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ợ' => 'o', 'ở' => 'o', 'ỡ' => 'o',
        'ù' => 'u', 'ú' => 'u', 'ụ' => 'u', 'ủ' => 'u', 'ũ' => 'u',
        'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ự' => 'u', 'ử' => 'u', 'ữ' => 'u',
        'ỳ' => 'y', 'ý' => 'y', 'ỵ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y',
        'đ' => 'd'
    ];

    $text = strtr($text, $map);
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
    $text = trim($text, '-');

    return $text ?: 'blog';
}

function getValidAuthorValue($conn, $authorId)
{
    $authorId = (int)$authorId;
    if ($authorId <= 0) return null;

    $stmt = $conn->prepare("SELECT UserID FROM Users WHERE UserID = ? LIMIT 1");
    $stmt->bind_param("i", $authorId);
    $stmt->execute();
    $result = $stmt->get_result();

    return ($result && $result->num_rows > 0) ? $authorId : null;
}

function uploadBlogImage($fileInputName = 'Thumbnail')
{
    if (empty($_FILES[$fileInputName]['name'])) {
        return ['success' => true, 'path' => ''];
    }

    $uploadDir = __DIR__ . "/../uploads/blogs/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($ext, $allowed)) {
        return ['success' => false, 'error' => 'File ảnh không hợp lệ'];
    }

    $fileName = time() . "_" . uniqid() . "." . $ext;
    $targetPath = $uploadDir . $fileName;
    $relativePath = "uploads/blogs/" . $fileName;

    if (!move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetPath)) {
        return ['success' => false, 'error' => 'Upload ảnh thất bại'];
    }

    return ['success' => true, 'path' => $relativePath];
}

function getInputValue($key, $default = '')
{
    global $jsonInput;

    if (isset($_POST[$key])) return $_POST[$key];
    if (is_array($jsonInput) && array_key_exists($key, $jsonInput)) return $jsonInput[$key];

    return $default;
}

function buildImageUrl($path)
{
    if (!$path) return '';
    if (preg_match('/^https?:\/\//i', $path)) return $path;
    return "http://localhost:9999/CareerNest/CareerNest_Backend/" . ltrim($path, '/');
}

function sanitizeBlogHtml($html)
{
    if (!is_string($html)) return '';

    $html = preg_replace('#<script\b[^>]*>(.*?)</script>#is', '', $html);
    $html = preg_replace('#<style\b[^>]*>(.*?)</style>#is', '', $html);
    $html = preg_replace('#\son\w+="[^"]*"#i', '', $html);
    $html = preg_replace("#\son\w+='[^']*'#i", '', $html);
    $html = preg_replace('#javascript:#i', '', $html);
    $html = preg_replace('#<o:p>\s*</o:p>#i', '', $html);
    $html = preg_replace('#<o:p>(.*?)</o:p>#i', '$1', $html);
    $html = preg_replace('#\s*mso-[^:]+:[^;"]+;?#i', '', $html);
    $html = preg_replace('#\s*class=("|\')(Mso|WordSection)[^"\']*\1#i', '', $html);

    return trim($html);
}

function getValidCategory($category)
{
    $category = trim($category);
    $allowed = ['Kỹ năng', 'Tâm lý', 'Xu hướng', 'Định hướng', 'Học tập', 'Du học'];

    if (!in_array($category, $allowed)) {
        return 'Định hướng';
    }

    return $category;
}

switch ($method) {
    case 'GET':
        $search = trim($_GET['search'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $category = trim($_GET['category'] ?? '');
        $slug = trim($_GET['slug'] ?? '');
        $id = intval($_GET['BlogID'] ?? 0);
        $popular = intval($_GET['popular'] ?? 0);
        $limit = intval($_GET['limit'] ?? 0);
        $increaseView = intval($_GET['increaseView'] ?? 0);

        if ($increaseView === 1 && ($id > 0 || $slug !== '')) {
            if ($id > 0) {
                $stmtView = $conn->prepare("UPDATE Blogs SET Views = IFNULL(Views, 0) + 1 WHERE BlogID = ?");
                $stmtView->bind_param("i", $id);
                $stmtView->execute();
            } else {
                $stmtView = $conn->prepare("UPDATE Blogs SET Views = IFNULL(Views, 0) + 1 WHERE Slug = ?");
                $stmtView->bind_param("s", $slug);
                $stmtView->execute();
            }
        }

        $sql = "SELECT 
                    b.BlogID,
                    b.Title,
                    b.Slug,
                    b.Summary,
                    b.Content,
                    b.ThumbnailURL,
                    b.AuthorID,
                    b.Status,
                    b.Category,
                    IFNULL(b.Views, 0) AS Views,
                    b.CreatedAt,
                    b.UpdatedAt,
                    u.FullName AS AuthorName
                FROM Blogs b
                LEFT JOIN Users u ON b.AuthorID = u.UserID
                WHERE 1=1";

        if ($id > 0) {
            $sql .= " AND b.BlogID = " . $id;
        }

        if ($slug !== '') {
            $slugEsc = $conn->real_escape_string($slug);
            $sql .= " AND b.Slug = '$slugEsc'";
        }

        if ($search !== '') {
            $searchEsc = $conn->real_escape_string($search);
            $sql .= " AND (
                b.Title LIKE '%$searchEsc%'
                OR b.Summary LIKE '%$searchEsc%'
                OR b.Content LIKE '%$searchEsc%'
                OR b.Category LIKE '%$searchEsc%'
                OR u.FullName LIKE '%$searchEsc%'
            )";
        }

        if ($status !== '') {
            $statusEsc = $conn->real_escape_string($status);
            $sql .= " AND b.Status = '$statusEsc'";
        }

        if ($category !== '') {
            $categoryEsc = $conn->real_escape_string($category);
            $sql .= " AND b.Category = '$categoryEsc'";
        }

        if ($popular === 1) {
            $sql .= " ORDER BY IFNULL(b.Views, 0) DESC, b.BlogID DESC";
        } else {
            $sql .= " ORDER BY b.BlogID DESC";
        }

        if ($limit > 0) {
            $sql .= " LIMIT " . $limit;
        }

        $result = $conn->query($sql);

        if (!$result) {
            jsonResponse(["error" => $conn->error]);
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $row['ThumbnailFullURL'] = buildImageUrl($row['ThumbnailURL']);
            $data[] = $row;
        }

        if ($id > 0 || $slug !== '') {
            jsonResponse($data ? $data[0] : null);
        }

        jsonResponse($data);
        break;

    case 'POST':
        $title = trim(getInputValue('Title', ''));
        $summary = trim(getInputValue('Summary', ''));
        $content = getInputValue('Content', '');
        if ($content === '') {
            $content = getInputValue('ContentHTML', '');
        }
        $content = sanitizeBlogHtml($content);

        $authorId = intval(getInputValue('AuthorID', 0));
        $status = trim(getInputValue('Status', 'Bản nháp'));
        $category = getValidCategory(getInputValue('Category', 'Định hướng'));
        $thumbnailFromInput = trim(getInputValue('ThumbnailURL', ''));

        if ($title === '' || $content === '') {
            jsonResponse(["error" => "Vui lòng nhập tiêu đề và nội dung"]);
        }

        if (!in_array($status, ['Bản nháp', 'Đã đăng', 'Ẩn'])) {
            $status = 'Bản nháp';
        }

        $thumbnail = $thumbnailFromInput;
        if (!empty($_FILES['Thumbnail']['name'])) {
            $upload = uploadBlogImage('Thumbnail');
            if (!$upload['success']) {
                jsonResponse(["error" => $upload['error']]);
            }
            $thumbnail = $upload['path'];
        }

        $slug = makeSlug($title);
        $slugCheckStmt = $conn->prepare("SELECT BlogID FROM Blogs WHERE Slug = ?");
        $slugCheckStmt->bind_param("s", $slug);
        $slugCheckStmt->execute();
        $slugCheckResult = $slugCheckStmt->get_result();
        if ($slugCheckResult && $slugCheckResult->num_rows > 0) {
            $slug .= '-' . time();
        }

        $authorValue = getValidAuthorValue($conn, $authorId);

        $stmt = $conn->prepare("
            INSERT INTO Blogs (Title, Slug, Summary, Content, ThumbnailURL, AuthorID, Status, Category)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssssiss",
            $title,
            $slug,
            $summary,
            $content,
            $thumbnail,
            $authorValue,
            $status,
            $category
        );

        if ($stmt->execute()) {
            jsonResponse([
                "message" => "Thêm bài viết thành công",
                "BlogID" => $conn->insert_id,
                "Slug" => $slug
            ]);
        }

        jsonResponse(["error" => $stmt->error ?: $conn->error]);
        break;

    case 'PUT':
        $blogId = intval(getInputValue('BlogID', 0));
        $title = trim(getInputValue('Title', ''));
        $summary = trim(getInputValue('Summary', ''));
        $content = getInputValue('Content', '');
        if ($content === '') {
            $content = getInputValue('ContentHTML', '');
        }
        $content = sanitizeBlogHtml($content);

        $authorId = intval(getInputValue('AuthorID', 0));
        $status = trim(getInputValue('Status', 'Bản nháp'));
        $category = getValidCategory(getInputValue('Category', 'Định hướng'));
        $thumbnailFromInput = trim(getInputValue('ThumbnailURL', ''));

        if ($blogId <= 0) {
            jsonResponse(["error" => "Thiếu BlogID"]);
        }

        if ($title === '' || $content === '') {
            jsonResponse(["error" => "Vui lòng nhập tiêu đề và nội dung"]);
        }

        if (!in_array($status, ['Bản nháp', 'Đã đăng', 'Ẩn'])) {
            $status = 'Bản nháp';
        }

        $oldStmt = $conn->prepare("SELECT ThumbnailURL FROM Blogs WHERE BlogID = ? LIMIT 1");
        $oldStmt->bind_param("i", $blogId);
        $oldStmt->execute();
        $oldResult = $oldStmt->get_result();
        $oldBlog = $oldResult ? $oldResult->fetch_assoc() : null;
        if (!$oldBlog) {
            jsonResponse(["error" => "Không tìm thấy bài viết"]);
        }
        $oldThumbnail = $oldBlog['ThumbnailURL'] ?? '';

        $slug = makeSlug($title);
        $slugCheckStmt = $conn->prepare("SELECT BlogID FROM Blogs WHERE Slug = ? AND BlogID <> ?");
        $slugCheckStmt->bind_param("si", $slug, $blogId);
        $slugCheckStmt->execute();
        $slugCheckResult = $slugCheckStmt->get_result();
        if ($slugCheckResult && $slugCheckResult->num_rows > 0) {
            $slug .= '-' . time();
        }

        $authorValue = getValidAuthorValue($conn, $authorId);
        $thumbnail = $oldThumbnail;

        if (!empty($_FILES['Thumbnail']['name'])) {
            $upload = uploadBlogImage('Thumbnail');
            if (!$upload['success']) {
                jsonResponse(["error" => $upload['error']]);
            }
            $thumbnail = $upload['path'];

            if (!empty($oldThumbnail)) {
                $oldFilePath = __DIR__ . "/../" . $oldThumbnail;
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }
        } elseif ($thumbnailFromInput !== '') {
            $thumbnail = $thumbnailFromInput;
        }

        $stmt = $conn->prepare("
            UPDATE Blogs
            SET Title = ?, Slug = ?, Summary = ?, Content = ?, ThumbnailURL = ?, AuthorID = ?, Status = ?, Category = ?
            WHERE BlogID = ?
        ");
        $stmt->bind_param(
            "sssssissi",
            $title,
            $slug,
            $summary,
            $content,
            $thumbnail,
            $authorValue,
            $status,
            $category,
            $blogId
        );

        if ($stmt->execute()) {
            jsonResponse([
                "message" => "Cập nhật bài viết thành công",
                "Slug" => $slug
            ]);
        }

        jsonResponse(["error" => $stmt->error ?: $conn->error]);
        break;

    case 'DELETE':
        parse_str($rawInput, $input);
        $blogId = intval($input['BlogID'] ?? 0);

        if ($blogId <= 0) {
            jsonResponse(["error" => "Thiếu BlogID"]);
        }

        $oldStmt = $conn->prepare("SELECT ThumbnailURL FROM Blogs WHERE BlogID = ? LIMIT 1");
        $oldStmt->bind_param("i", $blogId);
        $oldStmt->execute();
        $oldResult = $oldStmt->get_result();
        $oldBlog = $oldResult ? $oldResult->fetch_assoc() : null;
        $oldThumbnail = $oldBlog['ThumbnailURL'] ?? '';

        $stmt = $conn->prepare("DELETE FROM Blogs WHERE BlogID = ?");
        $stmt->bind_param("i", $blogId);

        if ($stmt->execute()) {
            if (!empty($oldThumbnail)) {
                $oldFilePath = __DIR__ . "/../" . $oldThumbnail;
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }

            jsonResponse(["message" => "Xóa bài viết thành công"]);
        }

        jsonResponse(["error" => $stmt->error ?: $conn->error]);
        break;

    default:
        jsonResponse(["error" => "Method không hỗ trợ"]);
        break;
}