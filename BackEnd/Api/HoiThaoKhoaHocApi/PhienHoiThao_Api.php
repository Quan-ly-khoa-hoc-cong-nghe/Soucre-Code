<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/PhienHoiThao.php';

$database = new Database();
$db = $database->getConn();
$phienhoithao = new PhienHoiThao($db);

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
            $stmt = $phienhoithao->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
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
        if (!isset($data->MaPhienHoiThao, $data->TenPhienHoiThao, $data->ThoiGianBatDau, $data->ThoiGianKetThuc, $data->MoTa, $data->MaHoiThao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $phienhoithao->MaPhienHoiThao = $data->MaPhienHoiThao;
        $phienhoithao->TenPhienHoiThao = $data->TenPhienHoiThao;
        $phienhoithao->ThoiGianBatDau = $data->ThoiGianBatDau;
        $phienhoithao->ThoiGianKetThuc = $data->ThoiGianKetThuc;
        $phienhoithao->MoTa = $data->MoTa;
        $phienhoithao->MaHoiThao = $data->MaHoiThao;

        if ($phienhoithao->add()) {
            echo json_encode(["message" => "Phiên hội thảo được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm phiên hội thảo thất bại"]);
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
        if (!isset($data->MaPhienHoiThao, $data->TenPhienHoiThao, $data->ThoiGianBatDau, $data->ThoiGianKetThuc, $data->MoTa, $data->MaHoiThao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $phienhoithao->MaPhienHoiThao = $data->MaPhienHoiThao;
        $phienhoithao->TenPhienHoiThao = $data->TenPhienHoiThao;
        $phienhoithao->ThoiGianBatDau = $data->ThoiGianBatDau;
        $phienhoithao->ThoiGianKetThuc = $data->ThoiGianKetThuc;
        $phienhoithao->MoTa = $data->MoTa;
        $phienhoithao->MaHoiThao = $data->MaHoiThao;

        if ($phienhoithao->update()) {
            echo json_encode(["message" => "Phiên hội thảo được cập nhật thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật phiên hội thảo thất bại"]);
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
        if (!isset($data->MaPhienHoiThao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $phienhoithao->MaPhienHoiThao = $data->MaPhienHoiThao;

        if ($phienhoithao->delete()) {
            echo json_encode(["message" => "Phiên hội thảo được xóa thành công"]);
        } else {
            echo json_encode(["message" => "Xóa phiên hội thảo thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
