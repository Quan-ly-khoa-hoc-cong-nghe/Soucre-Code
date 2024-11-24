<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHSinhVien/SinhVienNCKHSV.php';

$database = new Database();
$db = $database->getConn();
$sinhVien = new SinhVienNCKHSV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

// Xử lý các action
switch ($action) {
    case 'GET':
        // Lấy tất cả các bản ghi
        $result = $sinhVien->getAllRecords();
        echo json_encode($result);
        break;

    case 'GET_BY_MANHOM':
        // Lấy danh sách sinh viên theo mã nhóm
        if (isset($_GET['maNhom'])) {
            $result = $sinhVien->getByMaNhom($_GET['maNhom']);
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Không tìm thấy sinh viên trong nhóm này."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu mã nhóm (maNhom)."]);
        }
        break;

    case 'POST':
        // Thêm sinh viên mới vào nhóm
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaNhomNCKHSV'], $data['MaSinhVien'])) {
            $result = $sinhVien->addRecord($data['MaNhomNCKHSV'], $data['MaSinhVien']);
            if ($result === true) {
                echo json_encode(["message" => "Thêm sinh viên thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để thêm sinh viên."]);
        }
        break;

    case 'PUT':
        // Cập nhật sinh viên trong nhóm
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaNhomNCKHSV'], $data['MaSinhVien'], $data['NewMaSinhVien'])) {
            $result = $sinhVien->updateRecord($data['MaNhomNCKHSV'], $data['MaSinhVien'], $data['NewMaSinhVien']);
            if ($result === true) {
                echo json_encode(["message" => "Cập nhật sinh viên thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để cập nhật."]);
        }
        break;

    case 'DELETE':
        // Xóa sinh viên khỏi nhóm
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaNhomNCKHSV'], $data['MaSinhVien'])) {
            $result = $sinhVien->deleteRecord($data['MaNhomNCKHSV'], $data['MaSinhVien']);
            if ($result === true) {
                echo json_encode(["message" => "Xóa sinh viên thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để xóa."]);
        }
        break;

    default:
        echo json_encode(["message" => "Action không hợp lệ."]);
        break;
}
?>
