<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__. '/../../Model/HoiThaoKhoaHocModel/KeHoachHoiThao.php';


$database = new Database();
$db = $database->getConn();
$kehoach = new KeHoachHoiThao($db);

// Lấy phương thức HTTP và tham số `action`
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

// Kiểm tra tham số `action`
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
        if (!isset($data->NgayBatDau, $data->NgayKetThuc, $data->KinhPhi, $data->FileKeHoach, $data->MaHoiThao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $kehoach->NgayBatDau = $data->NgayBatDau;
        $kehoach->NgayKetThuc = $data->NgayKetThuc;
        $kehoach->KinhPhi = $data->KinhPhi;
        $kehoach->FileKeHoach = $data->FileKeHoach;
        $kehoach->MaHoiThao = $data->MaHoiThao;

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
        if (!isset($data->NgayBatDau, $data->NgayKetThuc, $data->KinhPhi, $data->FileKeHoach, $data->MaHoiThao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $kehoach->NgayBatDau = $data->NgayBatDau;
        $kehoach->NgayKetThuc = $data->NgayKetThuc;
        $kehoach->KinhPhi = $data->KinhPhi;
        $kehoach->FileKeHoach = $data->FileKeHoach;
        $kehoach->MaHoiThao = $data->MaHoiThao;

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
        if (!isset($data->MaHoiThao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $kehoach->MaHoiThao = $data->MaHoiThao;

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
