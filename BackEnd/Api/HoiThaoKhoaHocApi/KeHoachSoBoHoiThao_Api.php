<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/KeHoachSoBo.php';

$database = new Database();
$db = $database->getConn();
$accessToken = 'YOUR_ACCESS_TOKEN_HERE'; // Thay bằng Access Token hợp lệ từ OAuth 2.0 Playground
$kehoach = new KeHoachSoBo($db, $accessToken);

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
                $result = $kehoach->getAll();
                echo json_encode(["data" => $result, "message" => "Lấy tất cả kế hoạch thành công"]);
            } elseif ($action === "getOne") {
                $maKeHoachSoBo = $_GET['MaKeHoachSoBo'] ?? null;
                if (!$maKeHoachSoBo) {
                    echo json_encode(["message" => "Thiếu mã kế hoạch sơ bộ"]);
                    http_response_code(400);
                    exit;
                }
                $kehoach->MaKeHoachSoBo = $maKeHoachSoBo;
                $data = $kehoach->getOne();
                if ($data) {
                    echo json_encode(["data" => $data, "message" => "Lấy kế hoạch thành công"]);
                } else {
                    echo json_encode(["message" => "Không tìm thấy kế hoạch"]);
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

            if (!isset($_FILES['file']) || !isset($_POST['MaKeHoachSoBo'], $_POST['NgayGui'], $_POST['TrangThai'], $_POST['MaKhoa'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];

            $kehoach->MaKeHoachSoBo = $_POST['MaKeHoachSoBo'];
            $kehoach->NgayGui = $_POST['NgayGui'];
            $kehoach->TrangThai = $_POST['TrangThai'];
            $kehoach->MaKhoa = $_POST['MaKhoa'];

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
            if (!isset($_PUT['MaKeHoachSoBo'], $_PUT['NgayGui'], $_PUT['TrangThai'], $_PUT['MaKhoa'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $kehoach->MaKeHoachSoBo = $_PUT['MaKeHoachSoBo'];
            $kehoach->NgayGui = $_PUT['NgayGui'];
            $kehoach->TrangThai = $_PUT['TrangThai'];
            $kehoach->MaKhoa = $_PUT['MaKhoa'];

            $filePath = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : null;
            $fileName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : null;

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
            if (!isset($_DELETE['MaKeHoachSoBo'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $kehoach->MaKeHoachSoBo = $_DELETE['MaKeHoachSoBo'];

            if ($kehoach->delete()) {
                echo json_encode(["message" => "Kế hoạch và file trên Google Drive đã được xóa thành công"]);
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
