<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/TaiLieuHoiThao.php';

$database = new Database();
$db = $database->getConn();
$taiLieu = new TaiLieu($db);

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
            $stmt = $taiLieu->getAll();
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
        if (!isset($data->MaTaiLieu, $data->TenTaiLieu, $data->LoaiTaiLieu, $data->DuongDanFile, $data->ThoiGianTao, $data->MaHoiThao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $taiLieu->MaTaiLieu = $data->MaTaiLieu;
        $taiLieu->TenTaiLieu = $data->TenTaiLieu;
        $taiLieu->LoaiTaiLieu = $data->LoaiTaiLieu;
        $taiLieu->DuongDanFile = $data->DuongDanFile;
        $taiLieu->ThoiGianTao = $data->ThoiGianTao;
        $taiLieu->MaHoiThao = $data->MaHoiThao;

        if ($taiLieu->add()) {
            echo json_encode(["message" => "Tài liệu được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm tài liệu thất bại"]);
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
        if (!isset($data->MaTaiLieu, $data->TenTaiLieu, $data->LoaiTaiLieu, $data->DuongDanFile, $data->ThoiGianTao, $data->MaHoiThao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $taiLieu->MaTaiLieu = $data->MaTaiLieu;
        $taiLieu->TenTaiLieu = $data->TenTaiLieu;
        $taiLieu->LoaiTaiLieu = $data->LoaiTaiLieu;
        $taiLieu->DuongDanFile = $data->DuongDanFile;
        $taiLieu->ThoiGianTao = $data->ThoiGianTao;
        $taiLieu->MaHoiThao = $data->MaHoiThao;

        if ($taiLieu->update()) {
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

        $taiLieu->MaTaiLieu = $data->MaTaiLieu;

        if ($taiLieu->delete()) {
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
