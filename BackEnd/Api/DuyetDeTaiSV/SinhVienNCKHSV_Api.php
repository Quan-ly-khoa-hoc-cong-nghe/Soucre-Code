<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/DuyetDeTaiSVModel/SinhVienNCKHSV.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$sinhVienNCKHSV = new SinhVienNCKHSV($conn);
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        $result = $sinhVienNCKHSV->readAll();
        echo json_encode(['SinhVienNCKHSV' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add':
        if (!empty($data['MaNhomNCKHSV']) && !empty($data['MaSinhVien'])) {
            $sinhVienNCKHSV->MaNhomNCKHSV = $data['MaNhomNCKHSV'];
            $sinhVienNCKHSV->MaSinhVien = $data['MaSinhVien'];
            if ($sinhVienNCKHSV->add()) {
                echo json_encode(['message' => 'Thêm sinh viên vào nhóm thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể thêm sinh viên vào nhóm'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'update':
        if (!empty($data['MaNhomNCKHSV'])) {
            $sinhVienNCKHSV->MaNhomNCKHSV = $data['MaNhomNCKHSV'];
            $sinhVienNCKHSV->MaSinhVien = $data['MaSinhVien'];
            if ($sinhVienNCKHSV->update()) {
                echo json_encode(['message' => 'Cập nhật sinh viên vào nhóm thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể cập nhật sinh viên vào nhóm'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Thiếu mã nhóm sinh viên'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

        case 'delete':
            $data = json_decode(file_get_contents('php://input'), true);
            $maSinhVien = $data['MaSinhVien'];
            $maNhomNCKHSV = $data['MaNhomNCKHSV'];
        
            // Xóa sinh viên khỏi nhóm trong bảng SinhVienNCKHSV
            $sql = "DELETE FROM SinhVienNCKHSV WHERE MaSinhVien = :maSinhVien AND MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':maSinhVien', $maSinhVien);
            $stmt->bindParam(':maNhomNCKHSV', $maNhomNCKHSV);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Xóa sinh viên khỏi nhóm thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể xóa sinh viên khỏi nhóm'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            break;
        

    default:
        echo json_encode(['message' => 'Action không hợp lệ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
?>
