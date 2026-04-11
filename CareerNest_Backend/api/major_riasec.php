<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

require_once __DIR__ . "/../config/database.php";
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT mr.*, m.CareerCode, m.CareerName
                FROM major_riasec mr
                LEFT JOIN majors m ON mr.CareerID = m.CareerID
                ORDER BY mr.MajorRiasecID DESC";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        $careerId = intval($input['CareerID'] ?? 0);
        $riasec = $conn->real_escape_string($input['RiasecCode'] ?? 'R');
        $weight = intval($input['Weight'] ?? 1);

        $sql = "INSERT INTO major_riasec (CareerID, RiasecCode, Weight)
                VALUES ($careerId, '$riasec', $weight)";

        echo json_encode($conn->query($sql)
            ? ["message" => "Thêm mapping thành công"]
            : ["error" => $conn->error]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = intval($input['MajorRiasecID'] ?? 0);
        $careerId = intval($input['CareerID'] ?? 0);
        $riasec = $conn->real_escape_string($input['RiasecCode'] ?? 'R');
        $weight = intval($input['Weight'] ?? 1);

        $sql = "UPDATE major_riasec
                SET CareerID=$careerId, RiasecCode='$riasec', Weight=$weight
                WHERE MajorRiasecID=$id";

        echo json_encode($conn->query($sql)
            ? ["message" => "Cập nhật mapping thành công"]
            : ["error" => $conn->error]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $input);
        $id = intval($input['MajorRiasecID'] ?? 0);

        echo json_encode($conn->query("DELETE FROM major_riasec WHERE MajorRiasecID=$id")
            ? ["message" => "Xóa mapping thành công"]
            : ["error" => $conn->error]);
        break;
}
?>