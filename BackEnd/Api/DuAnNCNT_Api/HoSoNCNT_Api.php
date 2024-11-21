<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/HoSoNCNT.php';

// Khởi tạo kết nối cơ sở dữ liệu và đối tượng class
$database = new Database();
$db = $database->getConn();
$accessToken = 'YOUR_GOOGLE_DRIVE_ACCESS_TOKEN'; // Thay bằng Access Token hợp lệ
$hoso = new HoSoNCNT($db, $accessToken);

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$action) {
    echo json_encode(["message" => "Yêu cầu không hợp lệ: thiếu tham số action"]);
    http_response_code(400);
    exit;
}

try {
    switch ($method) {
        case 'GET':
            if ($action === "get") {
                $result = $hoso->getAll();
                echo json_encode(["data" => $result, "message" => "Lấy tất cả hồ sơ thành công"]);
            } elseif ($action === "getOne") {
                $maHoSo = isset($_GET['ma_ho_so']) ? $_GET['ma_ho_so'] : null;
                if (!$maHoSo) {
                    echo json_encode(["message" => "Thiếu mã hồ sơ"]);
                    http_response_code(400);
                    exit;
                }
                $hoso->ma_ho_so = $maHoSo;
                $data = $hoso->getOne();
                if ($data) {
                    echo json_encode(["data" => $data, "message" => "Lấy thông tin hồ sơ thành công"]);
                } else {
                    echo json_encode(["message" => "Không tìm thấy hồ sơ"]);
                    http_response_code(404);
                }
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

            if (!isset($_FILES['file']) || !isset($_POST['ma_ho_so']) || !isset($_POST['ngay_nop']) || !isset($_POST['trang_thai']) || !isset($_POST['ma_dat_hang']) || !isset($_POST['ma_khoa'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];

            $hoso->ma_ho_so = $_POST['ma_ho_so'];
            $hoso->ngay_nop = $_POST['ngay_nop'];
            $hoso->trang_thai = $_POST['trang_thai'];
            $hoso->ma_dat_hang = $_POST['ma_dat_hang'];
            $hoso->ma_khoa = $_POST['ma_khoa'];

            if ($hoso->add($filePath, $fileName)) {
                echo json_encode(["message" => "Hồ sơ được thêm thành công"]);
            } else {
                echo json_encode(["message" => "Thêm hồ sơ thất bại"]);
                http_response_code(500);
            }
            break;

        case 'PUT':
            if ($action !== "put") {
                echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
                http_response_code(400);
                exit;
            }

            parse_str(file_get_contents("php://input"), $_PUT);
            if (!isset($_PUT['ma_ho_so']) || !isset($_PUT['ngay_nop']) || !isset($_PUT['trang_thai']) || !isset($_PUT['ma_dat_hang']) || !isset($_PUT['ma_khoa'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $hoso->ma_ho_so = $_PUT['ma_ho_so'];
            $hoso->ngay_nop = $_PUT['ngay_nop'];
            $hoso->trang_thai = $_PUT['trang_thai'];
            $hoso->ma_dat_hang = $_PUT['ma_dat_hang'];
            $hoso->ma_khoa = $_PUT['ma_khoa'];

            $filePath = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : null;
            $fileName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : null;

            if ($hoso->update($filePath, $fileName)) {
                echo json_encode(["message" => "Hồ sơ được cập nhật thành công"]);
            } else {
                echo json_encode(["message" => "Cập nhật hồ sơ thất bại"]);
                http_response_code(500);
            }
            break;

        case 'DELETE':
            if ($action !== "delete") {
                echo json_encode(["message" => "Action không hợp lệ cho phương thức DELETE"]);
                http_response_code(400);
                exit;
            }

            parse_str(file_get_contents("php://input"), $_DELETE);
            if (!isset($_DELETE['ma_ho_so'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $hoso->ma_ho_so = $_DELETE['ma_ho_so'];

            if ($hoso->delete()) {
                echo json_encode(["message" => "Hồ sơ được xóa thành công"]);
            } else {
                echo json_encode(["message" => "Xóa hồ sơ thất bại"]);
                http_response_code(500);
            }
            break;

        default:
            echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
            http_response_code(405);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["message" => "Có lỗi xảy ra: " . $e->getMessage()]);
    http_response_code(500);
}
?>
