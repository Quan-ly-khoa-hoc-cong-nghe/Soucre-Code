<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiCapCoSoModel/SanPhamDTCS.php';

$database = new Database();
$db = $database->getConn();
$sanpham = new SanPhamDTCS($db);

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$action) {
    echo json_encode(["message" => "Yêu cầu không hợp lệ: thiếu tham số action"]);
    http_response_code(400);
    exit;
}

switch ($method) {
    case 'GET':
        if ($action === "get") {
            $stmt = $sanpham->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $TenSanPham = isset($_GET['TenSanPham']) ? $_GET['TenSanPham'] : null;
            $MaDTCS = isset($_GET['MaDTCS']) ? $_GET['MaDTCS'] : null;
            if (!$TenSanPham || !$MaDTCS) {
                echo json_encode(["message" => "Thiếu thông tin sản phẩm hoặc mã đề tài cơ sở"]);
                http_response_code(400);
                exit;
            }
            $sanpham->TenSanPham = $TenSanPham; // Gán tên sản phẩm
            $sanpham->MaDTCS = $MaDTCS;         // Gán mã đề tài cơ sở
            $data = $sanpham->getOne();
            echo json_encode($data);
        } else {
            echo json_encode(["message" => "Action không hợp lệ"]);
            http_response_code(400);
        }
        break;

    case 'POST':
        if ($action !== "add") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức POST"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->ten_san_pham, $data->ngay_hoan_thanh, $data->ket_qua, $data->ma_dtcs)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $sanpham->TenSanPham = $data->ten_san_pham;
        $sanpham->NgayHoanThanh = $data->ngay_hoan_thanh;
        $sanpham->KetQua = $data->ket_qua;
        $sanpham->MaDTCS = $data->ma_dtcs;

        if ($sanpham->add()) {
            echo json_encode(["message" => "Sản phẩm được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm sản phẩm thất bại"]);
            http_response_code(500);
        }
        break;

    case 'PUT':
        if ($action !== "update") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->ten_san_pham, $data->ngay_hoan_thanh, $data->ket_qua, $data->ma_dtcs)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $sanpham->TenSanPham = $data->ten_san_pham;
        $sanpham->NgayHoanThanh = $data->ngay_hoan_thanh;
        $sanpham->KetQua = $data->ket_qua;
        $sanpham->MaDTCS = $data->ma_dtcs;

        if ($sanpham->update()) {
            echo json_encode(["message" => "Sản phẩm được cập nhật thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật sản phẩm thất bại"]);
            http_response_code(500);
        }
        break;

    case 'DELETE':
        if ($action !== "delete") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức DELETE"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->ten_san_pham, $data->ma_dtcs)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $sanpham->TenSanPham = $data->ten_san_pham;
        $sanpham->MaDTCS = $data->ma_dtcs;

        if ($sanpham->delete()) {
            echo json_encode(["message" => "Sản phẩm được xóa thành công"]);
        } else {
            echo json_encode(["message" => "Xóa sản phẩm thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}