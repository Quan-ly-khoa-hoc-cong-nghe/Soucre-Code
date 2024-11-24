<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHSinhVien/KeHoachNCKHSV.php';

$database = new Database();
$db = $database->getConn();
$keHoach = new KeHoachNCKHSV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

// Xử lý các action
switch ($action) {
    case 'GET':
        // Lấy tất cả kế hoạch
        $result = $keHoach->getAllKeHoach();
        echo json_encode($result);
        break;

    case 'GET_BY_MA_DETAI':
        // Lấy kế hoạch theo mã đề tài
        if (isset($_GET['maDeTai'])) {
            $result = $keHoach->getKeHoachByMaDeTai($_GET['maDeTai']);
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Không tìm thấy kế hoạch với mã đề tài này."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu mã đề tài (maDeTai)."]);
        }
        break;

    case 'POST':
        // Thêm kế hoạch mới
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['NgayBatDau'], $data['NgayKetThuc'], $data['KinhPhi'], $data['FileKeHoach'], $data['MaDeTaiSV'])) {
            $result = $keHoach->addKeHoach($data['NgayBatDau'], $data['NgayKetThuc'], $data['KinhPhi'], $data['FileKeHoach'], $data['MaDeTaiSV']);
            if ($result === true) {
                echo json_encode(["message" => "Thêm kế hoạch thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để thêm kế hoạch."]);
        }
        break;

    case 'PUT':
        // Cập nhật kế hoạch
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['NgayBatDau'], $data['NgayKetThuc'], $data['KinhPhi'], $data['FileKeHoach'], $data['MaDeTaiSV'])) {
            $result = $keHoach->updateKeHoach($data['NgayBatDau'], $data['NgayKetThuc'], $data['KinhPhi'], $data['FileKeHoach'], $data['MaDeTaiSV']);
            if ($result === true) {
                echo json_encode(["message" => "Cập nhật kế hoạch thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để cập nhật kế hoạch."]);
        }
        break;

    case 'DELETE':
        // Xóa kế hoạch theo mã đề tài
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaDeTaiSV'])) {
            $result = $keHoach->deleteKeHoach($data['MaDeTaiSV']);
            if ($result === true) {
                echo json_encode(["message" => "Xóa kế hoạch thành công."]);
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
