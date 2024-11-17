<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiCapCoSoModel/DeTaiCapSo.php';

$database = new Database();
$db = $database->getConn();
$detai = new DeTaiCapSo($db);

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
            $stmt = $detai->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $maDTCS = isset($_GET['ma_dtcs']) ? $_GET['ma_dtcs'] : null;
            if (!$maDTCS) {
                echo json_encode(["message" => "Thiếu mã đề tài cấp sơ"]);
                http_response_code(400);
                exit;
            }
            $detai->ma_dtcs = $maDTCS;
            $data = $detai->getOne();
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
        if (!isset($data->ma_dtcs, $data->ten_de_tai, $data->ngay_bat_dau, $data->ngay_ket_thuc, $data->file_hop_dong, $data->trang_thai, $data->ma_ho_so)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $detai->ma_dtcs = $data->ma_dtcs;
        $detai->ten_de_tai = $data->ten_de_tai;
        $detai->ngay_bat_dau = $data->ngay_bat_dau;
        $detai->ngay_ket_thuc = $data->ngay_ket_thuc;
        $detai->file_hop_dong = $data->file_hop_dong;
        $detai->trang_thai = $data->trang_thai;
        $detai->ma_ho_so = $data->ma_ho_so;

        if ($detai->add()) {
            echo json_encode(["message" => "Đề tài được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm đề tài thất bại"]);
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
        if (!isset($data->ma_dtcs, $data->ten_de_tai, $data->ngay_bat_dau, $data->ngay_ket_thuc, $data->file_hop_dong, $data->trang_thai, $data->ma_ho_so)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $detai->ma_dtcs = $data->ma_dtcs;
        $detai->ten_de_tai = $data->ten_de_tai;
        $detai->ngay_bat_dau = $data->ngay_bat_dau;
        $detai->ngay_ket_thuc = $data->ngay_ket_thuc;
        $detai->file_hop_dong = $data->file_hop_dong;
        $detai->trang_thai = $data->trang_thai;
        $detai->ma_ho_so = $data->ma_ho_so;

        if ($detai->update()) {
            echo json_encode(["message" => "Đề tài được cập nhật thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật đề tài thất bại"]);
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
            echo json_encode(["message" => "Thiếu mã đề tài cấp sơ"]);
            http_response_code(400);
            exit;
        }

        $detai->ma_dtcs = $data->ma_dtcs;

        if ($detai->delete()) {
            echo json_encode(["message" => "Đề tài được xóa thành công"]);
        } else {
            echo json_encode(["message" => "Xóa đề tài thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
