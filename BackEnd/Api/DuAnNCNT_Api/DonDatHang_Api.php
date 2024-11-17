<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNT/DonDatHang.php';

$database = new Database();
$db = $database->getConn();
$dondathang = new DonDatHang($db);

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
            $stmt = $dondathang->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $maDatHang = isset($_GET['ma_dat_hang']) ? $_GET['ma_dat_hang'] : null;
            if (!$maDatHang) {
                echo json_encode(["message" => "Thiếu mã đơn đặt hàng"]);
                http_response_code(400);
                exit;
            }
            $dondathang->ma_dat_hang = $maDatHang;
            $data = $dondathang->getOne();
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
        if (!isset($data->ma_dat_hang, $data->ngay_dat, $data->file_dat_hang, $data->ma_doi_tac)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $dondathang->ma_dat_hang = $data->ma_dat_hang;
        $dondathang->ngay_dat = $data->ngay_dat;
        $dondathang->file_dat_hang = $data->file_dat_hang;
        $dondathang->ma_doi_tac = $data->ma_doi_tac;

        if ($dondathang->add()) {
            echo json_encode(["message" => "Đơn đặt hàng được thêm thành công"]);
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

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->ma_dat_hang, $data->ngay_dat, $data->file_dat_hang, $data->ma_doi_tac)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $dondathang->ma_dat_hang = $data->ma_dat_hang;
        $dondathang->ngay_dat = $data->ngay_dat;
        $dondathang->file_dat_hang = $data->file_dat_hang;
        $dondathang->ma_doi_tac = $data->ma_doi_tac;

        if ($dondathang->update()) {
            echo json_encode(["message" => "Đơn đặt hàng được cập nhật thành công"]);
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
        if (!isset($data->ma_dat_hang)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $dondathang->ma_dat_hang = $data->ma_dat_hang;

        if ($dondathang->delete()) {
            echo json_encode(["message" => "Đơn đặt hàng được xóa thành công"]);
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
