<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once __DIR__. '/../../Model/HoiThaoKhoaHocModel/KeHoachHoiThao.php';

// Khởi tạo kết nối cơ sở dữ liệu
$database = new Database();
$db = $database->getConn();
$accessToken = 'YOUR_ACCESS_TOKEN_HERE'; // Thay bằng Access Token hợp lệ
$kehoach = new KeHoachHoiThao($db, $accessToken);

// Lấy phương thức HTTP và tham số `action`
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

// Kiểm tra tham số `action`
if (!$action) {
    echo json_encode(["message" => "Yêu cầu không hợp lệ: thiếu tham số action"]);
    http_response_code(400);
    exit;
}

try {
    switch ($method) {
        case 'GET':
            if ($action === "get") {
                $result = $kehoach->getAll();
                echo json_encode(["data" => $result, "message" => "Lấy tất cả kế hoạch thành công"]);
            } elseif ($action === "getOne") {
                $maHoiThao = $_GET['MaHoiThao'] ?? null;
                if (!$maHoiThao) {
                    echo json_encode(["message" => "Thiếu mã hội thảo"]);
                    http_response_code(400);
                    exit;
                }
                $kehoach->ma_hoi_thao = $maHoiThao;
                $data = $kehoach->getOne();
                echo json_encode(["data" => $data, "message" => "Lấy kế hoạch thành công"]);
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

            if (!isset($_FILES['file']) || !isset($_POST['NgayBatDau'], $_POST['NgayKetThuc'], $_POST['KinhPhi'], $_POST['MaHoiThao'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];

            $kehoach->ngay_bat_dau = $_POST['NgayBatDau'];
            $kehoach->ngay_ket_thuc = $_POST['NgayKetThuc'];
            $kehoach->kinh_phi = $_POST['KinhPhi'];
            $kehoach->ma_hoi_thao = $_POST['MaHoiThao'];

            if ($kehoach->add($filePath, $fileName)) {
                echo json_encode(["message" => "Kế hoạch được thêm thành công"]);
            } else {
                echo json_encode(["message" => "Thêm kế hoạch thất bại"]);
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
            if (!isset($_PUT['NgayBatDau'], $_PUT['NgayKetThuc'], $_PUT['KinhPhi'], $_PUT['MaHoiThao'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : null;
            $fileName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : null;

            $kehoach->ngay_bat_dau = $_PUT['NgayBatDau'];
            $kehoach->ngay_ket_thuc = $_PUT['NgayKetThuc'];
            $kehoach->kinh_phi = $_PUT['KinhPhi'];
            $kehoach->ma_hoi_thao = $_PUT['MaHoiThao'];

            if ($kehoach->update($filePath, $fileName)) {
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

            parse_str(file_get_contents("php://input"), $_DELETE);
            if (!isset($_DELETE['MaHoiThao'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $kehoach->ma_hoi_thao = $_DELETE['MaHoiThao'];

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
} catch (Exception $e) {
    echo json_encode(["message" => "Có lỗi xảy ra: " . $e->getMessage()]);
    http_response_code(500);
}
?>
