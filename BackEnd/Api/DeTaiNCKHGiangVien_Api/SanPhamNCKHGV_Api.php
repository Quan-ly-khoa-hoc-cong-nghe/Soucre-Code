<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHGiangVien/SanPhamNCKHGV.php';

$database = new Database();
$db = $database->getConn();
$sanPham = new SanPhamNCKHGV($db);

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
        $result = $sanPham->getAllProducts();
        echo json_encode($result);
        break;

    case 'GET_BY_ID':
        // Lấy sản phẩm theo MaDeTaiNCKHGV
        if (isset($_GET['maDeTai'])) {
            $result = $sanPham->getProductByMaDeTai($_GET['maDeTai']);
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
        if (isset($data['TenSanPham'], $data['NgayHoanThanh'], $data['KetQua'], $data['MaDeTaiNCKHGV'])) {
            if ($sanPham->addProduct($data['TenSanPham'], $data['NgayHoanThanh'], $data['KetQua'], $data['MaDeTaiNCKHGV'])) {
                echo json_encode(["message" => "Thêm sản phẩm thành công."]);
            } else {
                echo json_encode(["message" => "Thêm sản phẩm thất bại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin sản phẩm để tạo."]);
        }
        break;

    case 'PUT':
        // Cập nhật sản phẩm theo MaDeTaiNCKHGV
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaDeTaiNCKHGV'], $data['TenSanPham'], $data['NgayHoanThanh'], $data['KetQua'])) {
            if ($sanPham->updateProductByMaDeTai($data['MaDeTaiNCKHGV'], $data['TenSanPham'], $data['NgayHoanThanh'], $data['KetQua'])) {
                echo json_encode(["message" => "Cập nhật sản phẩm thành công."]);
            } else {
                echo json_encode(["message" => "Cập nhật thất bại hoặc MaDeTaiNCKHGV không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin cần thiết để cập nhật."]);
        }
        break;

    case 'DELETE':
        // Xóa sản phẩm theo MaDeTaiNCKHGV
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaDeTaiNCKHGV'])) {
            if ($sanPham->deleteProductByMaDeTai($data['MaDeTaiNCKHGV'])) {
                echo json_encode(["message" => "Xóa sản phẩm thành công."]);
            } else {
                echo json_encode(["message" => "Xóa thất bại hoặc MaDeTaiNCKHGV không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu MaDeTaiNCKHGV để xóa."]);
        }
        break;

    default:
        echo json_encode(["message" => "Action không hợp lệ."]);
        break;
}
?>
