<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/DienGiaPhien.php';

$database = new Database();
$db = $database->getConn();
$diengiaphien = new DienGiaPhien($db);

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
            $stmt = $diengiaphien->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
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
        if (!isset($data->MaPhienHoiThao, $data->MaNguoiThamGia, $data->ChuyenDe)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $diengiaphien->MaPhienHoiThao = $data->MaPhienHoiThao;
        $diengiaphien->MaNguoiThamGia = $data->MaNguoiThamGia;
        $diengiaphien->ChuyenDe = $data->ChuyenDe;

        if ($diengiaphien->add()) {
            echo json_encode(["message" => "Diễn giả phiên được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm diễn giả phiên thất bại"]);
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
        if (!isset($data->MaPhienHoiThao, $data->MaNguoiThamGia, $data->ChuyenDe)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $diengiaphien->MaPhienHoiThao = $data->MaPhienHoiThao;
        $diengiaphien->MaNguoiThamGia = $data->MaNguoiThamGia;
        $diengiaphien->ChuyenDe = $data->ChuyenDe;

        if ($diengiaphien->update()) {
            echo json_encode(["message" => "Diễn giả phiên được cập nhật thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật diễn giả phiên thất bại"]);
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
        if (!isset($data->MaPhienHoiThao, $data->MaNguoiThamGia)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $diengiaphien->MaPhienHoiThao = $data->MaPhienHoiThao;
        $diengiaphien->MaNguoiThamGia = $data->MaNguoiThamGia;

        if ($diengiaphien->delete()) {
            echo json_encode(["message" => "Diễn giả phiên được xóa thành công"]);
        } else {
            echo json_encode(["message" => "Xóa diễn giả phiên thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
