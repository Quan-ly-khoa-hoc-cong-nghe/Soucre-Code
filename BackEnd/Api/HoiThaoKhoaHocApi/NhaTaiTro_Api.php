<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET,PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: application/json");

require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/NhaTaiTro.php';

$database = new Database();
$db = $database->getConn();
$nhataitro = new NhaTaiTro($db);

// Lấy phương thức HTTP và tham số `action`
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
            $stmt = $nhataitro->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } else {
            echo json_encode(["message" => "Action không hợp lệ"]);
            http_response_code(400);
        }
        break;

    case 'POST':
        if ($action !== "post") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức POST"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->TenNhaTaiTro, $data->DiaChi, $data->SoDienThoai, $data->Email)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nhataitro->TenNhaTaiTro = $data->TenNhaTaiTro;
        $nhataitro->DiaChi = $data->DiaChi;
        $nhataitro->SoDienThoai = $data->SoDienThoai;
        $nhataitro->Email = $data->Email;

        if ($nhataitro->add()) {
            echo json_encode(["message" => "Nhà tài trợ được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm nhà tài trợ thất bại"]);
            http_response_code(500);
        }
        break;

    case 'PUT':
        if ($action !== "put") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->MaNhaTaiTro, $data->TenNhaTaiTro, $data->DiaChi, $data->SoDienThoai, $data->Email)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nhataitro->MaNhaTaiTro = $data->MaNhaTaiTro;
        $nhataitro->TenNhaTaiTro = $data->TenNhaTaiTro;
        $nhataitro->DiaChi = $data->DiaChi;
        $nhataitro->SoDienThoai = $data->SoDienThoai;
        $nhataitro->Email = $data->Email;

        if ($nhataitro->update()) {
            echo json_encode(["message" => "Nhà tài trợ được cập nhật thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật nhà tài trợ thất bại"]);
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
        if (!isset($data->MaNhaTaiTro)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nhataitro->MaNhaTaiTro = $data->MaNhaTaiTro;

        if ($nhataitro->delete()) {
            echo json_encode(["message" => "Nhà tài trợ được xóa thành công"]);
        } else {
            echo json_encode(["message" => "Xóa nhà tài trợ thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>