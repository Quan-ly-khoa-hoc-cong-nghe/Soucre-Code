<?php
header("Content-Type: application/json");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/NguoiDung/NhanVien.php';

// Kết nối cơ sở dữ liệu
$database = new Database();
$db = $database->getConn();
$nhanvien = new NhanVien($db);

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
        if ($action === "getAll") {
            $stmt = $nhanvien->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getById") {
            $nhanvien->MaNhanVien = isset($_GET['MaNhanVien']) ? $_GET['MaNhanVien'] : null;
            if ($nhanvien->MaNhanVien) {
                $stmt = $nhanvien->getById();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Thiếu MaNhanVien"]);
                http_response_code(400);
            }
        } else {
            echo json_encode(["message" => "Action không hợp lệ"]);
            http_response_code(400);
        }
        break;

    case 'POST':
        if ($action === "post") {
            $data = json_decode(file_get_contents("php://input"));
            if (!isset($data->MaNhanVien, $data->TenNhanVien, $data->sdtNV, $data->EmailNV, $data->PhongCongTac)) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $nhanvien->MaNhanVien = $data->MaNhanVien;
            $nhanvien->TenNhanVien = $data->TenNhanVien;
            $nhanvien->sdtNV = $data->sdtNV;
            $nhanvien->EmailNV = $data->EmailNV;
            $nhanvien->PhongCongTac = $data->PhongCongTac;

            if ($nhanvien->add()) {
                echo json_encode(["message" => "Nhân viên đã được thêm thành công"]);
            } else {
                echo json_encode(["message" => "Thêm nhân viên thất bại"]);
                http_response_code(500);
            }
        } else {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức POST"]);
            http_response_code(400);
        }
        break;

    case 'PUT':
        if ($action === "put") {
            $data = json_decode(file_get_contents("php://input"));
            if (!isset($data->MaNhanVien, $data->TenNhanVien, $data->sdtNV, $data->EmailNV, $data->PhongCongTac)) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $nhanvien->MaNhanVien = $data->MaNhanVien;
            $nhanvien->TenNhanVien = $data->TenNhanVien;
            $nhanvien->sdtNV = $data->sdtNV;
            $nhanvien->EmailNV = $data->EmailNV;
            $nhanvien->PhongCongTac = $data->PhongCongTac;

            if ($nhanvien->update()) {
                echo json_encode(["message" => "Nhân viên đã được cập nhật"]);
            } else {
                echo json_encode(["message" => "Cập nhật nhân viên thất bại"]);
                http_response_code(500);
            }
        } else {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
            http_response_code(400);
        }
        break;

    case 'DELETE':
        if ($action === "delete") {
            $data = json_decode(file_get_contents("php://input"));
            if (!isset($data->MaNhanVien)) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $nhanvien->MaNhanVien = $data->MaNhanVien;

            if ($nhanvien->delete()) {
                echo json_encode(["message" => "Nhân viên đã được xóa thành công"]);
            } else {
                echo json_encode(["message" => "Xóa nhân viên thất bại"]);
                http_response_code(500);
            }
        } else {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức DELETE"]);
            http_response_code(400);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
