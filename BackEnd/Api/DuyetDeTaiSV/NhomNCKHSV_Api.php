<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/DuyetDeTaiSVModel/NhomNCKHSV.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$nhomNCKHSV = new NhomNCKHSV($conn);
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

function errorResponse($message)
{
    echo json_encode(['success' => false, 'message' => $message], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

function successResponse($message, $data = [])
{
    echo json_encode(array_merge(['success' => true, 'message' => $message], $data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

switch ($action) {
    case 'get':
        $result = $nhomNCKHSV->readAll();
        echo json_encode(['NhomNCKHSV' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add': // Thêm nhóm
        if (empty($data['MaDeTaiSV'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng cung cấp mã đề tài sinh viên.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }

        // Gán dữ liệu nhóm
        $nhomNCKHSV->MaDeTaiSV = $data['MaDeTaiSV'];  // Mã đề tài sinh viên (đã có từ bước thêm đề tài)

        // Thực hiện thêm nhóm
        $result = $nhomNCKHSV->add();

        if (isset($result['error'])) {
            // Nếu có lỗi xảy ra
            echo json_encode([
                'success' => false,
                'message' => $result['error']
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            // Thêm thành công, trả về ID nhóm
            echo json_encode([
                'success' => true,
                'message' => 'Thêm nhóm thành công.',
                'MaNhomNCKHSV' => $result  // Sử dụng trực tiếp giá trị MaNhomNCKHSV
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'autoUpdateGroups':
        $url = "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/DeTaiNCKHSV_Api.php?action=get";
        $response = file_get_contents($url);
        $dataDeTai = json_decode($response, true);

        if (isset($dataDeTai['DeTaiNCKHSV'])) {
            $result = $nhomNCKHSV->autoUpdateGroups($dataDeTai['DeTaiNCKHSV']);
            echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'Không lấy được dữ liệu từ bảng Đề Tài'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'update':
        if (!empty($data['MaNhomNCKHSV'])) {
            $nhomNCKHSV->MaNhomNCKHSV = $data['MaNhomNCKHSV'];
            $nhomNCKHSV->MaDeTaiSV = $data['MaDeTaiSV'];
            $result = $nhomNCKHSV->update();

            if (isset($result['error'])) {
                echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Cập nhật nhóm NCKHSV thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Thiếu mã nhóm NCKHSV'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'delete':
        if (!empty($data['MaNhomNCKHSV'])) {
            $nhomNCKHSV->MaNhomNCKHSV = $data['MaNhomNCKHSV'];
            $result = $nhomNCKHSV->delete();

            if (isset($result['error'])) {
                echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Xóa nhóm NCKHSV thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        echo json_encode(['message' => 'Action không hợp lệ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
