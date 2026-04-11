<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
require_once __DIR__ . "/../config/database.php";

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {


case 'GET':
    $result = $conn->query("SELECT 
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
            AND LOWER(TRIM(i.position)) = LOWER(TRIM(u.FullName))
    ");

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
    break;
    //  thêm user
    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        $name = $input['FullName'];
        $email = $input['Email'];
        $password = password_hash($input['Password'], PASSWORD_DEFAULT);
        $role = $input['Role'];

        $sql = "INSERT INTO Users (FullName, Email, PasswordHash, Role)
                VALUES ('$name', '$email', '$password', '$role')";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Thêm thành công"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    //  sửa user
    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = $input['UserID'];
        $name = $input['FullName'];
        $email = $input['Email'];
        $role = $input['Role'];

        // nếu có nhập password mới
        if (!empty($input['Password'])) {
            $password = password_hash($input['Password'], PASSWORD_DEFAULT);
            $sql = "UPDATE Users 
                    SET FullName='$name', Email='$email', Role='$role', PasswordHash='$password'
                    WHERE UserID=$id";
        } else {
            $sql = "UPDATE Users 
                    SET FullName='$name', Email='$email', Role='$role'
                    WHERE UserID=$id";
        }

        $conn->query($sql);
        echo json_encode(["message" => "Cập nhật thành công"]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $input);
        $id = $input['UserID'];

        $conn->query("DELETE FROM Users WHERE UserID=$id");

        echo json_encode(["message" => "Xóa thành công"]);
        break;
}
