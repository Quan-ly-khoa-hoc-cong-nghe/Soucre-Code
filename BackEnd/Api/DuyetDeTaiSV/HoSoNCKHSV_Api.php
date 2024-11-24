<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/DuyetDeTaiSVModel/HoSoNCKHSV.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$hoSo = new HoSoNCKHSV($conn);
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        $result = $hoSo->readAll();
        echo json_encode(['HoSoNCKHSV' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add':
        if (!empty($data['MaHoSo']) && !empty($data['NgayNop']) && !empty($data['FileHoSo']) && !empty($data['TrangThai']) && !empty($data['MaKhoa'])) {
            $hoSo->MaHoSo = $data['MaHoSo'];
            $hoSo->NgayNop = $data['NgayNop'];
            $hoSo->FileHoSo = $data['FileHoSo'];
            $hoSo->TrangThai = $data['TrangThai'];
            $hoSo->MaKhoa = $data['MaKhoa'];

            if ($hoSo->add()) {
                echo json_encode(['message' => 'Thêm hồ sơ thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể thêm hồ sơ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'update':
        if (!empty($data['MaHoSo'])) {
            $hoSo->MaHoSo = $data['MaHoSo'];
            $hoSo->NgayNop = $data['NgayNop'];
            $hoSo->FileHoSo = $data['FileHoSo'];
            $hoSo->TrangThai = $data['TrangThai'];
            $hoSo->MaKhoa = $data['MaKhoa'];

            if ($hoSo->update()) {
                echo json_encode(['message' => 'Cập nhật hồ sơ thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể cập nhật hồ sơ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Thiếu mã hồ sơ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'delete':
        if (!empty($data['MaHoSo'])) {
            $hoSo->MaHoSo = $data['MaHoSo'];
            if ($hoSo->delete()) {
                echo json_encode(['message' => 'Xóa hồ sơ thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể xóa hồ sơ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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
