<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHSinhVien/HoSoNCKHSV.php';

$database = new Database();
$db = $database->getConn();
$hoSo = new HoSoNCKHSV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

// Xử lý các action
switch ($action) {
    case 'GET':
        // Lấy tất cả hồ sơ
        $result = $hoSo->getAllHoSo();
        echo json_encode($result);
        break;

    case 'GET_BY_ID':
        // Lấy hồ sơ theo mã
        if (isset($_GET['maHoSo'])) {
            $result = $hoSo->getHoSoByMa($_GET['maHoSo']);
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Không tìm thấy hồ sơ với mã này."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu mã hồ sơ (maHoSo)."]);
        }
        break;

    case 'POST':
        // Thêm hồ sơ mới
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaHoSo'], $data['NgayNop'], $data['FileHoSo'], $data['TrangThai'], $data['MaKhoa'])) {
            $result = $hoSo->addHoSo($data['MaHoSo'], $data['NgayNop'], $data['FileHoSo'], $data['TrangThai'], $data['MaKhoa']);
            if ($result === true) {
                echo json_encode(["message" => "Thêm hồ sơ thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để thêm hồ sơ."]);
        }
        break;

    case 'PUT':
        // Cập nhật hồ sơ
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaHoSo'], $data['NgayNop'], $data['FileHoSo'], $data['TrangThai'], $data['MaKhoa'])) {
            $result = $hoSo->updateHoSo($data['MaHoSo'], $data['NgayNop'], $data['FileHoSo'], $data['TrangThai'], $data['MaKhoa']);
            if ($result === true) {
                echo json_encode(["message" => "Cập nhật hồ sơ thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin cần thiết để cập nhật."]);
        }
        break;

    case 'DELETE':
        // Xóa hồ sơ
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaHoSo'])) {
            $result = $hoSo->deleteHoSo($data['MaHoSo']);
            if ($result === true) {
                echo json_encode(["message" => "Xóa hồ sơ thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu mã hồ sơ để xóa."]);
        }
        break;

    default:
        echo json_encode(["message" => "Action không hợp lệ."]);
        break;
}
?>
