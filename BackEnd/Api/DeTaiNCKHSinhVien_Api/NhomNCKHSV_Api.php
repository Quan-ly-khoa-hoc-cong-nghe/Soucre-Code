<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHSinhVien/NhomNCKHSV.php';

$database = new Database();
$db = $database->getConn();
$nhom = new NhomNCKHSV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

// Xử lý các action
switch ($action) {
    case 'GET':
        // Lấy tất cả các nhóm
        $result = $nhom->getAllGroups();
        echo json_encode($result);
        break;

    case 'GET_BY_MANHOM':
        // Lấy nhóm theo mã nhóm
        if (isset($_GET['maNhom'])) {
            $result = $nhom->getGroupByMaNhom($_GET['maNhom']);
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Không tìm thấy nhóm với mã này."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu mã nhóm (maNhom)."]);
        }
        break;

    case 'POST':
        // Thêm nhóm mới
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaNhomNCKHSV'], $data['MaDeTaiSV'])) {
            $result = $nhom->addGroup($data['MaNhomNCKHSV'], $data['MaDeTaiSV']);
            if ($result === true) {
                echo json_encode(["message" => "Thêm nhóm thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để thêm nhóm."]);
        }
        break;

    case 'PUT':
        // Cập nhật nhóm
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaNhomNCKHSV'], $data['MaDeTaiSV'])) {
            $result = $nhom->updateGroup($data['MaNhomNCKHSV'], $data['MaDeTaiSV']);
            if ($result === true) {
                echo json_encode(["message" => "Cập nhật nhóm thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin cần thiết để cập nhật."]);
        }
        break;

    case 'DELETE':
        // Xóa nhóm
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaNhomNCKHSV'])) {
            $result = $nhom->deleteGroup($data['MaNhomNCKHSV']);
            if ($result === true) {
                echo json_encode(["message" => "Xóa nhóm thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu mã nhóm để xóa."]);
        }
        break;

    default:
        echo json_encode(["message" => "Action không hợp lệ."]);
        break;
}
?>
