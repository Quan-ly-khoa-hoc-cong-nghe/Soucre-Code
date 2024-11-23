<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHSinhVien/SanPhamNCKHSV.php';

$database = new Database();
$db = $database->getConn();
$sanPham = new SanPhamNCKHSV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

// Xử lý các action
switch ($action) {
    case 'GET':
        // Lấy tất cả sản phẩm
        $result = $sanPham->getAllSanPham();
        echo json_encode($result);
        break;

    case 'GET_BY_MA_DETAI':
        // Lấy sản phẩm theo mã đề tài
        if (isset($_GET['maDeTai'])) {
            $result = $sanPham->getSanPhamByMaDeTai($_GET['maDeTai']);
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Không tìm thấy sản phẩm với mã đề tài này."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu mã đề tài (maDeTai)."]);
        }
        break;

    case 'POST':
        // Thêm sản phẩm mới
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['TenSanPham'], $data['NgayHoanThanh'], $data['KetQua'], $data['MaDeTaiSV'])) {
            $result = $sanPham->addSanPham($data['TenSanPham'], $data['NgayHoanThanh'], $data['KetQua'], $data['MaDeTaiSV']);
            if ($result === true) {
                echo json_encode(["message" => "Thêm sản phẩm thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để thêm sản phẩm."]);
        }
        break;

    case 'PUT':
        // Cập nhật sản phẩm
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['TenSanPham'], $data['NgayHoanThanh'], $data['KetQua'], $data['MaDeTaiSV'])) {
            $result = $sanPham->updateSanPham($data['TenSanPham'], $data['NgayHoanThanh'], $data['KetQua'], $data['MaDeTaiSV']);
            if ($result === true) {
                echo json_encode(["message" => "Cập nhật sản phẩm thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin để cập nhật sản phẩm."]);
        }
        break;

    case 'DELETE':
        // Xóa sản phẩm theo mã đề tài
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaDeTaiSV'])) {
            $result = $sanPham->deleteSanPhamByMaDeTai($data['MaDeTaiSV']);
            if ($result === true) {
                echo json_encode(["message" => "Xóa sản phẩm thành công."]);
            } else {
                echo json_encode(["message" => $result]); // Trả về lỗi nếu có
            }
        } else {
            echo json_encode(["message" => "Thiếu mã đề tài để xóa sản phẩm."]);
        }
        break;

    default:
        echo json_encode(["message" => "Action không hợp lệ."]);
        break;
}
?>
