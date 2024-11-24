<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/DuyetDeTaiSVModel/SinhVien.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$sinhVien = new SinhVien($conn);
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        $result = $sinhVien->readAll();
        echo json_encode(['SinhVien' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

        case 'add':
            // Lấy dữ liệu từ body của yêu cầu POST
            $data = json_decode(file_get_contents('php://input'), true);
        
            // Kiểm tra xem tất cả các trường cần thiết có được cung cấp không
            if (!empty($data['MaSinhVien']) && !empty($data['TenSinhVien']) && !empty($data['EmailSV']) && !empty($data['sdtSV'])) {
                // Kết nối và thêm dữ liệu vào cơ sở dữ liệu
                $sinhVien->MaSinhVien = $data['MaSinhVien'];
                $sinhVien->TenSinhVien = $data['TenSinhVien'];
                $sinhVien->EmailSV = $data['EmailSV'];
                $sinhVien->sdtSV = $data['sdtSV'];
        
                // Gọi phương thức add để thêm sinh viên vào cơ sở dữ liệu
                if ($sinhVien->add()) {
                    echo json_encode(['message' => 'Thêm sinh viên thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(['message' => 'Không thể thêm sinh viên'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }
            } else {
                echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            break;
        
        

    case 'update':
        if (!empty($data['MaSinhVien'])) {
            $sinhVien->MaSinhVien = $data['MaSinhVien'];
            $sinhVien->TenSinhVien = $data['TenSinhVien'];
            $sinhVien->EmailSV = $data['EmailSV'];
            $sinhVien->sdtSV = $data['sdtSV'];
            if ($sinhVien->update()) {
                echo json_encode(['message' => 'Cập nhật sinh viên thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể cập nhật sinh viên'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Thiếu mã sinh viên'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'delete':
        $data = json_decode(file_get_contents('php://input'), true);
        $maSinhVien = $data['MaSinhVien'];
    
        // Xóa sinh viên khỏi bảng SinhVien
        $sql = "DELETE FROM SinhVien WHERE MaSinhVien = :maSinhVien";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':maSinhVien', $maSinhVien);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Xóa sinh viên thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['message' => 'Không thể xóa sinh viên'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;
    
        case 'getById':
            if (isset($_GET['MaSinhVien'])) {
                $MaSinhVien = $_GET['MaSinhVien'];
                $sql = "SELECT * FROM SinhVien WHERE MaSinhVien = :MaSinhVien";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':MaSinhVien', $MaSinhVien, PDO::PARAM_STR);
                $stmt->execute();
        
                // Lấy kết quả truy vấn
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($data) {
                    echo json_encode(["SinhVien" => $data], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(["message" => "Không tìm thấy sinh viên"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }
            } else {
                echo json_encode(["message" => "Thiếu tham số MaSinhVien"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            break;        
      
    default:
        echo json_encode(['message' => 'Action không hợp lệ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
?>
