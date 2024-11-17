<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiCapCoSoModel/HoSoDTCS.php';

$database = new Database();
$db = $database->getConn();
$hoso = new HoSoDTCS($db);

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
            $stmt = $hoso->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $maHoSo = isset($_GET['ma_ho_so']) ? $_GET['ma_ho_so'] : null;
            if (!$maHoSo) {
                echo json_encode(["message" => "Thiếu mã hồ sơ"]);
                http_response_code(400);
                exit;
            }
            $hoso->ma_ho_so = $maHoSo;
            $data = $hoso->getOne();
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
        if (!isset($data->ma_ho_so, $data->ngay_nop, $data->file_ho_so, $data->trang_thai, $data->ma_khoa)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hoso->ma_ho_so = $data->ma_ho_so;
        $hoso->ngay_nop = $data->ngay_nop;
        $hoso->file_ho_so = $data->file_ho_so;
        $hoso->trang_thai = $data->trang_thai;
        $hoso->ma_khoa = $data->ma_khoa;

        if ($hoso->add()) {
            echo json_encode(["message" => "Hồ sơ được thêm thành công"]);
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

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->ma_ho_so, $data->ngay_nop, $data->file_ho_so, $data->trang_thai, $data->ma_khoa)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hoso->ma_ho_so = $data->ma_ho_so;
        $hoso->ngay_nop = $data->ngay_nop;
        $hoso->file_ho_so = $data->file_ho_so;
        $hoso->trang_thai = $data->trang_thai;
        $hoso->ma_khoa = $data->ma_khoa;

        if ($hoso->update()) {
            echo json_encode(["message" => "Hồ sơ được cập nhật thành công"]);
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
        if (!isset($data->ma_ho_so)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hoso->ma_ho_so = $data->ma_ho_so;

        if ($hoso->delete()) {
            echo json_encode(["message" => "Hồ sơ được xóa thành công"]);
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
