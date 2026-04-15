<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

function jsonResponse($data, $statusCode = 200)
{
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function getBaseUrl()
{
    $isHttps = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
    );

    $scheme = $isHttps ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:9999';

    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $apiPos = strpos($scriptName, '/api/');
    if ($apiPos !== false) {
        $basePath = substr($scriptName, 0, $apiPos);
    } else {
        $basePath = rtrim(dirname(dirname($scriptName)), '/\\');
    }

    return $scheme . '://' . $host . rtrim($basePath, '/') . '/';
}

function buildLogoUrl($path)
{
    if (!$path) return '';
    if (preg_match('/^https?:\/\//i', $path)) return $path;
    return getBaseUrl() . ltrim($path, '/');
}

function ensureUploadDir($dir)
{
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT * FROM settings ORDER BY SettingID ASC LIMIT 1";
    $result = $conn->query($sql);

    if (!$result) {
        jsonResponse(["error" => $conn->error], 500);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $row['LogoFullURL'] = buildLogoUrl($row['LogoURL'] ?? '');
        $row['BaseURL'] = getBaseUrl();
        jsonResponse($row);
    }

    jsonResponse([
        "SettingID" => 0,
        "SiteName" => "CareerNest",
        "ContactEmail" => "contact@careernest.vn",
        "ContactPhone" => "+84 (028) 1234 5678",
        "Address" => "123 Đường ABC, Quận XYZ, TP. HCM",
        "LogoURL" => null,
        "LogoFullURL" => "",
        "MaintenanceMode" => 0,
        "SiteDescription" => "Nền tảng định hướng nghề nghiệp thông minh cho học sinh sinh viên.",
        "BaseURL" => getBaseUrl()
    ]);
}

if ($method === 'POST') {
    $siteName = trim($_POST['SiteName'] ?? 'CareerNest');
    $contactEmail = trim($_POST['ContactEmail'] ?? '');
    $contactPhone = trim($_POST['ContactPhone'] ?? '');
    $address = trim($_POST['Address'] ?? '');
    $maintenanceMode = !empty($_POST['MaintenanceMode']) ? 1 : 0;
    $siteDescription = trim($_POST['SiteDescription'] ?? '');

    $siteNameEsc = $conn->real_escape_string($siteName);
    $contactEmailEsc = $conn->real_escape_string($contactEmail);
    $contactPhoneEsc = $conn->real_escape_string($contactPhone);
    $addressEsc = $conn->real_escape_string($address);
    $siteDescriptionEsc = $conn->real_escape_string($siteDescription);

    $check = $conn->query("SELECT * FROM settings ORDER BY SettingID ASC LIMIT 1");
    if (!$check) {
        jsonResponse(["error" => $conn->error], 500);
    }

    $settingID = 0;
    $oldLogo = '';

    if ($check->num_rows > 0) {
        $oldRow = $check->fetch_assoc();
        $settingID = (int)$oldRow['SettingID'];
        $oldLogo = $oldRow['LogoURL'] ?? '';
    }

    $logoPath = $oldLogo;

    if (isset($_FILES['Logo']) && $_FILES['Logo']['error'] === 0) {
        $uploadDir = __DIR__ . '/../uploads/logo/';
        ensureUploadDir($uploadDir);

        $ext = strtolower(pathinfo($_FILES['Logo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            jsonResponse(["error" => "File logo không hợp lệ"], 400);
        }

        $fileName = 'logo_' . time() . '_' . uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $fileName;
        $relativePath = 'uploads/logo/' . $fileName;

        if (!move_uploaded_file($_FILES['Logo']['tmp_name'], $targetPath)) {
            jsonResponse(["error" => "Upload logo thất bại"], 500);
        }

        $logoPath = $relativePath;

        if (!empty($oldLogo)) {
            $oldFile = __DIR__ . '/../' . ltrim($oldLogo, '/');
            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }
    }

    $logoPathEsc = $conn->real_escape_string($logoPath);

    if ($settingID > 0) {
        $sql = "UPDATE settings SET
                    SiteName = '$siteNameEsc',
                    ContactEmail = '$contactEmailEsc',
                    ContactPhone = '$contactPhoneEsc',
                    Address = '$addressEsc',
                    LogoURL = " . ($logoPathEsc !== '' ? "'$logoPathEsc'" : "NULL") . ",
                    MaintenanceMode = $maintenanceMode,
                    SiteDescription = '$siteDescriptionEsc'
                WHERE SettingID = $settingID";

        if (!$conn->query($sql)) {
            jsonResponse(["error" => $conn->error], 500);
        }

        jsonResponse([
            "message" => "Cập nhật cài đặt thành công",
            "LogoURL" => $logoPath,
            "LogoFullURL" => buildLogoUrl($logoPath),
            "BaseURL" => getBaseUrl()
        ]);
    } else {
        $sql = "INSERT INTO settings (
                    SiteName,
                    ContactEmail,
                    ContactPhone,
                    Address,
                    LogoURL,
                    MaintenanceMode,
                    SiteDescription
                ) VALUES (
                    '$siteNameEsc',
                    '$contactEmailEsc',
                    '$contactPhoneEsc',
                    '$addressEsc',
                    " . ($logoPathEsc !== '' ? "'$logoPathEsc'" : "NULL") . ",
                    $maintenanceMode,
                    '$siteDescriptionEsc'
                )";

        if (!$conn->query($sql)) {
            jsonResponse(["error" => $conn->error], 500);
        }

        jsonResponse([
            "message" => "Lưu cài đặt thành công",
            "LogoURL" => $logoPath,
            "LogoFullURL" => buildLogoUrl($logoPath),
            "BaseURL" => getBaseUrl()
        ]);
    }
}

jsonResponse(["error" => "Phương thức không hỗ trợ"], 405);
?>