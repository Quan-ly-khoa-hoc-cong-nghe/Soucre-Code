<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/DonDatHang.php';

$database = new Database();
$db = $database->getConn();
$donDatHang = new DonDatHang($db);

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
            $stmt = $donDatHang->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $MaDatHang = isset($_GET['MaDatHang']) ? $_GET['MaDatHang'] : null;
            if (!$MaDatHang) {
                echo json_encode(["message" => "Thiếu mã đơn đặt hàng"]);
                http_response_code(400);
                exit;
            }
            $donDatHang->MaDatHang = $MaDatHang;
            $data = $donDatHang->getOne();
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
        if (!isset($data->MaDatHang, $data->NgayDat, $data->FileDatHang, $data->MaDoiTac)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $donDatHang->MaDatHang = $data->MaDatHang;
        $donDatHang->NgayDat = $data->NgayDat;
        $donDatHang->FileDatHang = $data->FileDatHang;
        $donDatHang->MaDoiTac = $data->MaDoiTac;

        if ($donDatHang->add()) {
            echo json_encode(["message" => "Thêm đơn đặt hàng thành công"]);
        } else {
            echo json_encode(["message" => "Thêm đơn đặt hàng thất bại"]);
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
        if (!isset($_PUT['MaDatHang'], $_PUT['NgayDat'], $_PUT['FileDatHang'], $_PUT['MaDoiTac'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $donDatHang->MaDatHang = $_PUT['MaDatHang'];
        $donDatHang->NgayDat = $_PUT['NgayDat'];
        $donDatHang->FileDatHang = $_PUT['FileDatHang'];
        $donDatHang->MaDoiTac = $_PUT['MaDoiTac'];

        if ($donDatHang->update()) {
            echo json_encode(["message" => "Cập nhật đơn đặt hàng thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật đơn đặt hàng thất bại"]);
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
        if (!isset($data->MaDatHang)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $donDatHang->MaDatHang = $data->MaDatHang;

        if ($donDatHang->delete()) {
            echo json_encode(["message" => "Xóa đơn đặt hàng thành công"]);
        } else {
            echo json_encode(["message" => "Xóa đơn đặt hàng thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
