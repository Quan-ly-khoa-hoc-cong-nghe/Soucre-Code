<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/SanPhamNCNT.php';

$database = new Database();
$db = $database->getConn();
$sanpham = new SanPhamNCNT($db);

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
            $tenSanPham = isset($_GET['ten_san_pham']) ? $_GET['ten_san_pham'] : null;
            $maDuAn = isset($_GET['ma_du_an']) ? $_GET['ma_du_an'] : null;
            if (!$tenSanPham || !$maDuAn) {
                echo json_encode(["message" => "Thiếu thông tin sản phẩm hoặc mã dự án"]);
                http_response_code(400);
                exit;
            }
            $sanpham->ten_san_pham = $tenSanPham;
            $sanpham->ma_du_an = $maDuAn;
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
        if (!isset($data->ten_san_pham, $data->ngay_hoan_thanh, $data->ket_qua, $data->ma_du_an)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $sanpham->ten_san_pham = $data->ten_san_pham;
        $sanpham->ngay_hoan_thanh = $data->ngay_hoan_thanh;
        $sanpham->ket_qua = $data->ket_qua;
        $sanpham->ma_du_an = $data->ma_du_an;

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
        if (!isset($data->ten_san_pham, $data->ngay_hoan_thanh, $data->ket_qua, $data->ma_du_an)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $sanpham->ten_san_pham = $data->ten_san_pham;
        $sanpham->ngay_hoan_thanh = $data->ngay_hoan_thanh;
        $sanpham->ket_qua = $data->ket_qua;
        $sanpham->ma_du_an = $data->ma_du_an;

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
        if (!isset($data->ten_san_pham, $data->ma_du_an)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $sanpham->ten_san_pham = $data->ten_san_pham;
        $sanpham->ma_du_an = $data->ma_du_an;

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
?>
