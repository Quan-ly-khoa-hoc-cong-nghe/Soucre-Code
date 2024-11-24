<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/DuyetDeTaiSVModel/KeHoachNCKHSV.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$keHoach = new KeHoachNCKHSV($conn);
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        $result = $keHoach->readAll();
        echo json_encode(['KeHoachNCKHSV' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add':
        if (!empty($data['NgayBatDau']) && !empty($data['NgayKetThuc'])) {
            $keHoach->NgayBatDau = $data['NgayBatDau'];
            $keHoach->NgayKetThuc = $data['NgayKetThuc'];
            $keHoach->KinhPhi = $data['KinhPhi'];
            $keHoach->FileKeHoach = $data['FileKeHoach'];
            $keHoach->MaDeTaiSV = $data['MaDeTaiSV'];
            if ($keHoach->add($data['FileKeHoach'])) {
                echo json_encode(['message' => 'Thêm kế hoạch thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể thêm kế hoạch'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

        case 'update':
            if (!empty($data['MaDeTaiSV'])) {
                // Kiểm tra đầu vào
                if (empty($data['NgayBatDau']) || empty($data['NgayKetThuc']) || !isset($data['KinhPhi'])) {
                    echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    exit;
                }
        
                // Gán giá trị và thực thi
                $keHoach->MaDeTaiSV = $data['MaDeTaiSV'];
                $keHoach->NgayBatDau = $data['NgayBatDau'];
                $keHoach->NgayKetThuc = $data['NgayKetThuc'];
                $keHoach->KinhPhi = $data['KinhPhi'];
                $keHoach->FileKeHoach = $data['FileKeHoach'];
        
                if ($keHoach->update()) {
                    echo json_encode(['message' => ''], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(['message' => ''], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }
            } else {
                echo json_encode(['message' => 'Thiếu mã đề tài'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            break;
        

    case 'delete':
        if (!empty($data['MaDeTaiSV'])) {
            $keHoach->MaDeTaiSV = $data['MaDeTaiSV'];
            if ($keHoach->delete()) {
                echo json_encode(['message' => 'Xóa kế hoạch thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể xóa kế hoạch'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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
