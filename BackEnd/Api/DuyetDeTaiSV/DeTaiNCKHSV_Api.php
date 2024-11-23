<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/DuyetDeTaiSVModel/DeTaiNCKHSV.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$deTai = new DeTaiNCKHSV($conn);
$data = json_decode(file_get_contents('php://input'), true);  // Lấy dữ liệu từ body (POST, PUT)
$action = $_GET['action'] ?? '';  // Lấy action từ query string

switch ($action) {
    case 'get': // Lấy tất cả đề tài
        $result = $deTai->readAll();
        echo json_encode(['DeTaiNCKHSV' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add': // Thêm mới đề tài
        if (!empty($data['tenDeTai']) && !empty($data['moTa']) && !empty($data['trangThai']) && !empty($data['fileHopDong']) && !empty($data['maHoSo']) && !empty($data['maNhomNCKHSV'])) {
            $deTai->tenDeTai = $data['tenDeTai'];
            $deTai->moTa = $data['moTa'];
            $deTai->trangThai = $data['trangThai'];
            $deTai->fileHopDong = $data['fileHopDong'];
            $deTai->maHoSo = $data['maHoSo'];
            $deTai->maNhomNCKHSV = $data['maNhomNCKHSV'];

            if ($deTai->add()) {
                echo json_encode(['message' => 'Thêm đề tài thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể thêm đề tài'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;
        case 'updateGroup': // Chỉnh sửa mã nhóm và mã đề tài
            $missingFields = [];
        
            // Kiểm tra các trường bắt buộc
            if (empty($data['maDeTaiSV'])) {
                $missingFields[] = 'Mã Đề Tài';
            }
            if (empty($data['maNhomNCKHSV'])) {
                $missingFields[] = 'Mã Nhóm';
            }
        
            // Kiểm tra các giá trị nhận được
            error_log('Dữ liệu nhận được: ' . print_r($data, true));  // Log để kiểm tra dữ liệu nhận được
        
            // Nếu có trường thiếu, trả về lỗi chi tiết
            if (!empty($missingFields)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dữ liệu không đầy đủ: thiếu ' . implode(', ', $missingFields)
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                break;
            }
        
            // Giữ nguyên mã đề tài và chỉ thay đổi mã nhóm
            $deTai->maDeTaiSV = $data['maDeTaiSV'];
            $deTai->maNhomNCKHSV = $data['maNhomNCKHSV'];
        
            // Cập nhật chỉ mã nhóm mà không thay đổi các thuộc tính khác
            if ($deTai->updateGroup()) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật mã nhóm thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật mã nhóm'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            break;
        
case 'update': // Cập nhật đề tài
    $missingFields = []; // Mảng lưu các trường thiếu

    // Kiểm tra các trường bắt buộc
    if (empty($data['maDeTaiSV'])) {
        $missingFields[] = 'Mã Đề Tài';
    }
    if (empty($data['tenDeTai'])) {
        $missingFields[] = 'Tên Đề Tài';
    }
    if (empty($data['moTa'])) {
        $missingFields[] = 'Mô Tả';
    }

    // Nếu có trường thiếu, trả về lỗi chi tiết
    if (!empty($missingFields)) {
        echo json_encode([
            'success' => false,
            'message' => 'Dữ liệu không đầy đủ: thiếu ' . implode(', ', $missingFields)
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
    }

    // Nếu tất cả các trường bắt buộc đều có giá trị
    $deTai->maDeTaiSV = $data['maDeTaiSV'];
    $deTai->tenDeTai = $data['tenDeTai'];
    $deTai->moTa = $data['moTa'];
    $deTai->trangThai = $data['trangThai'] ?? 'Đang làm';
    $deTai->fileHopDong = $data['fileHopDong'] ?? null;
    $deTai->maHoSo = $data['maHoSo'] ?? null;
    $deTai->maNhomNCKHSV = $data['maNhomNCKHSV'] ?? null;

    if ($deTai->update()) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật đề tài thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể cập nhật đề tài'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    break;

        

    case 'delete': // Xóa đề tài
        if (!empty($data['maDeTaiSV'])) {
            $deTai->maDeTaiSV = $data['maDeTaiSV'];

            if ($deTai->delete()) {
                echo json_encode(['message' => 'Xóa đề tài thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể xóa đề tài'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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
