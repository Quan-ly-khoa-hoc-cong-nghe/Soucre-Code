<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHGiangVien/NhomNCKHGV.php';

$database = new Database();
$db = $database->getConn();
$nhom = new NhomNCKHGV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

switch ($action) {
    case 'GET':
        $data = $nhom->read();
        echo json_encode($data);
        break;

    case 'GET_ONE':
        if (!empty($_GET["MaNhomNCKHGV"])) {
            $nhom->MaNhomNCKHGV = $_GET["MaNhomNCKHGV"];
            $data = $nhom->readOne();
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(["message" => "Nhóm NCKH không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Tham số MaNhomNCKHGV không được cung cấp."]);
        }
        break;

        case 'POST':
            // Lấy dữ liệu từ request body
            $data = json_decode(file_get_contents("php://input"));
            
            // Kiểm tra xem MaDeTaiNCKHGV có được cung cấp không
            if (!empty($data->MaDeTaiNCKHGV)) {
                $nhom->MaDeTaiNCKHGV = $data->MaDeTaiNCKHGV; // Gán MaDeTaiNCKHGV từ request
        
                // Gọi phương thức create để tạo nhóm mới
                if ($nhom->create()) {
                    // Trả về thông báo thành công kèm thông tin nhóm mới
                    $newNhom = [
                        "MaNhomNCKHGV" => $nhom->MaNhomNCKHGV,
                        "MaDeTaiNCKHGV" => $nhom->MaDeTaiNCKHGV
                    ];
                    echo json_encode([
                        "message" => "Nhóm NCKH đã được tạo thành công.",
                        "data" => $newNhom // Trả về dữ liệu nhóm mới
                    ]);
                } else {
                    // Nếu không thể tạo nhóm, trả về thông báo lỗi
                    echo json_encode(["message" => "Không thể tạo nhóm NCKH."]);
                }
            } else {
                // Nếu MaDeTaiNCKHGV không được cung cấp, trả về thông báo lỗi
                echo json_encode(["message" => "Tham số MaDeTaiNCKHGV không được cung cấp."]);
            }
            break;
        
        
    

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->MaNhomNCKHGV)) {
            $nhom->MaNhomNCKHGV = $data->MaNhomNCKHGV;
            $nhom->MaDeTaiNCKHGV = $data->MaDeTaiNCKHGV;

            if ($nhom->update()) {
                echo json_encode(["message" => "Nhóm NCKH được cập nhật thành công."]);
            } else {
                echo json_encode(["message" => "Không thể cập nhật nhóm NCKH."]);
            }
        } else {
            echo json_encode(["message" => "Tham số MaNhomNCKHGV không được cung cấp."]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->MaNhomNCKHGV)) {
            $nhom->MaNhomNCKHGV = $data->MaNhomNCKHGV;

            if ($nhom->delete()) {
                echo json_encode(["message" => "Nhóm NCKH đã được xóa."]);
            } else {
                echo json_encode(["message" => "Không thể xóa nhóm NCKH."]);
            }
        } else {
            echo json_encode(["message" => "Tham số MaNhomNCKHGV không được cung cấp."]);
        }
        break;

    default:
        echo json_encode(["message" => "Hành động không được hỗ trợ: $action."]);
        break;
}
?>
