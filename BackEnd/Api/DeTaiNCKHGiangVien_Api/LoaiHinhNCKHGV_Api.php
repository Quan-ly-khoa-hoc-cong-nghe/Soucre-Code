<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHGiangVien/LoaiHinhNCKHGV.php';

$database = new Database();
$db = $database->getConn();
$loaiHinh = new LoaiHinhNCKH($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

// Xử lý các action
switch ($action) {
    case 'GET':
        // Lấy tất cả loại hình
        $result = $loaiHinh->getAllLoaiHinh();
        echo json_encode($result);
        break;

    case 'GET_BY_ID':
        // Lấy loại hình theo mã loại hình
        if (isset($_GET['maLoaiHinh'])) {
            $result = $loaiHinh->getLoaiHinhByMa($_GET['maLoaiHinh']);
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Không tìm thấy loại hình với mã này."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu mã loại hình (maLoaiHinh)."]);
        }
        break;

    case 'POST':
        // Thêm loại hình mới
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaLoaiHinhNCKH'], $data['TenLoaiHinh'], $data['DiemSo'])) {
            if ($loaiHinh->addLoaiHinh($data['MaLoaiHinhNCKH'], $data['TenLoaiHinh'], $data['DiemSo'])) {
                echo json_encode(["message" => "Thêm loại hình thành công."]);
            } else {
                echo json_encode(["message" => "Thêm loại hình thất bại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin loại hình để tạo."]);
        }
        break;

    case 'PUT':
        // Cập nhật loại hình
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaLoaiHinhNCKH'], $data['TenLoaiHinh'], $data['DiemSo'])) {
            if ($loaiHinh->updateLoaiHinh($data['MaLoaiHinhNCKH'], $data['TenLoaiHinh'], $data['DiemSo'])) {
                echo json_encode(["message" => "Cập nhật loại hình thành công."]);
            } else {
                echo json_encode(["message" => "Cập nhật thất bại hoặc mã loại hình không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin cần thiết để cập nhật."]);
        }
        break;

    case 'DELETE':
        // Xóa loại hình
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaLoaiHinhNCKH'])) {
            if ($loaiHinh->deleteLoaiHinh($data['MaLoaiHinhNCKH'])) {
                echo json_encode(["message" => "Xóa loại hình thành công."]);
            } else {
                echo json_encode(["message" => "Xóa thất bại hoặc mã loại hình không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu mã loại hình để xóa."]);
        }
        break;

    default:
        echo json_encode(["message" => "Action không hợp lệ."]);
        break;
}
?>
