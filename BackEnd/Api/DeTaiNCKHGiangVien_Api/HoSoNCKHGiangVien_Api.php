<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHGiangVien/HoSoNCKHGiangVien.php';

$database = new Database();
$db = $database->getConn();
$hoso = new HoSoNCKHGV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;
if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

switch ($action) {
    case 'GET':
        // Lấy tất cả hồ sơ
        $stmt = $hoso->read();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;

    case 'GET_ONE':
        // Lấy một hồ sơ theo MaHoSo
        if (!empty($_GET["MaHoSo"])) {
            $hoso->MaHoSo = $_GET["MaHoSo"];
            $data = $hoso->readOne();
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(["message" => "Hồ sơ không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "MaHoSo không được cung cấp."]);
        }
        break;

    case 'POST':
        // Tạo mới hồ sơ
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->MaHoSo) && !empty($data->FileHoSo) && !empty($data->TrangThai)) {
            $hoso->MaHoSo = $data->MaHoSo;
            $hoso->NgayNop = $data->NgayNop;
            $hoso->FileHoSo = $data->FileHoSo;
            $hoso->TrangThai = $data->TrangThai;

            if ($hoso->create()) {
                echo json_encode(["message" => "Hồ sơ được tạo thành công."]);
            } else {
                echo json_encode(["message" => "Không thể tạo hồ sơ."]);
            }
        } else {
            echo json_encode(["message" => "Dữ liệu không hợp lệ."]);
        }
        break;

    case 'PUT':
        // Cập nhật hồ sơ
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->MaHoSo)) {
            $hoso->MaHoSo = $data->MaHoSo;
            $hoso->NgayNop = $data->NgayNop;
            $hoso->FileHoSo = $data->FileHoSo;
            $hoso->TrangThai = $data->TrangThai;

            if ($hoso->update()) {
                echo json_encode(["message" => "Hồ sơ được cập nhật thành công."]);
            } else {
                echo json_encode(["message" => "Không thể cập nhật hồ sơ."]);
            }
        } else {
            echo json_encode(["message" => "MaHoSo không được cung cấp."]);
        }
        break;

    case 'DELETE':
        // Xóa hồ sơ
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->MaHoSo)) {
            $hoso->MaHoSo = $data->MaHoSo;

            if ($hoso->delete()) {
                echo json_encode(["message" => "Hồ sơ đã bị xóa."]);
            } else {
                echo json_encode(["message" => "Không thể xóa hồ sơ."]);
            }
        } else {
            echo json_encode(["message" => "MaHoSo không được cung cấp."]);
        }
        break;

    default:
        // Action không hợp lệ
        echo json_encode(["message" => "Hành động không được hỗ trợ."]);
        break;
}
?>
