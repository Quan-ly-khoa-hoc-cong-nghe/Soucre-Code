<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/DuAnNCNT.php';

$database = new Database();
$db = $database->getConn();
$duAn = new DuAnNCNT($db);

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
            $stmt = $duAn->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $MaDuAn = isset($_GET['MaDuAn']) ? $_GET['MaDuAn'] : null;
            if (!$MaDuAn) {
                echo json_encode(["message" => "Thiếu mã dự án"]);
                http_response_code(400);
                exit;
            }
            $duAn->MaDuAn = $MaDuAn;
            $data = $duAn->getOne();
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
        if (!isset($data->MaDuAn, $data->TenDuAn, $data->NgayBatDau, $data->NgayKetThuc, $data->FileHopDong, $data->TrangThai, $data->MaHoSo, $data->MaDatHang)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $duAn->MaDuAn = $data->MaDuAn;
        $duAn->TenDuAn = $data->TenDuAn;
        $duAn->NgayBatDau = $data->NgayBatDau;
        $duAn->NgayKetThuc = $data->NgayKetThuc;
        $duAn->FileHopDong = $data->FileHopDong;
        $duAn->TrangThai = $data->TrangThai;
        $duAn->MaHoSo = $data->MaHoSo;
        $duAn->MaDatHang = $data->MaDatHang;

        if ($duAn->add()) {
            echo json_encode(["message" => "Thêm dự án thành công"]);
        } else {
            echo json_encode(["message" => "Thêm dự án thất bại"]);
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
        if (!isset($_PUT['MaDuAn'], $_PUT['TenDuAn'], $_PUT['NgayBatDau'], $_PUT['NgayKetThuc'], $_PUT['FileHopDong'], $_PUT['TrangThai'], $_PUT['MaHoSo'], $_PUT['MaDatHang'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $duAn->MaDuAn = $_PUT['MaDuAn'];
        $duAn->TenDuAn = $_PUT['TenDuAn'];
        $duAn->NgayBatDau = $_PUT['NgayBatDau'];
        $duAn->NgayKetThuc = $_PUT['NgayKetThuc'];
        $duAn->FileHopDong = $_PUT['FileHopDong'];
        $duAn->TrangThai = $_PUT['TrangThai'];
        $duAn->MaHoSo = $_PUT['MaHoSo'];
        $duAn->MaDatHang = $_PUT['MaDatHang'];

        if ($duAn->update()) {
            echo json_encode(["message" => "Cập nhật dự án thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật dự án thất bại"]);
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
        if (!isset($data->MaDuAn)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $duAn->MaDuAn = $data->MaDuAn;

        if ($duAn->delete()) {
            echo json_encode(["message" => "Xóa dự án thành công"]);
        } else {
            echo json_encode(["message" => "Xóa dự án thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
