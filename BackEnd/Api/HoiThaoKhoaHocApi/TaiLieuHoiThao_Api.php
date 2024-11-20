<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/TaiLieuHoiThao.php';

$database = new Database();
$db = $database->getConn();
$accessToken = 'YOUR_ACCESS_TOKEN_HERE'; // Thay bằng Access Token hợp lệ
$tailieu = new TaiLieu($db, $accessToken);

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
                $stmt = $tailieu->getAll();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(["data" => $result, "message" => "Lấy danh sách tài liệu thành công"]);
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

            if (!isset($_FILES['file']) || !isset($_POST['MaTaiLieu'], $_POST['TenTaiLieu'], $_POST['LoaiTaiLieu'], $_POST['ThoiGianTao'], $_POST['MaHoiThao'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];

            $tailieu->MaTaiLieu = $_POST['MaTaiLieu'];
            $tailieu->TenTaiLieu = $_POST['TenTaiLieu'];
            $tailieu->LoaiTaiLieu = $_POST['LoaiTaiLieu'];
            $tailieu->ThoiGianTao = $_POST['ThoiGianTao'];
            $tailieu->MaHoiThao = $_POST['MaHoiThao'];

            if ($tailieu->add($filePath, $fileName)) {
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

            parse_str(file_get_contents("php://input"), $_PUT);

            if (!isset($_PUT['MaTaiLieu'], $_PUT['TenTaiLieu'], $_PUT['LoaiTaiLieu'], $_PUT['ThoiGianTao'], $_PUT['MaHoiThao'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : null;
            $fileName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : null;

            $tailieu->MaTaiLieu = $_PUT['MaTaiLieu'];
            $tailieu->TenTaiLieu = $_PUT['TenTaiLieu'];
            $tailieu->LoaiTaiLieu = $_PUT['LoaiTaiLieu'];
            $tailieu->ThoiGianTao = $_PUT['ThoiGianTao'];
            $tailieu->MaHoiThao = $_PUT['MaHoiThao'];

            if ($tailieu->update($filePath, $fileName)) {
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

            parse_str(file_get_contents("php://input"), $_DELETE);

            if (!isset($_DELETE['MaTaiLieu'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $tailieu->MaTaiLieu = $_DELETE['MaTaiLieu'];

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
} catch (Exception $e) {
    echo json_encode(["message" => "Có lỗi xảy ra: " . $e->getMessage()]);
    http_response_code(500);
}
?>
