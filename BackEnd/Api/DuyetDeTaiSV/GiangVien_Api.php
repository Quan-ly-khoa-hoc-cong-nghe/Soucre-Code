<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/DuyetDeTaiSVModel/GiangVien.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$giangVien = new GiangVien($conn);
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        $result = $giangVien->readAll();
        echo json_encode(['GiangVien' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add':
        if (!empty($data['HoTenGV']) && !empty($data['EmailGV'])) {
            $giangVien->HoTenGV = $data['HoTenGV'];
            $giangVien->EmailGV = $data['EmailGV'];
            $giangVien->DiaChiGV = $data['DiaChiGV'];
            $giangVien->DiemNCKH = $data['DiemNCKH'];
            $giangVien->MaKhoa = $data['MaKhoa'];
            if ($giangVien->add()) {
                echo json_encode(['message' => 'Thêm giảng viên thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể thêm giảng viên'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'update':
        if (!empty($data['MaGV'])) {
            $giangVien->MaGV = $data['MaGV'];
            $giangVien->HoTenGV = $data['HoTenGV'];
            $giangVien->EmailGV = $data['EmailGV'];
            $giangVien->DiaChiGV = $data['DiaChiGV'];
            $giangVien->DiemNCKH = $data['DiemNCKH'];
            $giangVien->MaKhoa = $data['MaKhoa'];
            if ($giangVien->update()) {
                echo json_encode(['message' => 'Cập nhật giảng viên thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể cập nhật giảng viên'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Thiếu mã giảng viên'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'delete':
        if (!empty($data['MaGV'])) {
            $giangVien->MaGV = $data['MaGV'];
            if ($giangVien->delete()) {
                echo json_encode(['message' => 'Xóa giảng viên thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể xóa giảng viên'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        echo json_encode(['message' => 'Action không hợp lệ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
?>
