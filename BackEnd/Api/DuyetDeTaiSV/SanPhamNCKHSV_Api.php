<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/DuyetDeTaiSVModel/SanPhamNCKHSV.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$sanPham = new SanPhamNCKHSV($conn);
$data = json_decode(file_get_contents('php://input'), true);  // Nhận dữ liệu JSON
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        // Lấy tất cả sản phẩm
        $result = $sanPham->readAll();
        echo json_encode(['SanPhamNCKHSV' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add':
        // Thêm sản phẩm mới
        if (!empty($data['TenSanPham']) && !empty($data['KetQua'])) {
            $sanPham->TenSanPham = $data['TenSanPham'];
            $sanPham->NgayHoanThanh = $data['NgayHoanThanh'];
            $sanPham->KetQua = $data['KetQua'];
            $sanPham->MaDeTaiSV = $data['MaDeTaiSV'];
            $sanPham->FileSanPham = $data['FileSanPham'] ?? null;  // Nếu không có file, gán null

            if ($sanPham->add()) {
                echo json_encode(['message' => 'Thêm sản phẩm thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể thêm sản phẩm'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'update':
        // Cập nhật sản phẩm
        if (!empty($data['MaDeTaiSV'])) {
            if (empty($data['TenSanPham']) || empty($data['NgayHoanThanh']) || empty($data['KetQua'])) {
                echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $sanPham->MaDeTaiSV = $data['MaDeTaiSV'];
            $sanPham->TenSanPham = $data['TenSanPham'];
            $sanPham->NgayHoanThanh = $data['NgayHoanThanh'];
            $sanPham->KetQua = $data['KetQua'];
            $sanPham->FileSanPham = $data['FileSanPham'] ?? null;  // Nếu không có file, gán null

            if ($sanPham->update()) {
                echo json_encode(['message' => 'Cập nhật sản phẩm thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể cập nhật sản phẩm'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Thiếu mã đề tài'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'delete':
        // Xóa sản phẩm
        if (!empty($data['MaDeTaiSV'])) {
            $sanPham->MaDeTaiSV = $data['MaDeTaiSV'];
            if ($sanPham->delete()) {
                echo json_encode(['message' => 'Xóa sản phẩm thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể xóa sản phẩm'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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
