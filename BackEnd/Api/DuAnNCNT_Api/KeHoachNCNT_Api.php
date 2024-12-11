<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/KeHoachNCNT.php';

$database = new Database();
$db = $database->getConn();
$keHoach = new KeHoachNCNT($db);

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
            $stmt = $keHoach->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $maDuAn = isset($_GET['MaDuAn']) ? $_GET['MaDuAn'] : null;
            if (!$maDuAn) {
                echo json_encode(["message" => "Thiếu mã dự án"]);
                http_response_code(400);
                exit;
            }
            $keHoach->MaDuAn = $maDuAn;
            $data = $keHoach->getOne();
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
        if (!isset($data->NgayBatDau, $data->NgayKetThuc, $data->KinhPhi, $data->fileKeHoach, $data->MaDuAn)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $keHoach->NgayBatDau = $data->NgayBatDau;
        $keHoach->NgayKetThuc = $data->NgayKetThuc;
        $keHoach->KinhPhi = $data->KinhPhi;
        $keHoach->fileKeHoach = $data->fileKeHoach;
        $keHoach->MaDuAn = $data->MaDuAn;

        if ($keHoach->add()) {
            echo json_encode(["message" => "Thêm kế hoạch thành công"]);
        } else {
            echo json_encode(["message" => "Thêm kế hoạch thất bại"]);
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
        if (!isset($_PUT['NgayBatDau'], $_PUT['NgayKetThuc'], $_PUT['KinhPhi'], $_PUT['fileKeHoach'], $_PUT['MaDuAn'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $keHoach->NgayBatDau = $_PUT['NgayBatDau'];
        $keHoach->NgayKetThuc = $_PUT['NgayKetThuc'];
        $keHoach->KinhPhi = $_PUT['KinhPhi'];
        $keHoach->fileKeHoach = $_PUT['fileKeHoach'];
        $keHoach->MaDuAn = $_PUT['MaDuAn'];

        if ($keHoach->update()) {
            echo json_encode(["message" => "Cập nhật kế hoạch thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật kế hoạch thất bại"]);
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

        $keHoach->MaDuAn = $data->MaDuAn;

        if ($keHoach->delete()) {
            echo json_encode(["message" => "Xóa kế hoạch thành công"]);
        } else {
            echo json_encode(["message" => "Xóa kế hoạch thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
