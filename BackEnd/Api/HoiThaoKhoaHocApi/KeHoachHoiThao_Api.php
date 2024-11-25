<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/KeHoachHoiThao.php';

$database = new Database();
$db = $database->getConn();
$keHoachHoiThao = new KeHoachHoiThao($db);

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
            $result = $keHoachHoiThao->getAll();
            echo json_encode($result);
        } elseif ($action === "getOne") {
            if (!isset($_GET['MaHoiThao'])) {
                echo json_encode(["message" => "Yêu cầu không hợp lệ: thiếu tham số MaHoiThao"]);
                http_response_code(400);
                exit;
            }
            $keHoachHoiThao->ma_hoi_thao = $_GET['MaHoiThao'];
            $result = $keHoachHoiThao->getOne();
            echo json_encode($result);
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
        if (!isset($data->ngay_bat_dau, $data->ngay_ket_thuc, $data->kinh_phi, $data->file_ke_hoach, $data->ma_hoi_thao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $keHoachHoiThao->ngay_bat_dau = $data->ngay_bat_dau;
        $keHoachHoiThao->ngay_ket_thuc = $data->ngay_ket_thuc;
        $keHoachHoiThao->kinh_phi = $data->kinh_phi;
        $keHoachHoiThao->file_ke_hoach = $data->file_ke_hoach;
        $keHoachHoiThao->ma_hoi_thao = $data->ma_hoi_thao;

        if ($keHoachHoiThao->add()) {
            echo json_encode(["message" => "Kế hoạch hội thảo được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm kế hoạch hội thảo thất bại"]);
            http_response_code(500);
        }
        break;

    case 'PUT':
        if ($action !== "put") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->ngay_bat_dau, $data->ngay_ket_thuc, $data->kinh_phi, $data->file_ke_hoach, $data->ma_hoi_thao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $keHoachHoiThao->ngay_bat_dau = $data->ngay_bat_dau;
        $keHoachHoiThao->ngay_ket_thuc = $data->ngay_ket_thuc;
        $keHoachHoiThao->kinh_phi = $data->kinh_phi;
        $keHoachHoiThao->file_ke_hoach = $data->file_ke_hoach;
        $keHoachHoiThao->ma_hoi_thao = $data->ma_hoi_thao;

        if ($keHoachHoiThao->update()) {
            echo json_encode(["message" => "Kế hoạch hội thảo được cập nhật thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật kế hoạch hội thảo thất bại"]);
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
        if (!isset($data->ma_hoi_thao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $keHoachHoiThao->ma_hoi_thao = $data->ma_hoi_thao;

        if ($keHoachHoiThao->delete()) {
            echo json_encode(["message" => "Kế hoạch hội thảo được xóa thành công"]);
        } else {
            echo json_encode(["message" => "Xóa kế hoạch hội thảo thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
