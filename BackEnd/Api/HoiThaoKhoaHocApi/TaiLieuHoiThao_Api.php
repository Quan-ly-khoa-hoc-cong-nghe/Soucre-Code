<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/TaiLieuHoiThao.php';

$database = new Database();
$db = $database->getConn();
$tailieu = new TaiLieu($db);

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
            $stmt = $tailieu->getAll();
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
        if (!isset($data->MaTaiLieu, $data->TenTaiLieu, $data->LoaiTaiLieu, $data->DuongDanFile, $data->ThoiGianTao, $data->MaHoiThao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $tailieu->MaTaiLieu = $data->MaTaiLieu;
        $tailieu->TenTaiLieu = $data->TenTaiLieu;
        $tailieu->LoaiTaiLieu = $data->LoaiTaiLieu;
        $tailieu->DuongDanFile = $data->DuongDanFile;
        $tailieu->ThoiGianTao = $data->ThoiGianTao;
        $tailieu->MaHoiThao = $data->MaHoiThao;

        if ($tailieu->add()) {
            echo json_encode(["message" => "Tài liệu được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm tài liệu thất bại"]);
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
        if (!isset($data->MaTaiLieu, $data->TenTaiLieu, $data->LoaiTaiLieu, $data->DuongDanFile, $data->ThoiGianTao, $data->MaHoiThao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $tailieu->MaTaiLieu = $data->MaTaiLieu;
        $tailieu->TenTaiLieu = $data->TenTaiLieu;
        $tailieu->LoaiTaiLieu = $data->LoaiTaiLieu;
        $tailieu->DuongDanFile = $data->DuongDanFile;
        $tailieu->ThoiGianTao = $data->ThoiGianTao;
        $tailieu->MaHoiThao = $data->MaHoiThao;

        if ($tailieu->update()) {
            echo json_encode(["message" => "Tài liệu được cập nhật thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật tài liệu thất bại"]);
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
        if (!isset($data->MaTaiLieu)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $tailieu->MaTaiLieu = $data->MaTaiLieu;

        if ($tailieu->delete()) {
            echo json_encode(["message" => "Tài liệu được xóa thành công"]);
        } else {
            echo json_encode(["message" => "Xóa tài liệu thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
