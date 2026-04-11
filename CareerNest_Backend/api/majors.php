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

switch ($method) {

    // GET - lấy danh sách ngành hoặc chi tiết 1 ngành
    case 'GET':
    $careerId = intval($_GET['CareerID'] ?? 0);

    if ($careerId > 0) {
        $conn->query("UPDATE Majors SET Views = IFNULL(Views, 0) + 1 WHERE CareerID = $careerId");

        $sql = "SELECT 
                m.*,
                md.Overview,
                md.Skills,
                md.Roadmap,
                md.Opportunities,
                md.SalaryDetail,
                md.SuitableFor,
                md.Subjects,
                md.Universities,
                md.DegreeOptions,
                i.url AS ImageURL
            FROM Majors m
            LEFT JOIN MajorDetails md ON md.CareerID = m.CareerID
            LEFT JOIN images i
                ON i.page = 'Ngành học'
                AND LOWER(TRIM(i.position)) = LOWER(TRIM(m.CareerName))
            WHERE m.CareerID = $careerId
            LIMIT 1
        ";

        $result = $conn->query($sql);

        if (!$result) {
            echo json_encode(["error" => $conn->error]);
            exit;
        }

        $row = $result->fetch_assoc();

        if (!$row) {
            echo json_encode(["error" => "Không tìm thấy ngành học"]);
            exit;
        }

        echo json_encode($row);
        exit;
    }

    $sql = "SELECT 
            m.*,
            md.Overview,
            md.Skills,
            md.Roadmap,
            md.Opportunities,
            md.SalaryDetail,
            md.SuitableFor,
            md.Subjects,
            md.Universities,
            md.DegreeOptions,
            i.url AS ImageURL
        FROM Majors m
        LEFT JOIN MajorDetails md ON md.CareerID = m.CareerID
        LEFT JOIN images i
            ON i.page = 'Ngành học'
            AND LOWER(TRIM(i.position)) = LOWER(TRIM(m.CareerName))
        ORDER BY m.CareerID DESC
    ";

    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode(["error" => $conn->error]);
        exit;
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
    break;

    // POST - thêm ngành
    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        $code = $conn->real_escape_string($input['CareerCode'] ?? '');
        $name = $conn->real_escape_string($input['CareerName'] ?? '');
        $category = $conn->real_escape_string($input['Category'] ?? '');
        $min = !empty($input['SalaryMin']) ? floatval($input['SalaryMin']) : 0;
        $max = !empty($input['SalaryMax']) ? floatval($input['SalaryMax']) : 0;
        $desc = $conn->real_escape_string($input['Description'] ?? '');
        $highlight = $conn->real_escape_string($input['Highlight'] ?? '');
        $demand = $conn->real_escape_string($input['DemandLevel'] ?? '');

        $sql = "INSERT INTO Majors 
            (CareerCode, CareerName, Category, SalaryMin, SalaryMax, Description, Highlight, DemandLevel)
            VALUES 
            ('$code', '$name', '$category', $min, $max, '$desc', '$highlight', '$demand')";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Thêm ngành thành công"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    // PUT - sửa ngành
    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = intval($input['CareerID'] ?? 0);
        $code = $conn->real_escape_string($input['CareerCode'] ?? '');
        $name = $conn->real_escape_string($input['CareerName'] ?? '');
        $category = $conn->real_escape_string($input['Category'] ?? '');
        $min = !empty($input['SalaryMin']) ? floatval($input['SalaryMin']) : 0;
        $max = !empty($input['SalaryMax']) ? floatval($input['SalaryMax']) : 0;
        $desc = $conn->real_escape_string($input['Description'] ?? '');
        $highlight = $conn->real_escape_string($input['Highlight'] ?? '');
        $demand = $conn->real_escape_string($input['DemandLevel'] ?? '');

        $sql = "UPDATE Majors 
                SET CareerCode='$code',
                    CareerName='$name',
                    Category='$category',
                    SalaryMin=$min,
                    SalaryMax=$max,
                    Description='$desc',
                    Highlight='$highlight',
                    DemandLevel='$demand'
                WHERE CareerID=$id";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Cập nhật thành công"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    // DELETE - xóa ngành
case 'DELETE':
    parse_str(file_get_contents("php://input"), $input);
    $id = intval($input['CareerID'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["error" => "Thiếu CareerID"]);
        exit;
    }

    // Lấy tên ngành trước khi xóa
    $result = $conn->query("SELECT CareerName FROM Majors WHERE CareerID = $id LIMIT 1");

    if (!$result) {
        echo json_encode(["error" => $conn->error]);
        exit;
    }

    $major = $result->fetch_assoc();

    if (!$major) {
        echo json_encode(["error" => "Không tìm thấy ngành"]);
        exit;
    }

    $careerName = $conn->real_escape_string($major['CareerName']);

    // Lấy các ảnh liên quan để xóa file vật lý
    $imgResult = $conn->query("
        SELECT id, url
        FROM images
        WHERE page = 'Ngành học'
          AND LOWER(TRIM(position)) = LOWER(TRIM('$careerName'))
    ");

    if ($imgResult) {
        while ($img = $imgResult->fetch_assoc()) {
            $filePath = __DIR__ . '/../' . $img['url'];
            if (!empty($img['url']) && file_exists($filePath)) {
                @unlink($filePath);
            }
        }
    }

    // Xóa bản ghi ảnh trong DB
    $deleteImagesSql = "
        DELETE FROM images
        WHERE page = 'Ngành học'
          AND LOWER(TRIM(position)) = LOWER(TRIM('$careerName'))
    ";

    if (!$conn->query($deleteImagesSql)) {
        echo json_encode(["error" => $conn->error]);
        exit;
    }

    // Nếu có bài viết chi tiết thì xóa luôn
    $conn->query("DELETE FROM MajorDetails WHERE CareerID = $id");

    // Xóa ngành
    $sql = "DELETE FROM Majors WHERE CareerID = $id";

    if ($conn->query($sql)) {
        echo json_encode(["message" => "Xóa ngành và dữ liệu liên quan thành công"]);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
    break;
}