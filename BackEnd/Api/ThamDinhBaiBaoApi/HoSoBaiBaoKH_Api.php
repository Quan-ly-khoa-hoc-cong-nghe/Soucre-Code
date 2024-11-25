<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__. '/../../Model/ThamDinhBaiBaoModel/HoSoBaiBaoKH.php';

$database = new Database();
$db = $database->getConn();
$hosobaibao = new HoSoBaiBaoKH($db);

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
            $stmt = $hosobaibao->getAll();
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
        if (!isset($data->MaHoSo, $data->TrangThai, $data->MaNguoiDung, $data->NgayNop, $data->MaTacGia, $data->MaKhoa)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hosobaibao->MaHoSo = $data->MaHoSo;
        $hosobaibao->TrangThai = $data->TrangThai;
        $hosobaibao->NgayNop = $data->NgayNop;
        $hosobaibao->MaTacGia = $data->MaTacGia;
        $hosobaibao->MaKhoa = $data->MaKhoa;

        if ($hosobaibao->add()) {
            echo json_encode(["message" => "Thêm hồ sơ bài báo thành công"]);
        } else {
            echo json_encode(["message" => "Thêm hồ sơ bài báo thất bại"]);
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
        if (!isset($data->MaHoSo, $data->TrangThai, $data->MaNguoiDung, $data->NgayNop, $data->MaTacGia, $data->MaKhoa)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hosobaibao->MaHoSo = $data->MaHoSo;
        $hosobaibao->TrangThai = $data->TrangThai;
        $hosobaibao->NgayNop = $data->NgayNop;
        $hosobaibao->MaTacGia = $data->MaTacGia;
        $hosobaibao->MaKhoa = $data->MaKhoa;

        if ($hosobaibao->update()) {
            echo json_encode(["message" => "Cập nhật hồ sơ bài báo thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật hồ sơ bài báo thất bại"]);
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
        if (!isset($data->MaHoSo)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hosobaibao->MaHoSo = $data->MaHoSo;

        if ($hosobaibao->delete()) {
            echo json_encode(["message" => "Xóa hồ sơ bài báo thành công"]);
        } else {
            echo json_encode(["message" => "Xóa hồ sơ bài báo thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
