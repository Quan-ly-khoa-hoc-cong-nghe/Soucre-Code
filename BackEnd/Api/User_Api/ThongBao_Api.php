<?php
header("Content-Type: application/json");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/NguoiDung/ThongBao.php';

// Kết nối cơ sở dữ liệu
$database = new Database();
$db = $database->getConn();
$thongbao = new ThongBao($db);

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
            $stmt = $thongbao->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getById") {
            $thongbao->MaThongBao = isset($_GET['MaThongBao']) ? $_GET['MaThongBao'] : null;
            if ($thongbao->MaThongBao) {
                $stmt = $thongbao->getById();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Thiếu MaThongBao"]);
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
            if (!isset($data->MaThongBao, $data->TieuDe, $data->FileThongBao, $data->MaNguoiDung)) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $thongbao->MaThongBao = $data->MaThongBao;
            $thongbao->TieuDe = $data->TieuDe;
            $thongbao->FileThongBao = $data->FileThongBao;
            $thongbao->MaNguoiDung = $data->MaNguoiDung;

            if ($thongbao->add()) {
                echo json_encode(["message" => "Thông báo đã được thêm thành công"]);
            } else {
                echo json_encode(["message" => "Thêm thông báo thất bại"]);
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
            if (!isset($data->MaThongBao, $data->TieuDe, $data->FileThongBao, $data->MaNguoiDung)) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $thongbao->MaThongBao = $data->MaThongBao;
            $thongbao->TieuDe = $data->TieuDe;
            $thongbao->FileThongBao = $data->FileThongBao;
            $thongbao->MaNguoiDung = $data->MaNguoiDung;

            if ($thongbao->update()) {
                echo json_encode(["message" => "Thông báo đã được cập nhật"]);
            } else {
                echo json_encode(["message" => "Cập nhật thông báo thất bại"]);
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
            if (!isset($data->MaThongBao)) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $thongbao->MaThongBao = $data->MaThongBao;

            if ($thongbao->delete()) {
                echo json_encode(["message" => "Thông báo đã được xóa thành công"]);
            } else {
                echo json_encode(["message" => "Xóa thông báo thất bại"]);
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
