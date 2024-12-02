<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET,PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/HoSoNCNT.php';

$database = new Database();
$db = $database->getConn();
$hoSo = new HoSoNCNT($db);

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
            $stmt = $hoSo->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $MaHoSo = isset($_GET['MaHoSo']) ? $_GET['MaHoSo'] : null;
            if (!$MaHoSo) {
                echo json_encode(["message" => "Thiếu mã hồ sơ"]);
                http_response_code(400);
                exit;
            }
            $hoSo->MaHoSo = $MaHoSo;
            $data = $hoSo->getOne();
            echo json_encode($data);
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
        if (!isset($data->MaHoSo, $data->NgayNop, $data->FileHoSo, $data->TrangThai, $data->MaDatHang, $data->MaKhoa)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hoSo->MaHoSo = $data->MaHoSo;
        $hoSo->NgayNop = $data->NgayNop;
        $hoSo->FileHoSo = $data->FileHoSo;
        $hoSo->TrangThai = $data->TrangThai;
        $hoSo->MaDatHang = $data->MaDatHang;
        $hoSo->MaKhoa = $data->MaKhoa;

        if ($hoSo->add()) {
            echo json_encode(["message" => "Thêm hồ sơ thành công"]);
        } else {
            echo json_encode(["message" => "Thêm hồ sơ thất bại"]);
            http_response_code(500);
        }
        break;

    case 'PUT':
        if ($action !== "update") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
            http_response_code(400);
            exit;
        }

        parse_str(file_get_contents("php://input"), $_PUT);
        if (!isset($_PUT['MaHoSo'], $_PUT['NgayNop'], $_PUT['FileHoSo'], $_PUT['TrangThai'], $_PUT['MaDatHang'], $_PUT['MaKhoa'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hoSo->MaHoSo = $_PUT['MaHoSo'];
        $hoSo->NgayNop = $_PUT['NgayNop'];
        $hoSo->FileHoSo = $_PUT['FileHoSo'];
        $hoSo->TrangThai = $_PUT['TrangThai'];
        $hoSo->MaDatHang = $_PUT['MaDatHang'];
        $hoSo->MaKhoa = $_PUT['MaKhoa'];

        if ($hoSo->update()) {
            echo json_encode(["message" => "Cập nhật hồ sơ thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật hồ sơ thất bại"]);
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
        if (!isset($data->MaHoSo)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hoSo->MaHoSo = $data->MaHoSo;

        if ($hoSo->delete()) {
            echo json_encode(["message" => "Xóa hồ sơ thành công"]);
        } else {
            echo json_encode(["message" => "Xóa hồ sơ thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
