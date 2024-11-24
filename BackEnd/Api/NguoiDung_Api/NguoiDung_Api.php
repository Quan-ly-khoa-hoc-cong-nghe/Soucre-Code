<?php
header("Content-Type: application/json");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/NguoiDung/NguoiDung.php';

// Kết nối cơ sở dữ liệu
$database = new Database();
$db = $database->getConn();
$nguoidung = new NguoiDung($db);

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
            $stmt = $nguoidung->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getById") {
            $nguoidung->MaNguoiDung = isset($_GET['MaNguoiDung']) ? $_GET['MaNguoiDung'] : null;
            if ($nguoidung->MaNguoiDung) {
                $stmt = $nguoidung->getById();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Thiếu MaNguoiDung"]);
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
            if (!isset($data->MaNguoiDung, $data->VaiTro, $data->MatKhau, $data->MaNhanVien)) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $nguoidung->MaNguoiDung = $data->MaNguoiDung;
            $nguoidung->VaiTro = $data->VaiTro;
            $nguoidung->MatKhau = password_hash($data->MatKhau, PASSWORD_BCRYPT); // Mã hóa mật khẩu
            $nguoidung->MaNhanVien = $data->MaNhanVien;

            if ($nguoidung->add()) {
                echo json_encode(["message" => "Người dùng đã được thêm thành công"]);
            } else {
                echo json_encode(["message" => "Thêm người dùng thất bại"]);
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
            if (!isset($data->MaNguoiDung, $data->VaiTro, $data->MatKhau, $data->MaNhanVien)) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $nguoidung->MaNguoiDung = $data->MaNguoiDung;
            $nguoidung->VaiTro = $data->VaiTro;
            $nguoidung->MatKhau = password_hash($data->MatKhau, PASSWORD_BCRYPT);
            $nguoidung->MaNhanVien = $data->MaNhanVien;

            if ($nguoidung->update()) {
                echo json_encode(["message" => "Người dùng đã được cập nhật"]);
            } else {
                echo json_encode(["message" => "Cập nhật người dùng thất bại"]);
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
            if (!isset($data->MaNguoiDung)) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $nguoidung->MaNguoiDung = $data->MaNguoiDung;

            if ($nguoidung->delete()) {
                echo json_encode(["message" => "Người dùng đã được xóa thành công"]);
            } else {
                echo json_encode(["message" => "Xóa người dùng thất bại"]);
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
