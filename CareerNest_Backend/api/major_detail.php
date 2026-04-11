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

    // ================= GET =================
    case 'GET':
        $careerId = intval($_GET['CareerID'] ?? 0);
        $adminMode = intval($_GET['admin'] ?? 0);

        // Lấy 1 bài viết chi tiết
        if ($careerId > 0) {
            if ($adminMode !== 1) {
                $conn->query("UPDATE Majors SET Views = IFNULL(Views, 0) + 1 WHERE CareerID = $careerId");
            }

            $sql = "SELECT
                        m.CareerID,
                        m.CareerCode,
                        m.CareerName,
                        m.Category,
                        m.Description,
                        m.Highlight,
                        m.DemandLevel,
                        m.SalaryMin,
                        m.SalaryMax,
                        m.Views,
                        m.Rating,
                        m.RatingCount,
                        i.url AS ImageURL,

                        d.DetailID,
                        d.Overview,
                        d.Skills,
                        d.Roadmap,
                        d.Opportunities,
                        d.SalaryDetail,
                        d.SuitableFor,
                        d.Subjects,
                        d.Universities,
                        d.DegreeOptions,
                        d.Certifications,
                        d.Tools,
                        d.Pros,
                        d.Cons,
                        d.Trends,
                        d.CreatedAt,
                        d.UpdatedAt
                    FROM Majors m
                    LEFT JOIN MajorDetails d
                        ON m.CareerID = d.CareerID
                    LEFT JOIN images i
                        ON i.page = 'Ngành học'
                        AND LOWER(TRIM(i.position)) = LOWER(TRIM(m.CareerName))
                    WHERE m.CareerID = $careerId
                    LIMIT 1";

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

        // Danh sách cho admin
        $sql = "SELECT
                    m.CareerID,
                    m.CareerCode,
                    m.CareerName,
                    m.Category,
                    m.Views,
                    d.DetailID,
                    d.Overview,
                    d.Skills,
                    d.Roadmap,
                    d.Opportunities,
                    d.UpdatedAt,
                    CASE
                        WHEN d.DetailID IS NULL THEN 'Chưa có bài viết'
                        ELSE 'Đã có bài viết'
                    END AS DetailStatus
                FROM Majors m
                LEFT JOIN MajorDetails d
                    ON m.CareerID = d.CareerID
                ORDER BY m.CareerID DESC";

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

    // ================= POST =================
    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        $careerId       = intval($input['CareerID'] ?? 0);
        $overview       = $conn->real_escape_string($input['Overview'] ?? '');
        $skills         = $conn->real_escape_string($input['Skills'] ?? '');
        $roadmap        = $conn->real_escape_string($input['Roadmap'] ?? '');
        $opportunities  = $conn->real_escape_string($input['Opportunities'] ?? '');
        $salaryDetail   = $conn->real_escape_string($input['SalaryDetail'] ?? '');
        $suitableFor    = $conn->real_escape_string($input['SuitableFor'] ?? '');
        $subjects       = $conn->real_escape_string($input['Subjects'] ?? '');
        $universities   = $conn->real_escape_string($input['Universities'] ?? '');
        $degreeOptions  = $conn->real_escape_string($input['DegreeOptions'] ?? '');
        $certifications = $conn->real_escape_string($input['Certifications'] ?? '');
        $tools          = $conn->real_escape_string($input['Tools'] ?? '');
        $pros           = $conn->real_escape_string($input['Pros'] ?? '');
        $cons           = $conn->real_escape_string($input['Cons'] ?? '');
        $trends         = $conn->real_escape_string($input['Trends'] ?? '');

        if ($careerId <= 0) {
            echo json_encode(["error" => "Thiếu CareerID"]);
            exit;
        }

        $check = $conn->query("SELECT DetailID FROM MajorDetails WHERE CareerID = $careerId LIMIT 1");
        if ($check && $check->num_rows > 0) {
            echo json_encode(["error" => "Ngành này đã có bài viết chi tiết. Hãy dùng sửa."]);
            exit;
        }

        $sql = "INSERT INTO MajorDetails
                    (CareerID, Overview, Skills, Roadmap, Opportunities, SalaryDetail, SuitableFor, Subjects, Universities, DegreeOptions, Certifications, Tools, Pros, Cons, Trends)
                VALUES
                    ($careerId, '$overview', '$skills', '$roadmap', '$opportunities', '$salaryDetail', '$suitableFor', '$subjects', '$universities', '$degreeOptions', '$certifications', '$tools', '$pros', '$cons', '$trends')";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Thêm bài viết chi tiết thành công"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    // ================= PUT =================
    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $careerId       = intval($input['CareerID'] ?? 0);
        $overview       = $conn->real_escape_string($input['Overview'] ?? '');
        $skills         = $conn->real_escape_string($input['Skills'] ?? '');
        $roadmap        = $conn->real_escape_string($input['Roadmap'] ?? '');
        $opportunities  = $conn->real_escape_string($input['Opportunities'] ?? '');
        $salaryDetail   = $conn->real_escape_string($input['SalaryDetail'] ?? '');
        $suitableFor    = $conn->real_escape_string($input['SuitableFor'] ?? '');
        $subjects       = $conn->real_escape_string($input['Subjects'] ?? '');
        $universities   = $conn->real_escape_string($input['Universities'] ?? '');
        $degreeOptions  = $conn->real_escape_string($input['DegreeOptions'] ?? '');
        $certifications = $conn->real_escape_string($input['Certifications'] ?? '');
        $tools          = $conn->real_escape_string($input['Tools'] ?? '');
        $pros           = $conn->real_escape_string($input['Pros'] ?? '');
        $cons           = $conn->real_escape_string($input['Cons'] ?? '');
        $trends         = $conn->real_escape_string($input['Trends'] ?? '');

        if ($careerId <= 0) {
            echo json_encode(["error" => "Thiếu CareerID"]);
            exit;
        }

        $check = $conn->query("SELECT DetailID FROM MajorDetails WHERE CareerID = $careerId LIMIT 1");

        if ($check && $check->num_rows > 0) {
            $sql = "UPDATE MajorDetails
                    SET Overview='$overview',
                        Skills='$skills',
                        Roadmap='$roadmap',
                        Opportunities='$opportunities',
                        SalaryDetail='$salaryDetail',
                        SuitableFor='$suitableFor',
                        Subjects='$subjects',
                        Universities='$universities',
                        DegreeOptions='$degreeOptions',
                        Certifications='$certifications',
                        Tools='$tools',
                        Pros='$pros',
                        Cons='$cons',
                        Trends='$trends'
                    WHERE CareerID=$careerId";
        } else {
            $sql = "INSERT INTO MajorDetails
                        (CareerID, Overview, Skills, Roadmap, Opportunities, SalaryDetail, SuitableFor, Subjects, Universities, DegreeOptions, Certifications, Tools, Pros, Cons, Trends)
                    VALUES
                        ($careerId, '$overview', '$skills', '$roadmap', '$opportunities', '$salaryDetail', '$suitableFor', '$subjects', '$universities', '$degreeOptions', '$certifications', '$tools', '$pros', '$cons', '$trends')";
        }

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Lưu bài viết chi tiết thành công"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    // ================= DELETE =================
    case 'DELETE':
        parse_str(file_get_contents("php://input"), $input);
        $careerId = intval($input['CareerID'] ?? 0);

        if ($careerId <= 0) {
            echo json_encode(["error" => "Thiếu CareerID"]);
            exit;
        }

        $sql = "DELETE FROM MajorDetails WHERE CareerID = $careerId";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Xóa bài viết chi tiết thành công"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;
}
?>