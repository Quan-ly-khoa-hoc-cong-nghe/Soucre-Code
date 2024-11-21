<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiCapCoSoModel/KeHoachDTCS.php';

// Thiết lập database và khởi tạo đối tượng KeHoachDTCS
$database = new Database();
$db = $database->getConn();
$accessToken = "YOUR_ACCESS_TOKEN"; // Thay bằng Access Token hợp lệ
$refreshToken = "YOUR_REFRESH_TOKEN"; // Thay bằng Refresh Token
$clientId = "YOUR_CLIENT_ID"; // Thay bằng Client ID từ Google API
$clientSecret = "YOUR_CLIENT_SECRET"; // Thay bằng Client Secret từ Google API
$kehoach = new KeHoachDTCS($db, $accessToken, $refreshToken, $clientId, $clientSecret);

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
                $stmt = $kehoach->getAll();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(["data" => $result, "message" => "Lấy tất cả kế hoạch thành công"]);
            } elseif ($action === "getOne") {
                $maDTCS = $_GET['ma_dtcs'] ?? null;
                if (!$maDTCS) {
                    echo json_encode(["message" => "Thiếu mã đề tài cơ sở"]);
                    http_response_code(400);
                    exit;
                }
                $kehoach->ma_dtcs = $maDTCS;
                $data = $kehoach->getOne();
                echo json_encode(["data" => $data, "message" => "Lấy kế hoạch thành công"]);
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

            if (!isset($_FILES['file']) || !isset($_POST['ngay_bat_dau'], $_POST['ngay_ket_thuc'], $_POST['kinh_phi'], $_POST['ma_dtcs'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];

            $kehoach->ngay_bat_dau = $_POST['ngay_bat_dau'];
            $kehoach->ngay_ket_thuc = $_POST['ngay_ket_thuc'];
            $kehoach->kinh_phi = $_POST['kinh_phi'];
            $kehoach->ma_dtcs = $_POST['ma_dtcs'];

            if ($kehoach->add($filePath, $fileName)) {
                echo json_encode(["message" => "Kế hoạch được thêm thành công"]);
            } else {
                echo json_encode(["message" => "Thêm kế hoạch thất bại"]);
                http_response_code(500);
            }
            break;

        case 'PUT':
            if ($action !== "update") {
                echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
                http_response_code(400);
                exit;
            }

            parse_str(file_get_contents("php://input"), $_PUT);

            if (!isset($_PUT['ngay_bat_dau'], $_PUT['ngay_ket_thuc'], $_PUT['kinh_phi'], $_PUT['ma_dtcs'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : null;
            $fileName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : null;

            $kehoach->ngay_bat_dau = $_PUT['ngay_bat_dau'];
            $kehoach->ngay_ket_thuc = $_PUT['ngay_ket_thuc'];
            $kehoach->kinh_phi = $_PUT['kinh_phi'];
            $kehoach->ma_dtcs = $_PUT['ma_dtcs'];

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
            if (!isset($_DELETE['ma_dtcs'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $kehoach->ma_dtcs = $_DELETE['ma_dtcs'];

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
