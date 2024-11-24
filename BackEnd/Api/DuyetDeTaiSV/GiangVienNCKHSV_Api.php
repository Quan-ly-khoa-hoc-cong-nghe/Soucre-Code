<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/DuyetDeTaiSVModel/GiangVienNCKHSV.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$giangVienNCKHSV = new GiangVienNCKHSV($conn);
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        $result = $giangVienNCKHSV->readAll();
        echo json_encode(['GiangVienNCKHSV' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add': // Thêm giảng viên vào nhóm
        if (empty($data['MaNhomNCKHSV']) || empty($data['MaGV'])) {
            errorResponse("Vui lòng cung cấp đầy đủ thông tin: MaNhomNCKHSV, MaGV.");
        }

        $giangVienNCKHSV->MaNhomNCKHSV = $data['MaNhomNCKHSV'];
        $giangVienNCKHSV->MaGV = $data['MaGV'];

        if ($giangVienNCKHSV->add()) {
            successResponse("Thêm giảng viên vào nhóm thành công.");
        } else {
            errorResponse("Không thể thêm giảng viên vào nhóm.");
        }
        break;



    case 'update':
        if (!empty($data['MaNhomNCKHSV'])) {
            $giangVienNCKHSV->MaNhomNCKHSV = $data['MaNhomNCKHSV'];
            $giangVienNCKHSV->MaGV = $data['MaGV'];
            if ($giangVienNCKHSV->update()) {
                echo json_encode(['message' => 'Cập nhật giảng viên vào nhóm thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể cập nhật giảng viên vào nhóm'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Thiếu mã nhóm giảng viên'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'delete':
        if (!empty($data['MaNhomNCKHSV'])) {
            $giangVienNCKHSV->MaNhomNCKHSV = $data['MaNhomNCKHSV'];
            $giangVienNCKHSV->MaGV = $data['MaGV'];
            if ($giangVienNCKHSV->delete()) {
                echo json_encode(['message' => 'Xóa giảng viên khỏi nhóm thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể xóa giảng viên khỏi nhóm'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        echo json_encode(['message' => 'Action không hợp lệ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
