<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHSinhVien/DeTaiNCKHSV.php';

$database = new Database();
$db = $database->getConn();
$deTai = new DeTaiNCKHSV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

// Xử lý các action
switch ($action) {
    case 'GET':
        // Lấy tất cả đề tài
        $result = $deTai->getAllDeTai();
        echo json_encode($result);
        break;

    case 'GET_BY_ID':
        // Lấy đề tài theo mã
        if (isset($_GET['maDeTai'])) {
            $result = $deTai->getDeTaiByMa($_GET['maDeTai']);
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Không tìm thấy đề tài với mã này."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu mã đề tài (maDeTai)."]);
        }
        break;

    case 'POST':
        // Thêm đề tài mới
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaDeTaiSV'], $data['TenDeTai'], $data['MoTa'], $data['TrangThai'], $data['FileHopDong'], $data['MaHoSo'], $data['MaNhomNCKHSV'])) {
            $result = $deTai->addDeTai($data['MaDeTaiSV'], $data['TenDeTai'], $data['MoTa'], $data['TrangThai'], $data['FileHopDong'], $data['MaHoSo'], $data['MaNhomNCKHSV']);
            if ($result === true) {
                echo json_encode(["message" => "Thêm đề tài thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để thêm đề tài."]);
        }
        break;

    case 'PUT':
        // Cập nhật đề tài
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaDeTaiSV'], $data['TenDeTai'], $data['MoTa'], $data['TrangThai'], $data['FileHopDong'], $data['MaHoSo'], $data['MaNhomNCKHSV'])) {
            $result = $deTai->updateDeTai($data['MaDeTaiSV'], $data['TenDeTai'], $data['MoTa'], $data['TrangThai'], $data['FileHopDong'], $data['MaHoSo'], $data['MaNhomNCKHSV']);
            if ($result === true) {
                echo json_encode(["message" => "Cập nhật đề tài thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin cần thiết để cập nhật."]);
        }
        break;

    case 'DELETE':
        // Xóa đề tài
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaDeTaiSV'])) {
            $result = $deTai->deleteDeTai($data['MaDeTaiSV']);
            if ($result === true) {
                echo json_encode(["message" => "Xóa đề tài thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu mã đề tài để xóa."]);
        }
        break;

    default:
        echo json_encode(["message" => "Action không hợp lệ."]);
        break;
}
?>
