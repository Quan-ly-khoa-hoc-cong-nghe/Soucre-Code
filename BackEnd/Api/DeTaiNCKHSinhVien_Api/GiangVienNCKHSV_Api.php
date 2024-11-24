<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHSinhVien/GiangVienNCKHSV.php';

$database = new Database();
$db = $database->getConn();
$giangVien = new GiangVienNCKHSV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

// Xử lý các action
switch ($action) {
    case 'GET':
        // Lấy tất cả bản ghi
        $result = $giangVien->getAllRecords();
        echo json_encode($result);
        break;

    case 'GET_BY_MANHOM':
        // Lấy giảng viên theo mã nhóm
        if (isset($_GET['maNhom'])) {
            $result = $giangVien->getByMaNhom($_GET['maNhom']);
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Không tìm thấy giảng viên trong nhóm này."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu mã nhóm (maNhom)."]);
        }
        break;

    case 'POST':
        // Thêm bản ghi mới
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaNhomNCKHSV'], $data['MaGV'])) {
            if ($giangVien->addRecord($data['MaNhomNCKHSV'], $data['MaGV'])) {
                echo json_encode(["message" => "Thêm giảng viên vào nhóm thành công."]);
            } else {
                echo json_encode(["message" => "Thêm giảng viên thất bại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để thêm giảng viên."]);
        }
        break;

    case 'PUT':
        // Cập nhật giảng viên
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaNhomNCKHSV'], $data['MaGV'], $data['NewMaGV'])) {
            if ($giangVien->updateRecord($data['MaNhomNCKHSV'], $data['MaGV'], $data['NewMaGV'])) {
                echo json_encode(["message" => "Cập nhật giảng viên thành công."]);
            } else {
                echo json_encode(["message" => "Cập nhật thất bại hoặc bản ghi không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin cần thiết để cập nhật."]);
        }
        break;

    case 'DELETE':
        // Xóa giảng viên khỏi nhóm
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaNhomNCKHSV'], $data['MaGV'])) {
            if ($giangVien->deleteRecord($data['MaNhomNCKHSV'], $data['MaGV'])) {
                echo json_encode(["message" => "Xóa giảng viên thành công."]);
            } else {
                echo json_encode(["message" => "Xóa thất bại hoặc bản ghi không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để xóa giảng viên."]);
        }
        break;

    default:
        echo json_encode(["message" => "Action không hợp lệ."]);
        break;
}
?>
