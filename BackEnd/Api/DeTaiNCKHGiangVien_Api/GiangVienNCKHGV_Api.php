<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHGiangVien/DeTaiNCKHGiangVien.php';

$database = new Database();
$db = $database->getConn();
$giangvien = new GiangVienNCKHGV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

switch ($action) {
    case 'GET':
        $stmt = $giangvien->read();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;

    case 'GET_ONE':
        if (!empty($_GET["MaGV"])) {
            $giangvien->MaGV = $_GET["MaGV"];
            $data = $giangvien->readOne();
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(["message" => "Không tìm thấy dữ liệu giảng viên."]);
            }
        } else {
            echo json_encode(["message" => "Tham số MaGV không được cung cấp."]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->SoGioQuyDoi) && !empty($data->MaNhomNCKHGV) && !empty($data->MaGV)) {
            $giangvien->SoGioQuyDoi = $data->SoGioQuyDoi;
            $giangvien->MaNhomNCKHGV = $data->MaNhomNCKHGV;
            $giangvien->MaGV = $data->MaGV;

            if ($giangvien->create()) {
                echo json_encode(["message" => "Dữ liệu giảng viên được thêm mới thành công."]);
            } else {
                echo json_encode(["message" => "Không thể thêm dữ liệu giảng viên."]);
            }
        } else {
            echo json_encode(["message" => "Dữ liệu không hợp lệ."]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->MaGV)) {
            $giangvien->SoGioQuyDoi = $data->SoGioQuyDoi;
            $giangvien->MaNhomNCKHGV = $data->MaNhomNCKHGV;
            $giangvien->MaGV = $data->MaGV;

            if ($giangvien->update()) {
                echo json_encode(["message" => "Dữ liệu giảng viên được cập nhật thành công."]);
            } else {
                echo json_encode(["message" => "Không thể cập nhật dữ liệu giảng viên."]);
            }
        } else {
            echo json_encode(["message" => "Tham số MaGV không được cung cấp."]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->MaGV)) {
            $giangvien->MaGV = $data->MaGV;

            if ($giangvien->delete()) {
                echo json_encode(["message" => "Dữ liệu giảng viên đã được xóa."]);
            } else {
                echo json_encode(["message" => "Không thể xóa dữ liệu giảng viên."]);
            }
        } else {
            echo json_encode(["message" => "Tham số MaGV không được cung cấp."]);
        }
        break;

    default:
        echo json_encode(["message" => "Hành động không được hỗ trợ: $action."]);
        break;
}
?>
