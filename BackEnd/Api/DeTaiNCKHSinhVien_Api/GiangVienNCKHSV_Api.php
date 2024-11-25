<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHSinhVien/GiangVienNCKHSV.php';

$database = new Database();
$db = $database->getConn();
$giangVienNCKHSV = new GiangVienNCKHSV($db);

// Lấy phương thức HTTP và tham số `action`
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$action) {
    echo json_encode(["message" => "Yêu cầu không hợp lệ: thiếu tham số action"]);
    http_response_code(400);
    exit;
}

switch ($method) {
    case 'GET':
        if ($action === "get") {
            $result = $giangVienNCKHSV->readAll();
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $maNhom = isset($_GET['MaNhomNCKHSV']) ? $_GET['MaNhomNCKHSV'] : null;
            if (!$maNhom) {
                echo json_encode(["message" => "Thiếu mã nhóm nghiên cứu khoa học sinh viên"]);
                http_response_code(400);
                exit;
            }
            $giangVienNCKHSV->MaNhomNCKHSV = $maNhom;
            $data = $giangVienNCKHSV->readOne();
            echo json_encode($data);
        } else {
            echo json_encode(["message" => "Action không hợp lệ"]);
            http_response_code(400);
        }
        break;

    case 'POST':
        if ($action !== "post") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức POST"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['MaNhomNCKHSV'], $data['MaGV'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $giangVienNCKHSV->MaNhomNCKHSV = $data['MaNhomNCKHSV'];
        $giangVienNCKHSV->MaGV = $data['MaGV'];

        if ($giangVienNCKHSV->add()) {
            echo json_encode(["message" => "Dữ liệu được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm dữ liệu thất bại"]);
            http_response_code(500);
        }
        break;

    case 'PUT':
        if ($action !== "put") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['MaNhomNCKHSV'], $data['MaGV'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $giangVienNCKHSV->MaNhomNCKHSV = $data['MaNhomNCKHSV'];
        $giangVienNCKHSV->MaGV = $data['MaGV'];

        if ($giangVienNCKHSV->update()) {
            echo json_encode(["message" => "Dữ liệu được cập nhật thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật dữ liệu thất bại"]);
            http_response_code(500);
        }
        break;

    case 'DELETE':
        if ($action !== "delete") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức DELETE"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['MaNhomNCKHSV'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $giangVienNCKHSV->MaNhomNCKHSV = $data['MaNhomNCKHSV'];

        if ($giangVienNCKHSV->delete()) {
            echo json_encode(["message" => "Dữ liệu được xóa thành công"]);
        } else {
            echo json_encode(["message" => "Xóa dữ liệu thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
