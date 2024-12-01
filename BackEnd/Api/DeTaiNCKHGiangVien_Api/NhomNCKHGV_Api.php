<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHGiangVien/NhomNCKHGV.php';

$database = new Database();
$db = $database->getConn();
$nhom = new NhomNCKHGV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

switch ($action) {
    case 'GET':
        $data = $nhom->read();
        echo json_encode($data);
        break;

    case 'GET_ONE':
        if (!empty($_GET["MaNhomNCKHGV"])) {
            $nhom->MaNhomNCKHGV = $_GET["MaNhomNCKHGV"];
            $data = $nhom->readOne();
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(["message" => "Nhóm NCKH không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Tham số MaNhomNCKHGV không được cung cấp."]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->MaNhomNCKHGV) && !empty($data->MaDeTaiNCKHGV)) {
            $nhom->MaNhomNCKHGV = $data->MaNhomNCKHGV;
            $nhom->MaDeTaiNCKHGV = $data->MaDeTaiNCKHGV;

            if ($nhom->create()) {
                echo json_encode(["message" => "Nhóm NCKH được tạo thành công."]);
            } else {
                echo json_encode(["message" => "Không thể tạo nhóm NCKH."]);
            }
        } else {
            echo json_encode(["message" => "Dữ liệu không hợp lệ."]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->MaNhomNCKHGV)) {
            $nhom->MaNhomNCKHGV = $data->MaNhomNCKHGV;
            $nhom->MaDeTaiNCKHGV = $data->MaDeTaiNCKHGV;

            if ($nhom->update()) {
                echo json_encode(["message" => "Nhóm NCKH được cập nhật thành công."]);
            } else {
                echo json_encode(["message" => "Không thể cập nhật nhóm NCKH."]);
            }
        } else {
            echo json_encode(["message" => "Tham số MaNhomNCKHGV không được cung cấp."]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->MaNhomNCKHGV)) {
            $nhom->MaNhomNCKHGV = $data->MaNhomNCKHGV;

            if ($nhom->delete()) {
                echo json_encode(["message" => "Nhóm NCKH đã được xóa."]);
            } else {
                echo json_encode(["message" => "Không thể xóa nhóm NCKH."]);
            }
        } else {
            echo json_encode(["message" => "Tham số MaNhomNCKHGV không được cung cấp."]);
        }
        break;

    default:
        echo json_encode(["message" => "Hành động không được hỗ trợ: $action."]);
        break;
}
?>
