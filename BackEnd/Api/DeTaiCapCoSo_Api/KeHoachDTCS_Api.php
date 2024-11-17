<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiCapCoSoModel/KeHoachDTCS.php';

$database = new Database();
$db = $database->getConn();
$kehoach = new KeHoachDTCS($db);

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
            $stmt = $kehoach->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $maDTCS = isset($_GET['ma_dtcs']) ? $_GET['ma_dtcs'] : null;
            if (!$maDTCS) {
                echo json_encode(["message" => "Thiếu mã đề tài cơ sở"]);
                http_response_code(400);
                exit;
            }
            $kehoach->ma_dtcs = $maDTCS;
            $data = $kehoach->getOne();
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
        if (!isset($data->ngay_bat_dau, $data->ngay_ket_thuc, $data->kinh_phi, $data->file_ke_hoach, $data->ma_dtcs)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $kehoach->ngay_bat_dau = $data->ngay_bat_dau;
        $kehoach->ngay_ket_thuc = $data->ngay_ket_thuc;
        $kehoach->kinh_phi = $data->kinh_phi;
        $kehoach->file_ke_hoach = $data->file_ke_hoach;
        $kehoach->ma_dtcs = $data->ma_dtcs;

        if ($kehoach->add()) {
            echo json_encode(["message" => "Kế hoạch được thêm thành công"]);
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

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->ngay_bat_dau, $data->ngay_ket_thuc, $data->kinh_phi, $data->file_ke_hoach, $data->ma_dtcs)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $kehoach->ngay_bat_dau = $data->ngay_bat_dau;
        $kehoach->ngay_ket_thuc = $data->ngay_ket_thuc;
        $kehoach->kinh_phi = $data->kinh_phi;
        $kehoach->file_ke_hoach = $data->file_ke_hoach;
        $kehoach->ma_dtcs = $data->ma_dtcs;

        if ($kehoach->update()) {
            echo json_encode(["message" => "Kế hoạch được cập nhật thành công"]);
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
        if (!isset($data->ma_dtcs)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $kehoach->ma_dtcs = $data->ma_dtcs;

        if ($kehoach->delete()) {
            echo json_encode(["message" => "Kế hoạch được xóa thành công"]);
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
