<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/NhomNCNT.php';

$database = new Database();
$db = $database->getConn();
$nhom = new NhomNCNT($db);

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
            $stmt = $nhom->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $maHoSo = isset($_GET['ma_ho_so']) ? $_GET['ma_ho_so'] : null;
            $maGV = isset($_GET['ma_gv']) ? $_GET['ma_gv'] : null;
            if (!$maHoSo || !$maGV) {
                echo json_encode(["message" => "Thiếu mã hồ sơ hoặc mã giảng viên"]);
                http_response_code(400);
                exit;
            }
            $nhom->ma_ho_so = $maHoSo;
            $nhom->ma_gv = $maGV;
            $data = $nhom->getOne();
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
        if (!isset($data->ma_ho_so, $data->ma_gv)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nhom->ma_ho_so = $data->ma_ho_so;
        $nhom->ma_gv = $data->ma_gv;

        if ($nhom->add()) {
            echo json_encode(["message" => "Thêm thành viên vào nhóm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm thành viên vào nhóm thất bại"]);
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
        if (!isset($_PUT['ma_ho_so'], $_PUT['ma_gv'], $_PUT['ma_gv_moi'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nhom->ma_ho_so = $_PUT['ma_ho_so'];
        $nhom->ma_gv = $_PUT['ma_gv'];
        $maGVMoi = $_PUT['ma_gv_moi'];

        if ($nhom->update($maGVMoi)) {
            echo json_encode(["message" => "Cập nhật thông tin thành viên trong nhóm thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật thông tin thành viên thất bại"]);
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
        if (!isset($data->ma_ho_so, $data->ma_gv)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nhom->ma_ho_so = $data->ma_ho_so;
        $nhom->ma_gv = $data->ma_gv;

        if ($nhom->delete()) {
            echo json_encode(["message" => "Xóa thành viên khỏi nhóm thành công"]);
        } else {
            echo json_encode(["message" => "Xóa thành viên khỏi nhóm thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
