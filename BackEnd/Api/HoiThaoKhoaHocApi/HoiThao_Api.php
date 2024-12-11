<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/HoiThao.php';

$database = new Database();
$db = $database->getConn();
$hoithao = new HoiThao($db);

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
            $stmt = $hoithao->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        if (!isset($data->MaHoiThao, $data->TenHoiThao, $data->NgayBatDau, $data->NgayKetThuc, $data->DiaDiem, $data->SoLuongThamDu, $data->MaKeHoachSoBo)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hoithao->MaHoiThao = $data->MaHoiThao;
        $hoithao->TenHoiThao = $data->TenHoiThao;
        $hoithao->NgayBatDau = $data->NgayBatDau;
        $hoithao->NgayKetThuc = $data->NgayKetThuc;
        $hoithao->DiaDiem = $data->DiaDiem;
        $hoithao->SoLuongThamDu = $data->SoLuongThamDu;
        $hoithao->MaKeHoachSoBo = $data->MaKeHoachSoBo;

        if ($hoithao->add()) {
            echo json_encode(["message" => "Hội thảo được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm hội thảo thất bại"]);
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
        if (!isset($data->MaHoiThao, $data->TenHoiThao, $data->NgayBatDau, $data->NgayKetThuc, $data->DiaDiem, $data->SoLuongThamDu, $data->MaKeHoachSoBo)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hoithao->MaHoiThao = $data->MaHoiThao;
        $hoithao->TenHoiThao = $data->TenHoiThao;
        $hoithao->NgayBatDau = $data->NgayBatDau;
        $hoithao->NgayKetThuc = $data->NgayKetThuc;
        $hoithao->DiaDiem = $data->DiaDiem;
        $hoithao->SoLuongThamDu = $data->SoLuongThamDu;
        $hoithao->MaKeHoachSoBo = $data->MaKeHoachSoBo;

        if ($hoithao->update()) {
            echo json_encode(["message" => "Hội thảo được cập nhật thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật hội thảo thất bại"]);
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
        if (!isset($data->MaHoiThao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hoithao->MaHoiThao = $data->MaHoiThao;

        if ($hoithao->delete()) {
            echo json_encode(["message" => "Hội thảo được xóa thành công"]);
        } else {
            echo json_encode(["message" => "Xóa hội thảo thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
