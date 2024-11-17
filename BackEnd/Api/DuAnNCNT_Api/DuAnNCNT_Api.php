<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/DuAnNCNT.php';

$database = new Database();
$db = $database->getConn();
$duan = new DuAnNCNT($db);

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
            $stmt = $duan->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $maDuAn = isset($_GET['ma_du_an']) ? $_GET['ma_du_an'] : null;
            if (!$maDuAn) {
                echo json_encode(["message" => "Thiếu mã dự án"]);
                http_response_code(400);
                exit;
            }
            $duan->ma_du_an = $maDuAn;
            $data = $duan->getOne();
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
        if (!isset($data->ma_du_an, $data->ten_du_an, $data->ngay_bat_dau, $data->ngay_ket_thuc, $data->file_hop_dong, $data->trang_thai, $data->ma_ho_so, $data->ma_dat_hang)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $duan->ma_du_an = $data->ma_du_an;
        $duan->ten_du_an = $data->ten_du_an;
        $duan->ngay_bat_dau = $data->ngay_bat_dau;
        $duan->ngay_ket_thuc = $data->ngay_ket_thuc;
        $duan->file_hop_dong = $data->file_hop_dong;
        $duan->trang_thai = $data->trang_thai;
        $duan->ma_ho_so = $data->ma_ho_so;
        $duan->ma_dat_hang = $data->ma_dat_hang;

        if ($duan->add()) {
            echo json_encode(["message" => "Dự án được thêm thành công"]);
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

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->ma_du_an, $data->ten_du_an, $data->ngay_bat_dau, $data->ngay_ket_thuc, $data->file_hop_dong, $data->trang_thai, $data->ma_ho_so, $data->ma_dat_hang)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $duan->ma_du_an = $data->ma_du_an;
        $duan->ten_du_an = $data->ten_du_an;
        $duan->ngay_bat_dau = $data->ngay_bat_dau;
        $duan->ngay_ket_thuc = $data->ngay_ket_thuc;
        $duan->file_hop_dong = $data->file_hop_dong;
        $duan->trang_thai = $data->trang_thai;
        $duan->ma_ho_so = $data->ma_ho_so;
        $duan->ma_dat_hang = $data->ma_dat_hang;

        if ($duan->update()) {
            echo json_encode(["message" => "Dự án được cập nhật thành công"]);
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
        if (!isset($data->ma_du_an)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $duan->ma_du_an = $data->ma_du_an;

        if ($duan->delete()) {
            echo json_encode(["message" => "Dự án được xóa thành công"]);
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
